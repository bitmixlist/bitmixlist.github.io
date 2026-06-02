# BitMixList Site Status Checker

FastAPI service for Cloud Run. It reads `site-status-targets.json`, checks each clearnet service target, stores the current result in Firestore, and exposes `/status.json` for the static BitMixList pages. Onion targets are intentionally not checked.

## API

| Method | Path | Description |
| --- | --- | --- |
| `GET` | `/status` | Liveness probe. |
| `GET` | `/targets` | Returns the target manifest loaded by the service. |
| `GET` | `/status.json` | Public status feed consumed by `wp-content/litespeed/js/site-status.js`. |
| `POST` | `/tasks/check` | Protected hourly refresh endpoint for Cloud Scheduler. |

## Deploy

Run `php tools/build-directory.php` from the repository root first. The generator writes both `site-status-targets.json` and `site-status-checker/site-status-targets.json`, so the Cloud Run image can carry the same clearnet-only target manifest without waiting for the static site to publish it.

Build from `site-status-checker/`. These defaults mirror the existing `amlcheck-bitmixlist` Cloud Run setup:

```bash
gcloud builds submit --tag gcr.io/golden-capsule-451306-j5/bitmixlist-site-status

gcloud run deploy bitmixlist-site-status \
  --image gcr.io/golden-capsule-451306-j5/bitmixlist-site-status \
  --region us-central1 \
  --platform managed \
  --memory 512Mi \
  --concurrency 8 \
  --allow-unauthenticated \
  --set-env-vars TARGETS_FILE=site-status-targets.json,FIRESTORE_PROJECT_ID=golden-capsule-451306-j5,CRON_SECRET=CHANGE_ME
```

Create the hourly scheduler:

```bash
gcloud scheduler jobs create http bitmixlist-site-status-hourly \
  --schedule="0 * * * *" \
  --uri="https://SERVICE_URL/tasks/check" \
  --http-method=POST \
  --headers="X-Cron-Secret=CHANGE_ME" \
  --location=us-central1
```

Then rebuild the static pages with the Cloud Run feed URL:

```bash
BITMIXLIST_STATUS_FEED_URL=https://SERVICE_URL/status.json php tools/build-directory.php
```

## Target manifest

Targets default to online for HTTP `2xx`, `3xx`, and `4xx` responses, and offline for `5xx` responses or network failures. A target can set `online_http_statuses` when a known protection page returns a `5xx` status while the service is reachable.

## Configuration

| Variable | Default | Description |
| --- | --- | --- |
| `TARGETS_URL` | unset | Optional public URL for `site-status-targets.json`. Leave unset to use the bundled manifest. |
| `TARGETS_FILE` | `site-status-targets.json` | Local manifest path when `TARGETS_URL` is unset. |
| `FIRESTORE_PROJECT_ID` | `golden-capsule-451306-j5` | Enables Firestore persistence for the current status document. |
| `FIRESTORE_COLLECTION` | `bitmixlist_site_status` | Collection for the cached result. |
| `FIRESTORE_DOCUMENT` | `current` | Document ID for the cached result. |
| `CRON_SECRET` | `change-me` | Required value for `X-Cron-Secret` on `/tasks/check`. |
| `CHECK_INTERVAL_SECONDS` | `3600` | Feed TTL and scheduler interval. |
| `CHECK_TIMEOUT_SECONDS` | `18` | HTTP timeout per target. |
| `MAX_CONCURRENCY` | `12` | Parallel probe limit. |
| `CORS_ORIGINS` | `*` | Comma-separated origins or `*`. |
