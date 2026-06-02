from __future__ import annotations

import os
from functools import lru_cache

from pydantic import BaseModel, ConfigDict, Field


class Settings(BaseModel):
    model_config = ConfigDict(populate_by_name=True)

    targets_url: str | None = Field(None, alias="TARGETS_URL")
    targets_file: str = Field("site-status-targets.json", alias="TARGETS_FILE")
    firestore_project_id: str | None = Field(
        "golden-capsule-451306-j5", alias="FIRESTORE_PROJECT_ID"
    )
    firestore_collection: str = Field(
        "bitmixlist_site_status", alias="FIRESTORE_COLLECTION"
    )
    firestore_document: str = Field("current", alias="FIRESTORE_DOCUMENT")
    cron_secret: str = Field("change-me", alias="CRON_SECRET")
    check_interval_seconds: int = Field(
        3600, alias="CHECK_INTERVAL_SECONDS", ge=300, le=86400
    )
    check_timeout_seconds: float = Field(
        18.0, alias="CHECK_TIMEOUT_SECONDS", ge=3.0, le=120.0
    )
    max_concurrency: int = Field(12, alias="MAX_CONCURRENCY", ge=1, le=64)
    cors_origins: str = Field("*", alias="CORS_ORIGINS")
    user_agent: str = Field(
        "BitMixList status checker/1.0 (+https://bitmixlist.org/)",
        alias="USER_AGENT",
    )


def _settings_from_env() -> dict[str, object]:
    data: dict[str, object] = {}
    string_fields = [
        ("TARGETS_URL", "targets_url"),
        ("TARGETS_FILE", "targets_file"),
        ("FIRESTORE_PROJECT_ID", "firestore_project_id"),
        ("FIRESTORE_COLLECTION", "firestore_collection"),
        ("FIRESTORE_DOCUMENT", "firestore_document"),
        ("CRON_SECRET", "cron_secret"),
        ("SITE_STATUS_CRON_SECRET", "cron_secret"),
        ("CORS_ORIGINS", "cors_origins"),
        ("USER_AGENT", "user_agent"),
    ]
    int_fields = [
        ("CHECK_INTERVAL_SECONDS", "check_interval_seconds"),
        ("MAX_CONCURRENCY", "max_concurrency"),
    ]
    float_fields = [("CHECK_TIMEOUT_SECONDS", "check_timeout_seconds")]

    for env_name, field_name in string_fields:
        value = os.getenv(env_name)
        if value:
            data[field_name] = value
    for env_name, field_name in int_fields:
        value = os.getenv(env_name)
        if value:
            data[field_name] = int(value)
    for env_name, field_name in float_fields:
        value = os.getenv(env_name)
        if value:
            data[field_name] = float(value)

    return data


@lru_cache
def get_settings() -> Settings:
    return Settings(**_settings_from_env())
