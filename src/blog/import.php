<?php

declare(strict_types=1);

require_once __DIR__ . '/store.php';
require_once __DIR__ . '/markdown.php';

/**
 * Discover candidate legacy article HTML files under site root (and ru/).
 *
 * @return list<array{slug: string, en_path: ?string, ru_path: ?string}>
 */
function blog_discover_legacy_articles(string $root): array
{
    // Legacy root HTML pages are hardwired site content, not blog posts.
    // Do not auto-discover them for import — that strips custom CSS/JS/timelines.
    // New editorial content belongs under blog/ via the blog admin UI.
    unset($root);

    return [];
}

/**
 * Extract title, description, dates, and .page-content body from a legacy HTML page.
 *
 * @return array{title: string, description: string, published_at: string, updated_at: string, body: string}
 */
function blog_extract_legacy_html(string $html): array
{
    $title = '';
    if (preg_match('/<h1[^>]*class="[^"]*entry-title[^"]*"[^>]*>(.*?)<\/h1>/is', $html, $m) === 1) {
        $title = blog_decode_entities(trim(strip_tags($m[1])));
    } elseif (preg_match('/<title>(.*?)<\/title>/is', $html, $m) === 1) {
        $title = blog_decode_entities(trim(preg_replace('/\s+[–-|].*$/u', '', strip_tags($m[1])) ?? strip_tags($m[1])));
    }

    $description = '';
    if (preg_match('/<meta[^>]+name=["\']description["\'][^>]+content=["\']([^"\']*)["\']/i', $html, $m) === 1) {
        $description = blog_decode_entities($m[1]);
    } elseif (preg_match('/<meta[^>]+content=["\']([^"\']*)["\'][^>]+name=["\']description["\']/i', $html, $m) === 1) {
        $description = blog_decode_entities($m[1]);
    }

    $published = '';
    if (preg_match('/property=["\']article:published_time["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $m) === 1) {
        $published = $m[1];
    } elseif (preg_match('/content=["\']([^"\']+)["\'][^>]+property=["\']article:published_time["\']/i', $html, $m) === 1) {
        $published = $m[1];
    }

    $updated = '';
    if (preg_match('/property=["\']article:modified_time["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $m) === 1) {
        $updated = $m[1];
    } elseif (preg_match('/content=["\']([^"\']+)["\'][^>]+property=["\']article:modified_time["\']/i', $html, $m) === 1) {
        $updated = $m[1];
    }

    $body = blog_extract_page_content($html);

    return [
        'title' => $title,
        'description' => $description,
        'published_at' => blog_normalize_datetime($published !== '' ? $published : '2023-12-05T00:00:00Z'),
        'updated_at' => blog_normalize_datetime($updated !== '' ? $updated : ($published !== '' ? $published : '2023-12-05T00:00:00Z')),
        'body' => $body,
    ];
}

/**
 * Pull inner HTML of the first .page-content container; strip author-card.
 */
function blog_extract_page_content(string $html): string
{
    if (preg_match('/<div[^>]*class="[^"]*page-content[^"]*"[^>]*>(.*)/is', $html, $m) !== 1) {
        // Try article.page-content
        if (preg_match('/<article[^>]*class="[^"]*page-content[^"]*"[^>]*>(.*)/is', $html, $m) !== 1) {
            return '';
        }
    }

    $rest = $m[1];
    // Find matching close for the outer div/article by scanning for author-card or main close
    // Prefer cut at author-card section
    if (preg_match('/^(.*)<section[^>]*class="[^"]*author-card[^"]*"/is', $rest, $cut) === 1) {
        $rest = $cut[1];
    } elseif (preg_match('/^(.*)<\/div>\s*<\/main>/is', $rest, $cut) === 1) {
        $rest = $cut[1];
    } elseif (preg_match('/^(.*)<\/article>/is', $rest, $cut) === 1) {
        $rest = $cut[1];
    }

    $body = trim($rest);
    // Remove nested closing leftovers
    $body = preg_replace('/<\/div>\s*$/i', '', $body) ?? $body;
    $body = blog_sanitize_html_fragment($body);

    return trim($body) . "\n";
}

function blog_decode_entities(string $value): string
{
    return html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Build a post array from discovered legacy paths.
 */
function blog_legacy_to_post(array $discovered): array
{
    $slug = (string) $discovered['slug'];
    $locales = [
        'en' => ['title' => '', 'description' => '', 'body' => ''],
        'ru' => ['title' => '', 'description' => '', 'body' => ''],
    ];
    $published = '';
    $updated = '';

    if (!empty($discovered['en_path']) && is_file($discovered['en_path'])) {
        $raw = file_get_contents($discovered['en_path']);
        if ($raw !== false) {
            $ex = blog_extract_legacy_html($raw);
            $locales['en'] = [
                'title' => $ex['title'],
                'description' => $ex['description'],
                'body' => $ex['body'],
            ];
            $published = $ex['published_at'];
            $updated = $ex['updated_at'];
        }
    }

    if (!empty($discovered['ru_path']) && is_file($discovered['ru_path'])) {
        $raw = file_get_contents($discovered['ru_path']);
        if ($raw !== false) {
            $ex = blog_extract_legacy_html($raw);
            $locales['ru'] = [
                'title' => $ex['title'],
                'description' => $ex['description'],
                'body' => $ex['body'],
            ];
            if ($published === '') {
                $published = $ex['published_at'];
            }
            if ($updated === '') {
                $updated = $ex['updated_at'];
            }
        }
    }

    return [
        'slug' => $slug,
        'status' => 'published',
        'published_at' => $published !== '' ? $published : gmdate('c'),
        'updated_at' => $updated !== '' ? $updated : gmdate('c'),
        'tags' => [],
        'author' => blog_config()['default_author'],
        'canonical_path' => $slug . '.html',
        'body_format' => 'html',
        'locales' => $locales,
    ];
}

/**
 * Import legacy articles into the content store.
 *
 * @return array{imported: list<string>, skipped: list<string>, dry_run: bool}
 */
function blog_import_legacy(string $root, bool $dryRun = false, ?array $onlySlugs = null, ?array $config = null): array
{
    $config ??= blog_config();
    $discovered = blog_discover_legacy_articles($root);
    $imported = [];
    $skipped = [];

    foreach ($discovered as $item) {
        $slug = $item['slug'];
        if ($onlySlugs !== null && !in_array($slug, $onlySlugs, true)) {
            continue;
        }
        if (in_array($slug, blog_utility_slugs(), true)) {
            $skipped[] = $slug . ' (utility)';
            continue;
        }
        if (blog_is_hardwired_root_slug($slug)) {
            $skipped[] = $slug . ' (hardwired root page)';
            continue;
        }

        $post = blog_legacy_to_post($item);
        $hasContent = blog_post_has_locale($post, 'en') || blog_post_has_locale($post, 'ru');
        if (!$hasContent) {
            $skipped[] = $slug . ' (empty body)';
            continue;
        }

        if (!$dryRun) {
            blog_save_post($root, $post, $config);
        }
        $imported[] = $slug;
    }

    return [
        'imported' => $imported,
        'skipped' => $skipped,
        'dry_run' => $dryRun,
    ];
}

function blog_is_utility_slug(string $slug): bool
{
    return in_array(blog_normalize_slug($slug), blog_utility_slugs(), true);
}
