from __future__ import annotations

import asyncio
import json
import time
from datetime import datetime, timedelta, timezone
from pathlib import Path
from typing import Any

import anyio
import httpx
from fastapi import FastAPI, HTTPException, Request
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import JSONResponse

from app.config import get_settings


settings = get_settings()
allow_origins = (
    ["*"]
    if settings.cors_origins.strip() == "*"
    else [origin.strip() for origin in settings.cors_origins.split(",") if origin.strip()]
)

app = FastAPI(title="BitMixList Site Status")
app.add_middleware(
    CORSMiddleware,
    allow_origins=allow_origins,
    allow_credentials=False,
    allow_methods=["GET", "POST", "OPTIONS"],
    allow_headers=["*"],
)

_memory_payload: dict[str, Any] | None = None
_refresh_lock = asyncio.Lock()


def _firestore_doc():
    if not settings.firestore_project_id:
        return None
    try:
        from google.cloud import firestore as firestore_client
    except Exception:  # pragma: no cover - optional in local development
        return None

    client = firestore_client.Client(project=settings.firestore_project_id)
    return client.collection(settings.firestore_collection).document(
        settings.firestore_document
    )


firestore_doc = _firestore_doc()


def _utcnow() -> datetime:
    return datetime.now(timezone.utc)


def _iso(value: datetime) -> str:
    return value.replace(microsecond=0).isoformat().replace("+00:00", "Z")


def _is_onion(url: str) -> bool:
    try:
        host = httpx.URL(url).host or ""
    except Exception:
        return ".onion" in url.lower()
    return host.lower().endswith(".onion")


def _without_onion_targets(service: dict[str, Any]) -> dict[str, Any]:
    raw_targets = service.get("targets")
    targets = raw_targets if isinstance(raw_targets, list) else []
    service = dict(service)
    service["targets"] = [
        target
        for target in targets
        if isinstance(target, dict) and not _is_onion(str(target.get("url") or ""))
    ]
    return service


async def _load_targets() -> list[dict[str, Any]]:
    if settings.targets_url:
        async with httpx.AsyncClient(timeout=settings.check_timeout_seconds) as client:
            response = await client.get(
                settings.targets_url,
                headers={
                    "Accept": "application/json",
                    "User-Agent": settings.user_agent,
                },
            )
            response.raise_for_status()
            data = response.json()
    else:
        path = Path(settings.targets_file)
        if not path.is_file():
            path = Path(__file__).resolve().parents[1] / settings.targets_file
        if not path.is_file():
            raise RuntimeError(f"Target manifest not found: {settings.targets_file}")
        data = json.loads(path.read_text(encoding="utf-8"))

    targets = data.get("targets") if isinstance(data, dict) else None
    if not isinstance(targets, list):
        raise RuntimeError("Target manifest must contain a targets array")
    return [
        _without_onion_targets(target)
        for target in targets
        if isinstance(target, dict)
    ]


async def _probe_url(target: dict[str, Any]) -> dict[str, Any]:
    url = str(target.get("url") or "").strip()
    kind = str(target.get("kind") or "clearnet")
    started = time.perf_counter()
    base = {
        "kind": kind,
        "url": url,
        "source": str(target.get("source") or ""),
        "checked_at": _iso(_utcnow()),
    }
    if not url:
        return base | {"status": "unknown", "error": "Missing URL"}
    if _is_onion(url):
        return base | {
            "status": "unknown",
            "error": "Onion targets are not checked",
        }

    client_kwargs: dict[str, Any] = {
        "follow_redirects": True,
        "timeout": settings.check_timeout_seconds,
        "headers": {
            "Accept": "text/html,application/xhtml+xml,application/json;q=0.8,*/*;q=0.5",
            "User-Agent": settings.user_agent,
        },
    }
    try:
        async with httpx.AsyncClient(**client_kwargs) as client:
            async with client.stream("GET", url) as response:
                latency_ms = int(round((time.perf_counter() - started) * 1000))
                status_code = response.status_code
                status = _status_from_http_response(status_code, target)
                return base | {
                    "status": status,
                    "http_status": status_code,
                    "latency_ms": latency_ms,
                    "target_url": str(response.url),
                }
    except Exception as exc:
        latency_ms = int(round((time.perf_counter() - started) * 1000))
        return base | {
            "status": "offline",
            "latency_ms": latency_ms,
            "error": f"{type(exc).__name__}: {exc}",
        }


def _status_from_http_response(status_code: int, target: dict[str, Any]) -> str:
    raw_online_statuses = target.get("online_http_statuses", [])
    configured_statuses = raw_online_statuses if isinstance(raw_online_statuses, list) else []
    online_statuses = {
        int(value)
        for value in configured_statuses
        if isinstance(value, int) or (isinstance(value, str) and value.isdigit())
    }
    if status_code in online_statuses:
        return "online"
    return "online" if 200 <= status_code < 500 else "offline"


def _aggregate_service(
    service: dict[str, Any], target_results: list[dict[str, Any]]
) -> dict[str, Any]:
    online = next((result for result in target_results if result["status"] == "online"), None)
    offline = next(
        (result for result in target_results if result["status"] == "offline"), None
    )
    chosen = online or offline or (target_results[0] if target_results else {})
    status = "online" if online else "offline" if offline else "unknown"

    return {
        "id": str(service.get("id") or ""),
        "category": str(service.get("category") or ""),
        "slug": str(service.get("slug") or ""),
        "name": str(service.get("name") or ""),
        "status": status,
        "checked_at": chosen.get("checked_at") or _iso(_utcnow()),
        "target_url": chosen.get("target_url") or chosen.get("url") or "",
        "http_status": chosen.get("http_status"),
        "latency_ms": chosen.get("latency_ms"),
        "error": chosen.get("error"),
        "targets": target_results,
    }


async def _check_service(
    service: dict[str, Any], semaphore: asyncio.Semaphore
) -> dict[str, Any]:
    raw_targets = service.get("targets")
    targets = raw_targets if isinstance(raw_targets, list) else []

    async def run_target(target: dict[str, Any]) -> dict[str, Any]:
        async with semaphore:
            return await _probe_url(target)

    target_results = await asyncio.gather(
        *(run_target(target) for target in targets if isinstance(target, dict))
    )
    return _aggregate_service(service, target_results)


async def _save_payload(payload: dict[str, Any]) -> None:
    global _memory_payload
    _memory_payload = payload
    if firestore_doc is None:
        return

    def save() -> None:
        firestore_doc.set(payload)

    await anyio.to_thread.run_sync(save)


async def _load_payload() -> dict[str, Any] | None:
    if _memory_payload:
        return _memory_payload
    if firestore_doc is None:
        return None

    def load() -> dict[str, Any] | None:
        snapshot = firestore_doc.get()
        return snapshot.to_dict() if snapshot.exists else None

    return await anyio.to_thread.run_sync(load)


def _is_payload_fresh(payload: dict[str, Any] | None) -> bool:
    if not payload:
        return False
    expires_at = str(payload.get("expires_at") or "")
    if not expires_at:
        return False
    try:
        expires = datetime.fromisoformat(expires_at.replace("Z", "+00:00"))
    except ValueError:
        return False
    return expires > _utcnow()


async def refresh_status() -> dict[str, Any]:
    async with _refresh_lock:
        targets = await _load_targets()
        semaphore = asyncio.Semaphore(settings.max_concurrency)
        services = await asyncio.gather(
            *(_check_service(service, semaphore) for service in targets)
        )
        generated_at = _utcnow()
        expires_at = generated_at + timedelta(seconds=settings.check_interval_seconds)
        payload = {
            "schema_version": 1,
            "generated_at": _iso(generated_at),
            "expires_at": _iso(expires_at),
            "ttl_seconds": settings.check_interval_seconds,
            "services": {service["id"]: service for service in services if service["id"]},
        }
        await _save_payload(payload)
        return payload


def _authorized(request: Request) -> bool:
    secret = settings.cron_secret
    if not secret or secret == "change-me":
        return True
    header_secret = request.headers.get("X-Cron-Secret", "")
    auth = request.headers.get("Authorization", "")
    bearer = auth.removeprefix("Bearer ").strip() if auth.startswith("Bearer ") else ""
    return header_secret == secret or bearer == secret


@app.get("/status")
async def liveness() -> dict[str, Any]:
    return {"ok": True, "service": "bitmixlist-site-status"}


@app.get("/targets")
async def targets() -> dict[str, Any]:
    return {"targets": await _load_targets()}


@app.get("/status.json")
async def status_json() -> JSONResponse:
    payload = await _load_payload()
    if not _is_payload_fresh(payload):
        payload = await refresh_status()
    return JSONResponse(
        payload,
        headers={
            "Cache-Control": "public, max-age=300",
            "Access-Control-Allow-Origin": "*",
        },
    )


@app.post("/tasks/check")
async def scheduled_check(request: Request) -> JSONResponse:
    if not _authorized(request):
        raise HTTPException(status_code=401, detail="Invalid cron secret")
    payload = await refresh_status()
    return JSONResponse(payload)
