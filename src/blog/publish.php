<?php

declare(strict_types=1);

require_once __DIR__ . '/store.php';
require_once __DIR__ . '/../templates/blog-page.php';

/**
 * Build all published blog pages into the site root.
 *
 * @return array{written: list<string>, posts: int, indexes: list<string>}
 */
function blog_build(string $root, ?array $config = null, bool $includeDrafts = false): array
{
    $config ??= blog_config();
    $posts = blog_load_all_posts($root, $config);
    $published = array_values(array_filter(
        $posts,
        static fn (array $p): bool => $includeDrafts || ($p['status'] ?? '') === 'published'
    ));

    $written = [];
    foreach ($published as $post) {
        foreach (['en', 'ru'] as $locale) {
            if (!blog_post_has_locale($post, $locale)) {
                continue;
            }
            // For drafts only include if flag set; still skip empty locales
            if (!$includeDrafts && ($post['status'] ?? '') !== 'published') {
                continue;
            }
            $path = $root . '/' . blog_output_path_for_locale($post, $locale);
            $html = blog_render_post_page($post, $locale, $config);
            blog_write_file($path, $html);
            $written[] = $path;
        }
    }

    $indexes = [];
    foreach (['en', 'ru'] as $locale) {
        $indexPosts = array_values(array_filter(
            $published,
            static fn (array $p): bool => ($p['status'] ?? '') === 'published' && blog_post_has_locale($p, $locale)
        ));
        $indexPath = $root . '/' . blog_index_path($locale, $config);
        $html = blog_render_index_page($indexPosts, $locale, $config);
        blog_write_file($indexPath, $html);
        $indexes[] = $indexPath;
        $written[] = $indexPath;
    }

    return [
        'written' => $written,
        'posts' => count($published),
        'indexes' => $indexes,
    ];
}

/**
 * Save post (create/update) and optionally publish (set status + rebuild).
 *
 * @return array{path: string, build?: array}
 */
function blog_publish_post(string $root, array $post, bool $rebuild = true, ?array $config = null): array
{
    $config ??= blog_config();
    $post['slug'] = blog_normalize_slug((string) ($post['slug'] ?? ''));
    if ($post['slug'] === '') {
        throw new InvalidArgumentException('slug is required');
    }
    if (($post['status'] ?? '') === 'published') {
        $post['updated_at'] = blog_normalize_datetime((string) ($post['updated_at'] ?? gmdate('c')));
        if (trim((string) ($post['published_at'] ?? '')) === '') {
            $post['published_at'] = $post['updated_at'];
        }
    }
    if (trim((string) ($post['canonical_path'] ?? '')) === '') {
        $post['canonical_path'] = 'blog/' . $post['slug'] . '.html';
    }

    $path = blog_save_post($root, $post, $config);
    $result = ['path' => $path];
    if ($rebuild) {
        $result['build'] = blog_build($root, $config);
    }

    return $result;
}

/**
 * Create or update from form-like fields and publish.
 *
 * @param array{
 *   slug?: string,
 *   status?: string,
 *   title_en?: string,
 *   title_ru?: string,
 *   description_en?: string,
 *   description_ru?: string,
 *   body_en?: string,
 *   body_ru?: string,
 *   body_format?: string,
 *   tags?: string|list<string>,
 *   published_at?: string,
 *   canonical_path?: string,
 *   author?: string
 * } $fields
 */
function blog_save_from_fields(string $root, array $fields, bool $rebuild = true, ?array $config = null): array
{
    $config ??= blog_config();
    $slug = blog_normalize_slug((string) ($fields['slug'] ?? ''));
    $existing = $slug !== '' ? blog_load_post($root, $slug, $config) : null;

    $tags = $fields['tags'] ?? ($existing['tags'] ?? []);
    if (is_string($tags)) {
        $tags = array_values(array_filter(array_map('trim', explode(',', $tags))));
    }

    $post = [
        'slug' => $slug !== '' ? $slug : blog_normalize_slug((string) ($fields['title_en'] ?? 'untitled')),
        'status' => (string) ($fields['status'] ?? ($existing['status'] ?? 'draft')),
        'published_at' => (string) ($fields['published_at'] ?? ($existing['published_at'] ?? gmdate('c'))),
        'updated_at' => gmdate('c'),
        'tags' => $tags,
        'author' => (string) ($fields['author'] ?? ($existing['author'] ?? $config['default_author'])),
        'canonical_path' => (string) ($fields['canonical_path'] ?? ($existing['canonical_path'] ?? '')),
        'body_format' => (string) ($fields['body_format'] ?? ($existing['body_format'] ?? 'markdown')),
        'locales' => [
            'en' => [
                'title' => (string) ($fields['title_en'] ?? ($existing['locales']['en']['title'] ?? '')),
                'description' => (string) ($fields['description_en'] ?? ($existing['locales']['en']['description'] ?? '')),
                'body' => (string) ($fields['body_en'] ?? ($existing['locales']['en']['body'] ?? '')),
            ],
            'ru' => [
                'title' => (string) ($fields['title_ru'] ?? ($existing['locales']['ru']['title'] ?? '')),
                'description' => (string) ($fields['description_ru'] ?? ($existing['locales']['ru']['description'] ?? '')),
                'body' => (string) ($fields['body_ru'] ?? ($existing['locales']['ru']['body'] ?? '')),
            ],
        ],
    ];

    return blog_publish_post($root, $post, $rebuild, $config);
}

function blog_write_file(string $path, string $contents): void
{
    $dir = dirname($path);
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        throw new RuntimeException("Unable to create {$dir}");
    }
    if (file_put_contents($path, $contents) === false) {
        throw new RuntimeException("Unable to write {$path}");
    }
}

/**
 * Collect sitemap URL entries for published posts + blog indexes.
 *
 * @return list<array{loc: string, lastmod: string}>
 */
function blog_sitemap_entries(string $root, ?array $config = null): array
{
    $config ??= blog_config();
    $entries = [];
    $posts = array_values(array_filter(
        blog_load_all_posts($root, $config),
        static fn (array $p): bool => ($p['status'] ?? '') === 'published'
    ));

    foreach (['en', 'ru'] as $locale) {
        $indexPath = blog_index_path($locale, $config);
        $isBlog = str_starts_with($indexPath, 'blog/') || str_starts_with($indexPath, 'ru/blog/');
        $base = $isBlog ? $config['blog_base_url'] : $config['site_base_url'];
        $entries[] = [
            'loc' => rtrim($base, '/') . '/' . $indexPath,
            'lastmod' => gmdate('Y-m-d\T00:00:00\Z'),
        ];
    }

    foreach ($posts as $post) {
        foreach (['en', 'ru'] as $locale) {
            if (!blog_post_has_locale($post, $locale)) {
                continue;
            }
            $entries[] = [
                'loc' => blog_canonical_url($post, $locale, $config),
                'lastmod' => gmdate('Y-m-d\T00:00:00\Z', strtotime((string) $post['updated_at']) ?: time()),
            ];
        }
    }

    return $entries;
}

/**
 * Merge blog URLs into sitemap.xml (replace previous blog-managed block if present).
 */
function blog_update_sitemap(string $root, ?array $config = null): void
{
    $config ??= blog_config();
    $path = $root . '/sitemap.xml';
    if (!is_file($path)) {
        return;
    }
    $sitemap = file_get_contents($path);
    if ($sitemap === false) {
        return;
    }

    // Remove previous blog marker block
    $sitemap = preg_replace(
        '~\s*<!-- bitmixlist-blog-start -->.*?<!-- bitmixlist-blog-end -->\s*~su',
        "\n",
        $sitemap
    ) ?? $sitemap;

    $block = "  <!-- bitmixlist-blog-start -->\n";
    foreach (blog_sitemap_entries($root, $config) as $entry) {
        $block .= "  <url>\n";
        $block .= '    <loc>' . htmlspecialchars($entry['loc'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</loc>\n";
        $block .= '    <lastmod>' . $entry['lastmod'] . "</lastmod>\n";
        $block .= "    <changefreq>weekly</changefreq>\n";
        $block .= "    <priority>0.65</priority>\n";
        $block .= "  </url>\n";
    }
    $block .= "  <!-- bitmixlist-blog-end -->\n";

    if (!str_contains($sitemap, '</urlset>')) {
        return;
    }
    $sitemap = str_replace('</urlset>', $block . '</urlset>', $sitemap);
    file_put_contents($path, $sitemap);
}
