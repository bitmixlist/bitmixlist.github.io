<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

/**
 * Parse Markdown file with YAML-like frontmatter into a post array.
 *
 * Frontmatter keys: slug, status, published_at, updated_at, tags, author,
 * canonical_path, body_format (markdown|html), locales.{en|ru}.{title,description,body?}
 * Body after frontmatter is the primary locale body (en) unless locales include body.
 *
 * @return array{
 *   slug: string,
 *   status: string,
 *   published_at: string,
 *   updated_at: string,
 *   tags: list<string>,
 *   author: string,
 *   canonical_path: string,
 *   body_format: string,
 *   locales: array<string, array{title: string, description: string, body: string}>,
 *   source_path?: string
 * }
 */
function blog_parse_post_file(string $contents, string $sourcePath = ''): array
{
    $contents = preg_replace('/^\xEF\xBB\xBF/', '', $contents) ?? $contents;
    $front = [];
    $body = $contents;

    if (preg_match('/\A---\r?\n(.*?)\r?\n---\r?\n(.*)\z/s', $contents, $m) === 1) {
        $front = blog_parse_frontmatter($m[1]);
        $body = $m[2];
    }

    $slug = (string) ($front['slug'] ?? '');
    if ($slug === '' && $sourcePath !== '') {
        $slug = pathinfo($sourcePath, PATHINFO_FILENAME);
    }
    $slug = blog_normalize_slug($slug);
    if ($slug === '') {
        throw new InvalidArgumentException('Post is missing a valid slug' . ($sourcePath !== '' ? " ({$sourcePath})" : ''));
    }

    $status = strtolower((string) ($front['status'] ?? 'draft'));
    if (!in_array($status, ['draft', 'published'], true)) {
        $status = 'draft';
    }

    $bodyFormat = strtolower((string) ($front['body_format'] ?? 'markdown'));
    if (!in_array($bodyFormat, ['markdown', 'html'], true)) {
        $bodyFormat = 'markdown';
    }

    $tags = [];
    if (isset($front['tags']) && is_array($front['tags'])) {
        foreach ($front['tags'] as $tag) {
            $tag = trim((string) $tag);
            if ($tag !== '') {
                $tags[] = $tag;
            }
        }
    } elseif (isset($front['tags']) && is_string($front['tags'])) {
        foreach (explode(',', $front['tags']) as $tag) {
            $tag = trim($tag);
            if ($tag !== '') {
                $tags[] = $tag;
            }
        }
    }

    $canonicalPath = trim((string) ($front['canonical_path'] ?? ''));
    if ($canonicalPath === '') {
        $canonicalPath = 'blog/' . $slug . '.html';
    }
    $canonicalPath = ltrim(str_replace('\\', '/', $canonicalPath), '/');

    $localesIn = is_array($front['locales'] ?? null) ? $front['locales'] : [];
    $locales = [];
    foreach (['en', 'ru'] as $locale) {
        $loc = is_array($localesIn[$locale] ?? null) ? $localesIn[$locale] : [];
        $title = trim((string) ($loc['title'] ?? ($locale === 'en' ? ($front['title'] ?? '') : '')));
        $description = trim((string) ($loc['description'] ?? ($locale === 'en' ? ($front['description'] ?? '') : '')));
        $locBody = array_key_exists('body', $loc) ? (string) $loc['body'] : ($locale === 'en' ? $body : '');
        $locales[$locale] = [
            'title' => $title,
            'description' => $description,
            'body' => rtrim($locBody) . (rtrim($locBody) === '' ? '' : "\n"),
        ];
    }

    // If only EN body in file and RU has empty body, leave empty (not published for RU).
    if (trim($locales['en']['title']) === '' && trim($locales['en']['body']) !== '') {
        // Prefer first heading as title fallback later in render; keep empty title allowed in store.
    }

    $editors = [];
    if (isset($front['editors']) && is_array($front['editors'])) {
        foreach ($front['editors'] as $ed) {
            $ed = strtolower(trim((string) $ed));
            if ($ed !== '') {
                $editors[] = $ed;
            }
        }
    } elseif (isset($front['editors']) && is_string($front['editors'])) {
        foreach (preg_split('/[\s,]+/', $front['editors']) ?: [] as $ed) {
            $ed = strtolower(trim($ed));
            if ($ed !== '') {
                $editors[] = $ed;
            }
        }
    }
    $editors = array_values(array_unique($editors));
    $createdBy = strtolower(trim((string) ($front['created_by'] ?? '')));

    $post = [
        'slug' => $slug,
        'status' => $status,
        'published_at' => blog_normalize_datetime((string) ($front['published_at'] ?? '')),
        'updated_at' => blog_normalize_datetime((string) ($front['updated_at'] ?? ($front['published_at'] ?? ''))),
        'tags' => $tags,
        'author' => trim((string) ($front['author'] ?? blog_config()['default_author'])),
        'canonical_path' => $canonicalPath,
        'body_format' => $bodyFormat,
        'created_by' => $createdBy,
        'editors' => $editors,
        'locales' => $locales,
    ];

    if ($sourcePath !== '') {
        $post['source_path'] = $sourcePath;
    }

    return $post;
}

/**
 * Minimal YAML-ish frontmatter parser (scalars, lists, nested maps one level for locales).
 *
 * @return array<string, mixed>
 */
function blog_parse_frontmatter(string $yaml): array
{
    $lines = preg_split('/\r?\n/', $yaml) ?: [];
    $result = [];
    $i = 0;
    $n = count($lines);

    while ($i < $n) {
        $line = $lines[$i];
        if (trim($line) === '' || str_starts_with(ltrim($line), '#')) {
            $i++;
            continue;
        }

        if (preg_match('/^([A-Za-z0-9_]+):\s*(.*)$/', $line, $m) !== 1) {
            $i++;
            continue;
        }

        $key = $m[1];
        $rest = $m[2];

        if ($rest === '' || $rest === '|' || $rest === '>') {
            // Nested block or list
            $i++;
            if ($i < $n && preg_match('/^\s+-\s+/', $lines[$i]) === 1) {
                $list = [];
                while ($i < $n && preg_match('/^\s+-\s+(.*)$/', $lines[$i], $lm) === 1) {
                    $list[] = blog_unquote_scalar(trim($lm[1]));
                    $i++;
                }
                $result[$key] = $list;
                continue;
            }
            if ($i < $n && preg_match('/^\s+[A-Za-z0-9_]+:/', $lines[$i]) === 1) {
                $map = [];
                while ($i < $n && preg_match('/^(\s+)([A-Za-z0-9_]+):\s*(.*)$/', $lines[$i], $nm) === 1) {
                    $indent = strlen($nm[1]);
                    $nkey = $nm[2];
                    $nrest = $nm[3];
                    $i++;
                    if ($nrest === '' && $i < $n && preg_match('/^\s{' . ($indent + 1) . ',}[A-Za-z0-9_]+:/', $lines[$i]) === 1) {
                        $inner = [];
                        while ($i < $n && preg_match('/^(\s+)([A-Za-z0-9_]+):\s*(.*)$/', $lines[$i], $im) === 1 && strlen($im[1]) > $indent) {
                            $inner[$im[2]] = blog_unquote_scalar(trim($im[3]));
                            $i++;
                        }
                        $map[$nkey] = $inner;
                    } else {
                        $map[$nkey] = blog_unquote_scalar(trim($nrest));
                    }
                }
                $result[$key] = $map;
                continue;
            }
            $result[$key] = '';
            continue;
        }

        $result[$key] = blog_unquote_scalar(trim($rest));
        $i++;
    }

    return $result;
}

function blog_unquote_scalar(string $value): string
{
    if ($value === 'true' || $value === 'false' || $value === 'null') {
        return $value;
    }
    if (
        (str_starts_with($value, '"') && str_ends_with($value, '"'))
        || (str_starts_with($value, "'") && str_ends_with($value, "'"))
    ) {
        return stripcslashes(substr($value, 1, -1));
    }

    return $value;
}

function blog_normalize_slug(string $slug): string
{
    $slug = strtolower(trim($slug));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';
    $slug = trim($slug, '-');

    return $slug;
}

function blog_normalize_datetime(string $value): string
{
    $value = trim($value);
    if ($value === '') {
        return gmdate('Y-m-d\TH:i:s\Z');
    }
    $ts = strtotime($value);
    if ($ts === false) {
        return gmdate('Y-m-d\TH:i:s\Z');
    }

    return gmdate('Y-m-d\TH:i:s\Z', $ts);
}

/**
 * @return list<array>
 */
function blog_load_all_posts(string $root, ?array $config = null): array
{
    $config ??= blog_config();
    $dir = $root . '/' . $config['posts_dir'];
    if (!is_dir($dir)) {
        return [];
    }

    $posts = [];
    foreach (glob($dir . '/*.{md,markdown}', GLOB_BRACE) ?: [] as $path) {
        if (!is_file($path)) {
            continue;
        }
        $raw = file_get_contents($path);
        if ($raw === false) {
            continue;
        }
        try {
            $posts[] = blog_parse_post_file_with_trailer($raw, $path);
        } catch (Throwable $e) {
            throw new RuntimeException('Failed to parse post ' . $path . ': ' . $e->getMessage(), 0, $e);
        }
    }

    usort($posts, static function (array $a, array $b): int {
        return [$b['published_at'], $a['slug']] <=> [$a['published_at'], $b['slug']];
    });

    return $posts;
}

function blog_load_post(string $root, string $slug, ?array $config = null): ?array
{
    $slug = blog_normalize_slug($slug);
    foreach (blog_load_all_posts($root, $config) as $post) {
        if ($post['slug'] === $slug) {
            return $post;
        }
    }

    return null;
}

/**
 * Serialize a post array back to a markdown+frontmatter file.
 */
function blog_serialize_post(array $post): string
{
    $slug = blog_normalize_slug((string) ($post['slug'] ?? ''));
    if ($slug === '') {
        throw new InvalidArgumentException('Cannot serialize post without slug');
    }

    $status = (string) ($post['status'] ?? 'draft');
    $bodyFormat = (string) ($post['body_format'] ?? 'markdown');
    $published = blog_normalize_datetime((string) ($post['published_at'] ?? ''));
    $updated = blog_normalize_datetime((string) ($post['updated_at'] ?? $published));
    $author = (string) ($post['author'] ?? blog_config()['default_author']);
    $canonical = ltrim((string) ($post['canonical_path'] ?? ('blog/' . $slug . '.html')), '/');
    $tags = is_array($post['tags'] ?? null) ? $post['tags'] : [];
    $locales = is_array($post['locales'] ?? null) ? $post['locales'] : [];
    $createdBy = strtolower(trim((string) ($post['created_by'] ?? '')));
    $editors = [];
    if (is_array($post['editors'] ?? null)) {
        foreach ($post['editors'] as $ed) {
            $ed = strtolower(trim((string) $ed));
            if ($ed !== '') {
                $editors[] = $ed;
            }
        }
    }
    $editors = array_values(array_unique($editors));

    $enBody = (string) ($locales['en']['body'] ?? '');
    $lines = [
        '---',
        'slug: ' . $slug,
        'status: ' . $status,
        'published_at: ' . $published,
        'updated_at: ' . $updated,
        'author: ' . blog_yaml_quote($author),
        'canonical_path: ' . $canonical,
        'body_format: ' . $bodyFormat,
    ];
    if ($createdBy !== '') {
        $lines[] = 'created_by: ' . blog_yaml_quote($createdBy);
    }
    if ($editors !== []) {
        $lines[] = 'editors:';
        foreach ($editors as $ed) {
            $lines[] = '  - ' . blog_yaml_quote($ed);
        }
    }

    if ($tags !== []) {
        $lines[] = 'tags:';
        foreach ($tags as $tag) {
            $lines[] = '  - ' . blog_yaml_quote((string) $tag);
        }
    }

    $lines[] = 'locales:';
    foreach (['en', 'ru'] as $locale) {
        $loc = is_array($locales[$locale] ?? null) ? $locales[$locale] : [];
        $title = (string) ($loc['title'] ?? '');
        $description = (string) ($loc['description'] ?? '');
        $lines[] = '  ' . $locale . ':';
        $lines[] = '    title: ' . blog_yaml_quote($title);
        $lines[] = '    description: ' . blog_yaml_quote($description);
        if ($locale === 'ru' && trim((string) ($loc['body'] ?? '')) !== '') {
            // RU body embedded as a single-line escaped string when short; multiline via marker file convention:
            // We keep RU body after EN body separator in file trailer.
            $lines[] = '    body: ""';
        }
    }
    $lines[] = '---';
    $lines[] = rtrim($enBody);
    $lines[] = '';

    $ruBody = rtrim((string) ($locales['ru']['body'] ?? ''));
    if ($ruBody !== '') {
        $lines[] = '<!--blog:locale:ru-->';
        $lines[] = $ruBody;
        $lines[] = '';
    }

    return implode("\n", $lines);
}

/**
 * Parse post file including optional RU body trailer marker.
 */
function blog_parse_post_file_with_trailer(string $contents, string $sourcePath = ''): array
{
    $ruBody = '';
    if (preg_match('/\n<!--blog:locale:ru-->\r?\n(.*)\z/s', $contents, $tm) === 1) {
        $ruBody = $tm[1];
        $contents = substr($contents, 0, -strlen($tm[0]));
    }

    $post = blog_parse_post_file($contents, $sourcePath);
    if (trim($ruBody) !== '') {
        $post['locales']['ru']['body'] = rtrim($ruBody) . "\n";
    }

    return $post;
}

function blog_yaml_quote(string $value): string
{
    if ($value === '' || preg_match('/[:#{}\[\],&*?|>!%@`\'"\n\r]/', $value) === 1) {
        return '"' . addcslashes($value, "\\\"\n\r") . '"';
    }

    return $value;
}

function blog_post_path(string $root, string $slug, ?array $config = null): string
{
    $config ??= blog_config();
    $slug = blog_normalize_slug($slug);

    return $root . '/' . $config['posts_dir'] . '/' . $slug . '.md';
}

function blog_save_post(string $root, array $post, ?array $config = null): string
{
    $config ??= blog_config();
    $slug = blog_normalize_slug((string) ($post['slug'] ?? ''));
    if ($slug === '') {
        throw new InvalidArgumentException('Post slug required');
    }
    $post['slug'] = $slug;
    $path = blog_post_path($root, $slug, $config);
    $dir = dirname($path);
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        throw new RuntimeException("Unable to create {$dir}");
    }

    // Prefer full parse path on reload: serialize with trailer
    $serialized = blog_serialize_post($post);
    if (file_put_contents($path, $serialized) === false) {
        throw new RuntimeException("Unable to write {$path}");
    }

    return $path;
}


