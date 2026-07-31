# BitMixList Blog (PHP, no WordPress)

File-backed editorial posts with a static HTML build and a small admin editor.

## Hosts

| Host | Role |
| --- | --- |
| **https://bitmixlist.org/blog/** | Public blog content (EN). RU: `/ru/blog/` |
| **https://bitmixlist.org/{slug}.html** | Migrated legacy articles (stable paths) |
| **https://blog.bitmixlist.org** | Admin editor only — does **not** serve public post pages |

## Layout

| Path | Role |
| --- | --- |
| `src/blog/posts/*.md` | Content store (frontmatter + body) |
| `src/blog/*.php` | Load, import, publish, markdown |
| `src/templates/blog-page.php` | Public HTML chrome |
| `tools/build-blog.php` | Build public pages + sitemap block |
| `tools/import-legacy-posts.php` | Import root article HTML into the store |
| `admin/blog/` | Authenticated editor (intended host: blog.bitmixlist.org) |
| `blog/` · `ru/blog/` | Generated public index + new posts (deploy with main site) |

## Build (local)

```bash
# From html/
php tools/build-blog.php
php tools/build-blog.php --check
```

Environment overrides:

```bash
export BITMIXLIST_SITE_BASE_URL=https://bitmixlist.org          # public content + canonicals
export BITMIXLIST_BLOG_ADMIN_BASE_URL=https://blog.bitmixlist.org  # editor host only
export BITMIXLIST_BLOG_ASSET_MODE=relative   # or absolute
php tools/build-blog.php
```

Public canonicals always use the **main site** origin, e.g.:

- `https://bitmixlist.org/blog/index.html`
- `https://bitmixlist.org/blog/welcome-to-the-blog.html`
- `https://bitmixlist.org/coinjoin.html` (legacy path)

## Import legacy articles

```bash
php tools/import-legacy-posts.php --dry-run
php tools/import-legacy-posts.php --only=coinjoin,roman-storm-case --rebuild
php tools/import-legacy-posts.php --rebuild   # full import + rebuild
```

Utility pages are never imported: `faq`, `terms-and-conditions`, `scam-lookup`, `letter-verify`, `changelog`, `index`, `404`, `aml-check`.

## Admin editor

Deploy or run `admin/blog/` on **blog.bitmixlist.org** (or locally for dev). Publishing writes into `src/blog/posts/` and rebuilds static HTML under the main site tree (`blog/`, root legacy paths, `ru/…`). Those files ship with **bitmixlist.org**, not the admin subdomain.

```bash
# Local admin (from html/)
php -S 127.0.0.1:8765 -t .
# Open http://127.0.0.1:8765/admin/blog/
# Password: bitmixlist-local
# Or: BITMIXLIST_BLOG_ADMIN_PASSWORD=...  or admin/blog/admin-password.hash
```

Publish calls the same `blog_save_from_fields` / `blog_build` path as the CLI.

## Tests

```bash
php tests/blog/run-tests.php
```
