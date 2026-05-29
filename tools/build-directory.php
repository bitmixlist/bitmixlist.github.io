#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/directory/extract.php';
require_once __DIR__ . '/../src/templates/directory-page.php';
require_once __DIR__ . '/../src/search-index.php';

const GENERATED_DIR_MARKER = '.bitmixlist-generated';

$root = dirname(__DIR__);
$checkOnly = in_array('--check', $argv, true);
$skipIndex = in_array('--skip-index', $argv, true);
$data = directory_extract_all($root);
$errors = directory_validate_data($data, $root, $checkOnly);

if ($errors !== []) {
    fwrite(STDERR, implode(PHP_EOL, $errors) . PHP_EOL);
    exit(1);
}

$pages = [];
foreach (array_keys($data['categories']) as $categorySlug) {
    foreach (['en', 'ru'] as $locale) {
        $path = $root . '/' . directory_section_output_path($categorySlug, $locale);
        $html = directory_render_section_page($categorySlug, $data, $locale);
        $pages[$path] = $html;
    }
}

foreach ($data['entries'] as $entry) {
    foreach (['en', 'ru'] as $locale) {
        $path = $root . '/' . $entry['output_paths'][$locale];
        $html = directory_render_page($entry, $data['categories'], $locale);
        $pages[$path] = $html;
    }
}

if ($checkOnly) {
    foreach ($pages as $path => $html) {
        if (!is_file($path)) {
            fwrite(STDERR, "Missing generated page: {$path}" . PHP_EOL);
            exit(1);
        }
        $existing = file_get_contents($path);
        if ($existing !== $html) {
            fwrite(STDERR, "Generated page is stale: {$path}" . PHP_EOL);
            exit(1);
        }
    }
    check_generated_local_links($root, array_keys($pages));
    check_index_links($root, $data);
    check_sitemap($root, $data);
    bitmixlist_check_search_index($root);
    echo 'Directory data and generated pages are current.' . PHP_EOL;
    exit(0);
}

cleanup_generated_dirs($root, array_keys($data['categories']));

foreach ($pages as $path => $html) {
    $dir = dirname($path);
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        throw new RuntimeException("Unable to create {$dir}");
    }
    file_put_contents($path, $html);
}
mark_generated_dirs(array_keys($pages));

if (!$skipIndex) {
    rewrite_index($root . '/index.html', 'en', $data);
    rewrite_index($root . '/ru/index.html', 'ru', $data);
}

write_sitemap($root, $data);
bitmixlist_write_search_index($root);

echo 'Generated ' . count($pages) . ' directory pages.' . PHP_EOL;

function rewrite_index(string $path, string $locale, array $data): void
{
    $html = file_get_contents($path);
    if ($html === false) {
        throw new RuntimeException("Unable to read {$path}");
    }

    $html = ensure_index_styles($html);
    $entries = $data['entries'];
    $serviceByName = [];
    $toolByName = [];
    foreach ($entries as $entry) {
        $name = $entry['content'][$locale]['name'];
        if ($entry['type'] === 'service') {
            $serviceByName[directory_key($name)] = $entry;
        } else {
            $toolByName[directory_key($name)] = $entry;
        }
    }

    $html = preg_replace_callback('~<div class="mixer-card">\s*.*?<div class="mixer-fee">.*?</div>\s*</div>~su', function (array $match) use ($locale, $serviceByName): string {
        $block = $match[0];
        if (!preg_match('~<a class="mixer-name"[^>]*>(.*?)</a>~su', $block, $nameMatch)) {
            return $block;
        }

        $name = trim(html_entity_decode(strip_tags($nameMatch[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        $entry = $serviceByName[directory_key($name)] ?? null;
        if (!$entry) {
            return $block;
        }

        $internal = $entry['index_paths'][$locale];
        $external = $entry['links']['clearnet'] ?? '';
        $visitLabel = $locale === 'ru' ? 'Открыть сайт' : 'Visit site';

        $block = preg_replace('~(<a class="mixer-logo-link" href=")[^"]+(")~u', '$1' . directory_attr($internal) . '$2', $block, 1) ?? $block;
        $block = preg_replace('~(<a class="mixer-name" href=")[^"]+(")~u', '$1' . directory_attr($internal) . '$2', $block, 1) ?? $block;

        if (str_contains($block, 'class="mixer-visit"')) {
            $block = preg_replace('~<a class="mixer-visit" href="[^"]*"[^>]*>.*?</a>~su', '<a class="mixer-visit" href="' . directory_attr($external) . '" rel="noopener noreferrer" target="_blank">' . directory_escape($visitLabel) . '</a>', $block, 1) ?? $block;
        } else {
            $block = preg_replace('~(<a class="mixer-name"[^>]*>.*?</a>)~su', '$1' . "\n" . '<a class="mixer-visit" href="' . directory_attr($external) . '" rel="noopener noreferrer" target="_blank">' . directory_escape($visitLabel) . '</a>', $block, 1) ?? $block;
        }

        return $block;
    }, $html) ?? $html;

    $html = preg_replace_callback('~<li class="tool"[^>]*>.*?</li>~su', function (array $match) use ($locale, $toolByName): string {
        $block = $match[0];
        if (!preg_match('~<a class="tool__name"[^>]*>(.*?)</a>~su', $block, $nameMatch)) {
            return $block;
        }

        $name = trim(html_entity_decode(strip_tags($nameMatch[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        $entry = $toolByName[directory_key($name)] ?? null;
        if (!$entry) {
            return $block;
        }

        $internal = $entry['index_paths'][$locale];
        $external = $entry['links']['clearnet'] ?? '';
        $visitLabel = $locale === 'ru' ? 'Открыть проект' : 'Visit project';
        $nameText = directory_escape($name);

        $block = preg_replace('~<a class="tool__name"[^>]*>.*?</a>~su', '<a class="tool__name" href="' . directory_attr($internal) . '">' . $nameText . '</a>', $block, 1) ?? $block;
        if (str_contains($block, 'class="tool-visit"')) {
            $block = preg_replace('~<a class="tool-visit" href="[^"]*"[^>]*>.*?</a>~su', '<a class="tool-visit" href="' . directory_attr($external) . '" rel="noreferrer" target="_blank">' . directory_escape($visitLabel) . '</a>', $block, 1) ?? $block;
        } else {
            $block = preg_replace('~(<a class="tool__name"[^>]*>.*?</a>)~su', '$1' . "\n" . '<a class="tool-visit" href="' . directory_attr($external) . '" rel="noreferrer" target="_blank">' . directory_escape($visitLabel) . '</a>', $block, 1) ?? $block;
        }

        return $block;
    }, $html) ?? $html;

    foreach ($entries as $entry) {
        if ($entry['type'] !== 'service') {
            continue;
        }
        $display = $entry['table_display'][$locale];
        $internal = $entry['index_paths'][$locale];
        $encodedDisplay = directory_escape($display);
        $linked = '<td><a class="directory-link" href="' . directory_attr($internal) . '">' . $encodedDisplay . '</a></td>';

        $html = preg_replace('~<td>\s*' . preg_quote($display, '~') . '\s*</td>~u', $linked, $html, 1) ?? $html;
        $html = preg_replace('~<td><a class="directory-link" href="[^"]+">' . preg_quote($encodedDisplay, '~') . '</a></td>~u', $linked, $html, 1) ?? $html;
    }

    file_put_contents($path, $html);
}

function ensure_index_styles(string $html): string
{
    if (!str_contains($html, '.mixer-visit')) {
        $needle = '          .mixer-name:hover, .mixer-name:focus { text-decoration: underline; }' . "\n";
        $insert = $needle
            . '          .mixer-visit, .tool-visit { margin-top: 6px; padding: 4px 8px; border: 1px solid #7a61f6; border-radius: 6px; color: #e8ddff; background: #1a1234; font-size: 0.82rem; line-height: 1.2; text-decoration: none; }' . "\n"
            . '          .mixer-visit:hover, .mixer-visit:focus, .tool-visit:hover, .tool-visit:focus { background: #27184d; color: #fff; text-decoration: none; }' . "\n"
            . '          .directory-link { font-weight: 700; color: #f6f2ff; }' . "\n";
        $html = str_replace($needle, $insert, $html);
    }

    if (!str_contains($html, '.homepage-directory')) {
        $needle = '          .directory-link { font-weight: 700; color: #f6f2ff; }' . "\n";
        $insert = $needle
            . '          .homepage-directory { max-width: 1080px; margin: 0 auto; padding: 28px 20px 44px; }' . "\n"
            . '          .homepage-directory .directory-section { margin-top: 28px; }' . "\n"
            . '          .homepage-directory .directory-section h2 { margin: 0 0 12px; font-size: 1.35rem; letter-spacing: 0; }' . "\n"
            . '          .homepage-directory .directory-section h3 { margin: 22px 0 10px; font-size: 1.08rem; letter-spacing: 0; }' . "\n"
            . '          .directory-section-heading { display: flex; align-items: baseline; justify-content: space-between; gap: 12px; }' . "\n"
            . '          .directory-section-link { flex: 0 0 auto; color: #d8ccff; font-size: 0.92rem; text-decoration: underline; text-underline-offset: 0.18em; }' . "\n"
            . '          .homepage-directory .directory-list { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 12px; margin: 0; padding: 0; list-style: none; justify-content: stretch; }' . "\n"
            . '          .homepage-directory .directory-list-card { display: grid; grid-template-columns: 64px minmax(0, 1fr); gap: 12px; align-items: start; min-width: 0; width: auto; padding: 14px; border: 1px solid #3a2e55; border-radius: 8px; background: #181222; box-shadow: none; }' . "\n"
            . '          .homepage-directory .directory-list-card .directory-logo { width: 64px; height: 64px; border-radius: 8px; object-fit: contain; }' . "\n"
            . '          .homepage-directory .directory-list-card .directory-logo--text { font-size: 1.15rem; }' . "\n"
            . '          .homepage-directory .directory-list-title { margin: 0 0 4px; font-size: 1.05rem; line-height: 1.25; font-weight: 700; }' . "\n"
            . '          .homepage-directory .directory-list-title a { color: #f6f2ff; text-decoration: none; }' . "\n"
            . '          .homepage-directory .directory-list-title a:hover, .homepage-directory .directory-list-title a:focus { text-decoration: underline; }' . "\n"
            . '          .homepage-directory .directory-list-summary { margin: 0; color: #d8d0e8; font-size: 0.92rem; line-height: 1.45; }' . "\n"
            . '          .homepage-directory .directory-list-actions { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }' . "\n"
            . '          .homepage-directory .directory-button { display: inline-flex; align-items: center; justify-content: center; min-height: 34px; margin-top: 0; padding: 0 10px; border: 1px solid #7a61f6; border-radius: 7px; background: #1a1234; color: #f2ecff; text-decoration: none; font-size: 0.9rem; font-weight: 650; line-height: 1.2; }' . "\n"
            . '          .homepage-directory .directory-button:hover, .homepage-directory .directory-button:focus { background: #27184d; color: #fff; text-decoration: none; }' . "\n"
            . '          .homepage-directory .directory-table-wrap { margin: 0; overflow-x: auto; }' . "\n"
            . '          .homepage-directory .directory-facts { width: 100%; border-collapse: collapse; table-layout: fixed; }' . "\n"
            . '          .homepage-directory .homepage-comparison-table { table-layout: auto; min-width: 1040px; }' . "\n"
            . '          .homepage-directory-section[data-category="mixers"] .homepage-comparison-table { min-width: 1380px; }' . "\n"
            . '          .homepage-directory-section[data-category="coordinators"] .homepage-comparison-table { min-width: 960px; }' . "\n"
            . '          .homepage-directory .directory-facts th, .homepage-directory .directory-facts td { vertical-align: top; padding: 12px; overflow-wrap: anywhere; word-break: normal; }' . "\n"
            . '          .homepage-directory .directory-facts th { color: #f6f2ff; text-align: left; background: #282238; }' . "\n"
            . '          .homepage-directory .directory-facts td { background: #1c1728; }' . "\n"
            . '          .homepage-directory .tool-registry { max-width: none; margin: 28px 0 0; padding: 0; }' . "\n"
            . '          .homepage-directory .tool { border-radius: 8px; }' . "\n"
            . '          .homepage-notes { border-top: 1px solid #3a2e55; padding-top: 24px; }' . "\n"
            . '          @media (max-width: 700px) { .homepage-directory { padding: 22px 14px 36px; } .homepage-directory .directory-list { grid-template-columns: 1fr; } .homepage-directory .directory-section-heading { align-items: flex-start; flex-direction: column; gap: 2px; } .homepage-directory .homepage-comparison-table { min-width: 0; } .homepage-directory .directory-facts, .homepage-directory .directory-facts tbody, .homepage-directory .directory-facts tr, .homepage-directory .directory-facts th, .homepage-directory .directory-facts td { display: block; width: 100%; box-sizing: border-box; } }' . "\n";
        $html = str_replace($needle, $insert, $html);
    }

    if (!str_contains($html, '.homepage-directory .homepage-comparison-table')) {
        $needle = '          .homepage-directory .directory-facts { width: 100%; border-collapse: collapse; table-layout: fixed; }' . "\n";
        $insert = $needle
            . '          .homepage-directory .homepage-comparison-table { table-layout: auto; min-width: 1040px; }' . "\n"
            . '          .homepage-directory-section[data-category="mixers"] .homepage-comparison-table { min-width: 1380px; }' . "\n"
            . '          .homepage-directory-section[data-category="coordinators"] .homepage-comparison-table { min-width: 960px; }' . "\n";
        $html = str_replace($needle, $insert, $html);

        $needle = '          @media (max-width: 700px) { .homepage-directory { padding: 22px 14px 36px; } .homepage-directory .directory-list { grid-template-columns: 1fr; } .homepage-directory .directory-section-heading { align-items: flex-start; flex-direction: column; gap: 2px; } .homepage-directory .directory-facts, .homepage-directory .directory-facts tbody, .homepage-directory .directory-facts tr, .homepage-directory .directory-facts th, .homepage-directory .directory-facts td { display: block; width: 100%; box-sizing: border-box; } }' . "\n";
        $insert = '          @media (max-width: 700px) { .homepage-directory { padding: 22px 14px 36px; } .homepage-directory .directory-list { grid-template-columns: 1fr; } .homepage-directory .directory-section-heading { align-items: flex-start; flex-direction: column; gap: 2px; } .homepage-directory .homepage-comparison-table { min-width: 0; } .homepage-directory .directory-facts, .homepage-directory .directory-facts tbody, .homepage-directory .directory-facts tr, .homepage-directory .directory-facts th, .homepage-directory .directory-facts td { display: block; width: 100%; box-sizing: border-box; } }' . "\n";
        $html = str_replace($needle, $insert, $html);
    }

    return $html;
}

function write_sitemap(string $root, array $data): void
{
    $path = $root . '/sitemap.xml';
    $sitemap = file_get_contents($path);
    if ($sitemap === false) {
        throw new RuntimeException('Unable to read sitemap.xml');
    }

    $categoryPattern = implode('|', array_map(static fn (string $slug): string => preg_quote($slug, '~'), array_keys($data['categories'])));
    $sitemap = preg_replace('~\s*<url>\s*<loc>https://bitmixlist\.org/(?:ru/)?directory/.*?</url>~su', '', $sitemap) ?? $sitemap;
    $sitemap = preg_replace('~\s*<url>\s*<loc>https://bitmixlist\.org/(?:ru/)?(?:' . $categoryPattern . ')/.*?</url>~su', '', $sitemap) ?? $sitemap;

    $lastmod = gmdate('Y-m-d\T00:00:00\Z');
    $block = '';
    foreach (array_keys($data['categories']) as $categorySlug) {
        foreach (['en', 'ru'] as $locale) {
            $block .= "  <url>\n";
            $block .= '    <loc>https://bitmixlist.org/' . directory_escape(directory_section_public_url($categorySlug, $locale)) . "</loc>\n";
            $block .= '    <lastmod>' . $lastmod . "</lastmod>\n";
            $block .= "    <changefreq>weekly</changefreq>\n";
            $block .= "    <priority>0.75</priority>\n";
            $block .= "  </url>\n";
        }
    }

    foreach ($data['entries'] as $entry) {
        foreach (['en', 'ru'] as $locale) {
            $block .= "  <url>\n";
            $block .= '    <loc>https://bitmixlist.org/' . directory_escape($entry['output_paths'][$locale]) . "</loc>\n";
            $block .= '    <lastmod>' . $lastmod . "</lastmod>\n";
            $block .= "    <changefreq>weekly</changefreq>\n";
            $block .= "    <priority>0.7</priority>\n";
            $block .= "  </url>\n";
        }
    }

    $sitemap = str_replace("\n</urlset>", "\n" . rtrim($block) . "\n</urlset>", $sitemap);
    file_put_contents($path, $sitemap);
}

function cleanup_generated_dirs(string $root, array $categorySlugs): void
{
    foreach ([
        $root . '/directory',
        $root . '/ru/directory',
    ] as $legacyPath) {
        delete_generated_tree($legacyPath);
    }

    foreach ($categorySlugs as $slug) {
        delete_marked_generated_tree($root . '/' . $slug);
        delete_marked_generated_tree($root . '/ru/' . $slug);
    }
}

function delete_marked_generated_tree(string $path): void
{
    if (!is_file($path . '/' . GENERATED_DIR_MARKER)) {
        return;
    }

    delete_generated_tree($path);
}

function delete_generated_tree(string $path): void
{
    if (!is_dir($path)) {
        return;
    }

    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($items as $item) {
        if ($item->isDir()) {
            rmdir($item->getPathname());
        } else {
            unlink($item->getPathname());
        }
    }

    rmdir($path);
}

function mark_generated_dirs(array $paths): void
{
    $dirs = [];
    foreach ($paths as $path) {
        $dirs[dirname($path)] = true;
    }

    foreach (array_keys($dirs) as $dir) {
        file_put_contents($dir . '/' . GENERATED_DIR_MARKER, "Generated by tools/build-directory.php. Do not edit files in this directory manually.\n");
    }
}

function check_generated_local_links(string $root, array $paths): void
{
    foreach ($paths as $path) {
        $html = file_get_contents($path);
        if ($html === false) {
            throw new RuntimeException("Unable to read {$path}");
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_NOWARNING | LIBXML_NOERROR);
        libxml_clear_errors();
        $xpath = new DOMXPath($dom);

        foreach ($xpath->query('//@href | //@src | //@srcset') ?: [] as $attr) {
            $rawValues = $attr->nodeName === 'srcset' ? preg_split('/\s*,\s*/', $attr->nodeValue) : [$attr->nodeValue];
            foreach ($rawValues ?: [] as $rawValue) {
                $parts = preg_split('/\s+/', trim($rawValue));
                $value = $parts[0] ?? '';
                if ($value === '' || should_skip_local_link($value)) {
                    continue;
                }

                $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $target = normalize_local_path(dirname($path) . '/' . preg_replace('/[?#].*$/', '', $value));
                if (!is_file($target) && !(is_dir($target) && is_file($target . '/index.html'))) {
                    fwrite(STDERR, "Broken local link in {$path}: {$value}" . PHP_EOL);
                    exit(1);
                }
            }
        }
    }
}

function should_skip_local_link(string $value): bool
{
    return str_starts_with($value, '#')
        || preg_match('~^(?:https?:|mailto:|bitcoin:|monero:|tox:|xmpp:|data:|javascript:)~i', $value) === 1;
}

function normalize_local_path(string $path): string
{
    $isAbsolute = str_starts_with($path, '/');
    $parts = [];
    foreach (explode('/', $path) as $part) {
        if ($part === '' || $part === '.') {
            continue;
        }
        if ($part === '..') {
            array_pop($parts);
            continue;
        }
        $parts[] = $part;
    }

    return ($isAbsolute ? '/' : '') . implode('/', $parts);
}

function check_index_links(string $root, array $data): void
{
    foreach (['en' => '/index.html', 'ru' => '/ru/index.html'] as $locale => $path) {
        $html = file_get_contents($root . $path);
        if ($html === false) {
            throw new RuntimeException("Unable to read {$path}");
        }
        foreach ($data['entries'] as $entry) {
            $expected = 'href="' . $entry['index_paths'][$locale] . '"';
            if (!str_contains($html, $expected)) {
                fwrite(STDERR, "Missing {$locale} index link for {$entry['category']}/{$entry['slug']}" . PHP_EOL);
                exit(1);
            }
        }
        foreach ([
            'class="mixer-logo-link" href="https?://',
            'class="mixer-name" href="https?://',
            'class="tool__name" href="https?://',
        ] as $pattern) {
            if (preg_match('~' . $pattern . '~', $html)) {
                fwrite(STDERR, "External directory card/name link remains in {$locale} index: {$pattern}" . PHP_EOL);
                exit(1);
            }
        }
    }
}

function check_sitemap(string $root, array $data): void
{
    $sitemap = file_get_contents($root . '/sitemap.xml');
    if ($sitemap === false) {
        throw new RuntimeException('Unable to read sitemap.xml');
    }

    foreach ($data['entries'] as $entry) {
        foreach (['en', 'ru'] as $locale) {
            $url = 'https://bitmixlist.org/' . $entry['output_paths'][$locale];
            if (!str_contains($sitemap, $url)) {
                fwrite(STDERR, "Missing sitemap URL: {$url}" . PHP_EOL);
                exit(1);
            }
        }
    }

    foreach (array_keys($data['categories']) as $categorySlug) {
        foreach (['en', 'ru'] as $locale) {
            $url = 'https://bitmixlist.org/' . directory_section_public_url($categorySlug, $locale);
            if (!str_contains($sitemap, $url)) {
                fwrite(STDERR, "Missing sitemap URL: {$url}" . PHP_EOL);
                exit(1);
            }
        }
    }
}

function directory_attr(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
}
