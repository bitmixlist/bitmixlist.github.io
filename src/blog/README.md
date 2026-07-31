# BitMixList Blog (PHP, no WordPress)

File-backed editorial posts with a static HTML build and a small local admin editor.

## Layout

| Path | Role |
| --- | --- |
| `src/blog/posts/*.md` | Content store (frontmatter + body) |
| `src/blog/*.php` | Load, import, publish, markdown |
| `src/templates/blog-page.php` | Public HTML chrome |
| `tools/build-blog.php` | Build public pages + sitemap block |
| `tools/import-legacy-posts.php` | Import root article HTML into the store |
| `admin/blog/` | Local authenticated editor |
| `blog/` · `ru/blog/` | Generated index + new posts |
| Legacy `*.html` | Generated in place when `canonical_path` is a root file |

## Build (local)

```bash
# From html/
php tools/build-blog.php
php tools/build-blog.php --check
```

Environment overrides:

```bash
export BITMIXLIST_BLOG_BASE_URL=https://blog.bitmixlist.local
export BITMIXLIST_SITE_BASE_URL=https://bitmixlist.org
export BITMIXLIST_BLOG_ASSET_MODE=relative   # or absolute for subdomain asset hosts
php tools/build-blog.php
```

## Import legacy articles

```bash
php tools/import-legacy-posts.php --dry-run
php tools/import-legacy-posts.php --only=coinjoin,roman-storm-case --rebuild
php tools/import-legacy-posts.php --rebuild   # full import + rebuild
```

Utility pages are never imported: `faq`, `terms-and-conditions`, `scam-lookup`, `letter-verify`, `changelog`, `index`, `404`, `aml-check`.

## Admin editor (local only)

```bash
# From html/
php -S 127.0.0.1:8765 -t .
# Open http://127.0.0.1:8765/admin/blog/
# Password: bitmixlist-local
# Or: BITMIXLIST_BLOG_ADMIN_PASSWORD=...  or admin/blog/admin-password.hash
```

Publish calls the same `blog_save_from_fields` / `blog_build` path as the CLI.

## Subdomain readiness (no deploy)

Canonicals for paths under `blog/` use `BITMIXLIST_BLOG_BASE_URL` (default `https://blog.bitmixlist.local`).
Legacy root articles keep `BITMIXLIST_SITE_BASE_URL` so `https://bitmixlist.org/coinjoin.html` stays stable.

### Local hosts fake

```text
127.0.0.1 blog.bitmixlist.local
```

```bash
BITMIXLIST_BLOG_BASE_URL=https://blog.bitmixlist.local php tools/build-blog.php
php -S blog.bitmixlist.local:8765 -t .
# curl -H 'Host: blog.bitmixlist.local' http://127.0.0.1:8765/blog/index.html
```

### Optional Servers.Guru `/etc/hosts` only (no site deploy)

SSH to Servers.Guru and add a hosts line for local Host-header tests if needed. Do **not** rsync or publish the blog tree as part of this workflow.

```bash
# Example only — hosts file mutation, not a deploy
# 127.0.0.1 blog.bitmixlist.local
```

## Tests

```bash
php tests/blog/run-tests.php
```
