#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/blog/publish.php';
require_once __DIR__ . '/../src/search-index.php';

$root = dirname(__DIR__);
$checkOnly = in_array('--check', $argv, true);
$skipSitemap = in_array('--skip-sitemap', $argv, true);
$skipIndex = in_array('--skip-search-index', $argv, true);
$includeDrafts = in_array('--drafts', $argv, true);

$config = blog_config();

if ($checkOnly) {
    $posts = array_values(array_filter(
        blog_load_all_posts($root, $config),
        static fn (array $p): bool => ($p['status'] ?? '') === 'published'
    ));
    $missing = [];
    foreach ($posts as $post) {
        foreach (['en', 'ru'] as $locale) {
            if (!blog_post_has_locale($post, $locale)) {
                continue;
            }
            $path = $root . '/' . blog_output_path_for_locale($post, $locale);
            $expected = blog_render_post_page($post, $locale, $config);
            if (!is_file($path)) {
                $missing[] = "Missing: {$path}";
                continue;
            }
            $actual = file_get_contents($path);
            if ($actual !== $expected) {
                $missing[] = "Stale: {$path}";
            }
        }
    }
    foreach (['en', 'ru'] as $locale) {
        $path = $root . '/' . blog_index_path($locale, $config);
        $indexPosts = array_values(array_filter(
            $posts,
            static fn (array $p): bool => blog_post_has_locale($p, $locale)
        ));
        $expected = blog_render_index_page($indexPosts, $locale, $config);
        if (!is_file($path) || file_get_contents($path) !== $expected) {
            $missing[] = is_file($path) ? "Stale index: {$path}" : "Missing index: {$path}";
        }
    }
    if ($missing !== []) {
        fwrite(STDERR, implode(PHP_EOL, $missing) . PHP_EOL);
        exit(1);
    }
    echo 'Blog pages are current (' . count($posts) . " published posts).\n";
    exit(0);
}

$result = blog_build($root, $config, $includeDrafts);
echo 'Wrote ' . count($result['written']) . ' files from ' . $result['posts'] . " post(s).\n";
foreach ($result['written'] as $path) {
    echo '  ' . substr($path, strlen($root) + 1) . "\n";
}

if (!$skipSitemap) {
    blog_update_sitemap($root, $config);
    echo "Updated sitemap.xml blog block.\n";
}

if (!$skipIndex && function_exists('bitmixlist_write_search_index')) {
    bitmixlist_write_search_index($root);
    echo "Updated site-search-index.json.\n";
}

echo "Public site (blog content): {$config['site_base_url']}/blog/\n";
echo "Admin editor host: {$config['admin_base_url']}\n";
