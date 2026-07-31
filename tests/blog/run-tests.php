#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * In-repo tests for the blog pipeline. Drives real shipped functions.
 * Exit 0 on success, 1 on failure.
 */

$root = dirname(__DIR__, 2);
require_once $root . '/src/blog/store.php';
require_once $root . '/src/blog/markdown.php';
require_once $root . '/src/blog/import.php';
require_once $root . '/src/blog/publish.php';

$failures = 0;
$passed = 0;

function assert_true(bool $cond, string $msg): void
{
    global $failures, $passed;
    if ($cond) {
        echo "PASS  {$msg}\n";
        $passed++;
    } else {
        echo "FAIL  {$msg}\n";
        $failures++;
    }
}

function assert_contains(string $haystack, string $needle, string $msg): void
{
    assert_true(str_contains($haystack, $needle), $msg . ' (contains ' . json_encode($needle) . ')');
}

// --- frontmatter / store ---
$sampleMd = <<<'MD'
---
slug: test-post-alpha
status: published
published_at: 2026-01-15T00:00:00Z
updated_at: 2026-01-16T00:00:00Z
author: NotATether
canonical_path: blog/test-post-alpha.html
body_format: markdown
tags:
  - privacy
  - test
locales:
  en:
    title: "Alpha Title"
    description: "Alpha desc"
  ru:
    title: "Альфа"
    description: "Описание"
---
Hello **world** and a [link](https://example.com).

## Section Two

More text.
<!--blog:locale:ru-->
Привет **мир**.
MD;

$parsed = blog_parse_post_file_with_trailer($sampleMd);
assert_true($parsed['slug'] === 'test-post-alpha', 'parse slug');
assert_true($parsed['status'] === 'published', 'parse status');
assert_true($parsed['locales']['en']['title'] === 'Alpha Title', 'parse EN title');
assert_true(str_contains($parsed['locales']['en']['body'], 'Hello **world**'), 'parse EN body');
assert_true(str_contains($parsed['locales']['ru']['body'], 'Привет'), 'parse RU body trailer');
assert_true($parsed['tags'] === ['privacy', 'test'], 'parse tags list');

// --- markdown render ---
$mdHtml = blog_markdown_to_html("Hello **world** and a [link](https://example.com).\n\n## Section Two\n\nMore text.");
assert_contains($mdHtml, '<strong>world</strong>', 'markdown bold');
assert_contains($mdHtml, 'href="https://example.com"', 'markdown link');
assert_contains($mdHtml, 'id="section-two"', 'markdown heading id');
assert_contains($mdHtml, '<p>More text.</p>', 'markdown paragraph');

// --- sanitize html ---
$dirty = '<p>ok</p><script>alert(1)</script><p onclick="x">y</p>';
$clean = blog_sanitize_html_fragment($dirty);
assert_true(!str_contains($clean, '<script'), 'sanitize strips script');
assert_true(!str_contains($clean, 'onclick'), 'sanitize strips onclick');
assert_contains($clean, '<p>ok</p>', 'sanitize keeps paragraph');

// --- utility exclusion ---
assert_true(blog_is_utility_slug('faq'), 'faq is utility');
assert_true(blog_is_utility_slug('terms-and-conditions'), 'terms is utility');
assert_true(!blog_is_utility_slug('coinjoin'), 'coinjoin is not utility');

// --- extract legacy from real coinjoin.html ---
$coinjoinPath = $root . '/coinjoin.html';
assert_true(is_file($coinjoinPath), 'coinjoin.html exists for extract test');
$coinjoinHtml = file_get_contents($coinjoinPath);
assert_true($coinjoinHtml !== false, 'read coinjoin.html');
$extracted = blog_extract_legacy_html($coinjoinHtml);
assert_contains($extracted['title'], 'CoinJoin', 'extract title has CoinJoin');
assert_true($extracted['body'] !== '', 'extract body non-empty');
assert_contains($extracted['body'], 'collaborate', 'extract body has article text');
assert_true(!str_contains($extracted['body'], 'author-card'), 'extract strips author-card');

// --- discover excludes utilities ---
$discovered = blog_discover_legacy_articles($root);
$slugs = array_column($discovered, 'slug');
foreach (blog_utility_slugs() as $util) {
    assert_true(!in_array($util, $slugs, true), "discover excludes utility {$util}");
}
assert_true(in_array('coinjoin', $slugs, true), 'discover includes coinjoin');

// --- render post page ---
$config = blog_config([
    'blog_base_url' => 'https://blog.bitmixlist.local',
    'site_base_url' => 'https://bitmixlist.org',
]);
$renderPost = $parsed;
$renderPost['body_format'] = 'markdown';
$html = blog_render_post_page($renderPost, 'en', $config);
assert_contains($html, 'Alpha Title', 'render includes title');
assert_contains($html, '<strong>world</strong>', 'render includes body HTML');
assert_contains($html, 'https://blog.bitmixlist.local/blog/test-post-alpha.html', 'render canonical uses blog base');
assert_contains($html, 'BitMixList', 'render site chrome');

// --- publish path in temp-like workspace subdirectory under root posts using unique slug ---
$pubSlug = 'test-publish-' . substr(sha1((string) microtime(true)), 0, 8);
$pubResult = blog_save_from_fields($root, [
    'slug' => $pubSlug,
    'status' => 'published',
    'title_en' => 'Publish Path Marker ' . $pubSlug,
    'description_en' => 'Published via test',
    'body_en' => "Unique body marker {$pubSlug} for publish verification.",
    'body_format' => 'markdown',
    'canonical_path' => 'blog/' . $pubSlug . '.html',
    'published_at' => '2026-07-31T15:00:00Z',
], true, $config);

assert_true(is_file($pubResult['path']), 'publish wrote content store file');
$publicFile = $root . '/blog/' . $pubSlug . '.html';
assert_true(is_file($publicFile), 'publish wrote public HTML');
$publicHtml = file_get_contents($publicFile);
assert_true($publicHtml !== false, 'read public HTML');
assert_contains($publicHtml, 'Publish Path Marker ' . $pubSlug, 'public HTML has title');
assert_contains($publicHtml, "Unique body marker {$pubSlug}", 'public HTML has body marker');

// cleanup test post artifacts
@unlink($pubResult['path']);
@unlink($publicFile);
// rebuild indexes without the temp post
blog_build($root, $config);

// --- import dry-run on coinjoin + roman-storm ---
$import = blog_import_legacy($root, true, ['coinjoin', 'roman-storm-case', 'faq']);
assert_true(in_array('coinjoin', $import['imported'], true), 'dry-run imports coinjoin');
assert_true(in_array('roman-storm-case', $import['imported'], true), 'dry-run imports roman-storm-case');
assert_true(!in_array('faq', $import['imported'], true), 'dry-run does not import faq');

// --- legacy to post preserves path ---
$legacyItem = null;
foreach ($discovered as $item) {
    if ($item['slug'] === 'coinjoin') {
        $legacyItem = $item;
        break;
    }
}
assert_true($legacyItem !== null, 'found coinjoin discovery item');
$legacyPost = blog_legacy_to_post($legacyItem);
assert_true($legacyPost['canonical_path'] === 'coinjoin.html', 'legacy canonical_path preserved');
assert_true($legacyPost['body_format'] === 'html', 'legacy body_format html');
$legacyHtml = blog_render_post_page($legacyPost, 'en', $config);
assert_contains($legacyHtml, 'https://bitmixlist.org/coinjoin.html', 'legacy canonical uses site base');
assert_contains($legacyHtml, 'CoinJoin', 'legacy render title');

// --- serialize roundtrip ---
$round = blog_parse_post_file_with_trailer(blog_serialize_post($parsed));
assert_true($round['slug'] === $parsed['slug'], 'serialize roundtrip slug');
assert_contains($round['locales']['en']['body'], 'Hello **world**', 'serialize roundtrip EN body');
assert_contains($round['locales']['ru']['body'], 'Привет', 'serialize roundtrip RU body');

echo "\n{$passed} passed, {$failures} failed\n";
exit($failures > 0 ? 1 : 0);
