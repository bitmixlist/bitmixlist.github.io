<?php

declare(strict_types=1);

require_once __DIR__ . '/directory-page.php';
require_once __DIR__ . '/../blog/config.php';
require_once __DIR__ . '/../blog/markdown.php';

function blog_render_post_page(array $post, string $locale, ?array $config = null): string
{
    $config ??= blog_config();
    $isRu = $locale === 'ru';
    $content = $post['locales'][$locale] ?? ['title' => '', 'description' => '', 'body' => ''];
    $titleText = trim((string) $content['title']);
    if ($titleText === '') {
        $titleText = $post['slug'];
    }
    $description = trim((string) $content['description']);
    if ($description === '') {
        $description = mb_substr(trim(preg_replace('/\s+/', ' ', strip_tags((string) $content['body'])) ?? ''), 0, 160, 'UTF-8');
    }

    $outputPath = blog_output_path_for_locale($post, $locale);
    $base = blog_asset_base_prefix($outputPath, $config);
    $home = blog_home_href($outputPath, $locale, $config);
    $blogIndex = blog_relative_href($outputPath, blog_index_path($locale, $config));
    $otherLocale = $isRu ? 'en' : 'ru';
    $hasOther = blog_post_has_locale($post, $otherLocale);
    $langHref = $hasOther
        ? blog_relative_href($outputPath, blog_output_path_for_locale($post, $otherLocale))
        : blog_relative_href($outputPath, blog_index_path($otherLocale, $config));

    $pageTitle = $titleText . ' – BitMixList';
    $canonical = blog_canonical_url($post, $locale, $config);
    $bodyHtml = blog_render_body_html($post, $locale);
    $published = (string) ($post['published_at'] ?? '');
    $updated = (string) ($post['updated_at'] ?? $published);
    $author = (string) ($post['author'] ?? $config['default_author']);
    $authorBio = (string) ($config['default_author_bio'][$locale] ?? $config['default_author_bio']['en']);
    $labels = blog_page_labels($locale);
    $headerTitle = $isRu ? 'Блог' : 'Blog';
    $headerSizes = directory_header_font_sizes($headerTitle);
    $dateLine = blog_format_date_line($published, $updated, $locale);

    // Empty categories array: sidebar still works for common links
    $categories = [];

    return '<!DOCTYPE html>
<html dir="ltr" lang="' . ($isRu ? 'ru-RU' : 'en-GB') . '" prefix="og: https://ogp.me/ns#">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<title>' . directory_escape($pageTitle) . '</title>
<meta content="' . directory_escape($description) . '" name="description"/>
<meta content="' . directory_escape($author) . '" name="author"/>
<link href="' . directory_escape($canonical) . '" rel="canonical"/>
' . directory_render_head_assets($base, $canonical, $pageTitle, $description, $locale) . '
<meta content="' . directory_escape($published) . '" property="article:published_time"/>
<meta content="' . directory_escape($updated) . '" property="article:modified_time"/>
<link as="style" data-optimized="2" href="' . directory_css_asset_url($base, 'wp-content/litespeed/css/d4d1cd3e2db3bf373348bdfd89958038.css') . '" onload="this.onload=null;this.rel=\'stylesheet\'" rel="preload"/>
<noscript><link data-optimized="2" href="' . directory_css_asset_url($base, 'wp-content/litespeed/css/d4d1cd3e2db3bf373348bdfd89958038.css') . '" rel="stylesheet"/></noscript>
<link href="' . directory_css_asset_url($base, 'wp-content/litespeed/css/styles.css') . '" rel="stylesheet"/>
' . directory_render_nav_scripts($base) . '
<style>
.blog-page .site-header { display: block; min-height: 108px; padding-top: 1rem; padding-bottom: 0.95rem; }
.blog-page .site-content-wrapper { padding-top: 124px; }
.blog-page .header-inner { gap: 12px; min-height: 42px; align-items: center; position: relative; padding-right: 8rem; }
.blog-page .header-inner h4 { flex: 1 1 0; min-width: 0; font-size: var(--directory-header-title-size, 1.5rem); line-height: 1.2; font-weight: 650; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.blog-page .lang-switcher { position: absolute; top: 50%; right: 2rem; transform: translateY(-50%); margin-left: 0; flex: 0 0 auto; }
.blog-article { max-width: 820px; margin: 0 auto; padding: 28px 20px 44px; }
.blog-breadcrumb { margin-bottom: 18px; color: #c9c3d8; font-size: 0.95rem; }
.blog-breadcrumb a { text-decoration: underline; text-underline-offset: 0.16em; }
.blog-article h1.entry-title { margin: 0 0 10px; font-size: 2.2rem; line-height: 1.15; }
.blog-meta { color: #c9c3d8; font-size: 0.95rem; margin: 0 0 22px; }
.blog-article .page-content { line-height: 1.65; }
.blog-article .page-content > :first-child { margin-top: 0; }
.blog-article .page-content h2 { margin-top: 1.6em; font-size: 1.35rem; }
.blog-article .page-content a { text-decoration: underline; text-underline-offset: 0.14em; }
.blog-article .author-card { width:100%; box-sizing:border-box; margin:28px 0 0 0; padding:24px; border:1px solid #3a2e55; border-radius:12px; background:#181222; }
@media (max-width: 700px) {
  .blog-page .site-header { min-height: 96px; padding-top: 0.65rem; padding-bottom: 0.55rem; }
  .blog-page .site-content-wrapper { padding-top: 104px; }
  .blog-page .header-inner { padding-right: 4.25rem; }
  .blog-page .lang-switcher { right: 0.5rem; }
  .blog-article h1.entry-title { font-size: 1.75rem; }
  .blog-article { padding: 22px 14px 36px; }
}
</style>
</head>
<body class="page-template-default page wp-custom-logo blog-page" style="--directory-header-title-size: ' . directory_escape($headerSizes['desktop']) . '; --directory-header-title-mobile-size: ' . directory_escape($headerSizes['mobile']) . ';">
<a class="skip-link screen-reader-text" href="#content">' . directory_escape($labels['skip']) . '</a>
' . blog_render_sidebar($base, $isRu, $outputPath) . '
<div aria-hidden="true" class="sidebar-overlay"></div>
<div class="site-content-wrapper">
<header class="site-header" id="site-header" role="banner">
<div class="header-inner">
<button aria-label="' . directory_escape($labels['menu']) . '" class="mobile-menu-toggle">☰</button>
<h4>' . directory_escape($headerTitle) . '</h4>
' . directory_render_lang_switcher($base, $langHref, $isRu) . '
</div>
</header>
' . directory_render_top_ad($isRu) . '
<main class="site-main post-34 page type-page status-publish hentry" id="content">
<article class="page-content blog-article">
<nav class="blog-breadcrumb"><a href="' . directory_escape($home) . '">' . directory_escape($labels['home']) . '</a> / <a href="' . directory_escape($blogIndex) . '">' . directory_escape($labels['blog']) . '</a> / ' . directory_escape($titleText) . '</nav>
<header class="page-header">
<h1 class="entry-title">' . directory_escape($titleText) . '</h1>
<p class="blog-meta">' . directory_escape($dateLine) . '</p>
</header>
<div class="page-content">
' . $bodyHtml . '
<section class="author-card">
<div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
<picture style="flex:0 0 88px;">
<source srcset="' . $base . 'wp-content/uploads/2023/12/cropped-favicon-2-88x88.webp" type="image/webp">
<img src="' . $base . 'wp-content/uploads/2023/12/cropped-favicon-2-88x88.jpg" alt="Author profile picture" width="88" height="88" style="border:1px solid #bdbdc7;border-radius:50%;object-fit:cover;display:block;">
</picture>
<div style="min-width:220px;flex:1;">
<p style="margin:0 0 6px 0;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#c9c3d8;">' . directory_escape($labels['author']) . '</p>
<h3 style="margin:0 0 8px 0;">' . directory_escape($author) . '</h3>
<p style="margin:0;color:#e8e1f5;">' . directory_escape($authorBio) . '</p>
</div>
</div>
</section>
</div>
</article>
</main>
' . directory_render_footer($base, $locale) . '
</div>
' . directory_render_sidebar_script() . '
</body>
</html>
';
}

/**
 * @param list<array> $posts published posts only
 */
function blog_render_index_page(array $posts, string $locale, ?array $config = null): string
{
    $config ??= blog_config();
    $isRu = $locale === 'ru';
    $outputPath = blog_index_path($locale, $config);
    $base = blog_asset_base_prefix($outputPath, $config);
    $home = blog_home_href($outputPath, $locale, $config);
    $otherLocale = $isRu ? 'en' : 'ru';
    $langHref = blog_relative_href($outputPath, blog_index_path($otherLocale, $config));
    $labels = blog_page_labels($locale);
    $headerTitle = $labels['blog'];
    $headerSizes = directory_header_font_sizes($headerTitle);
    $title = $labels['blog'] . ' – BitMixList';
    $description = $isRu
        ? 'Статьи BitMixList о приватности Bitcoin, миксерах и регулировании.'
        : 'BitMixList articles on Bitcoin privacy, mixers, and regulation.';
    $canonical = blog_index_canonical_url($locale, $config);

    $items = '';
    foreach ($posts as $post) {
        if (!blog_post_has_locale($post, $locale)) {
            continue;
        }
        $pTitle = trim((string) ($post['locales'][$locale]['title'] ?? $post['slug']));
        $pDesc = trim((string) ($post['locales'][$locale]['description'] ?? ''));
        $href = blog_relative_href($outputPath, blog_output_path_for_locale($post, $locale));
        $date = blog_format_date_line((string) $post['published_at'], (string) $post['published_at'], $locale);
        $items .= '<li class="blog-index-item">
<a class="blog-index-link" href="' . directory_escape($href) . '"><h2>' . directory_escape($pTitle) . '</h2></a>
<p class="blog-meta">' . directory_escape($date) . '</p>
' . ($pDesc !== '' ? '<p class="blog-index-summary">' . directory_escape($pDesc) . '</p>' : '') . '
</li>
';
    }

    if ($items === '') {
        $items = '<li class="blog-index-item"><p>' . directory_escape($labels['empty']) . '</p></li>';
    }

    return '<!DOCTYPE html>
<html dir="ltr" lang="' . ($isRu ? 'ru-RU' : 'en-GB') . '" prefix="og: https://ogp.me/ns#">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<title>' . directory_escape($title) . '</title>
<meta content="' . directory_escape($description) . '" name="description"/>
<link href="' . directory_escape($canonical) . '" rel="canonical"/>
' . directory_render_head_assets($base, $canonical, $title, $description, $locale) . '
<link as="style" data-optimized="2" href="' . directory_css_asset_url($base, 'wp-content/litespeed/css/d4d1cd3e2db3bf373348bdfd89958038.css') . '" onload="this.onload=null;this.rel=\'stylesheet\'" rel="preload"/>
<noscript><link data-optimized="2" href="' . directory_css_asset_url($base, 'wp-content/litespeed/css/d4d1cd3e2db3bf373348bdfd89958038.css') . '" rel="stylesheet"/></noscript>
<link href="' . directory_css_asset_url($base, 'wp-content/litespeed/css/styles.css') . '" rel="stylesheet"/>
' . directory_render_nav_scripts($base) . '
<style>
.blog-page .site-header { display: block; min-height: 108px; padding-top: 1rem; padding-bottom: 0.95rem; }
.blog-page .site-content-wrapper { padding-top: 124px; }
.blog-page .header-inner { gap: 12px; min-height: 42px; align-items: center; position: relative; padding-right: 8rem; }
.blog-page .header-inner h4 { flex: 1 1 0; min-width: 0; font-size: var(--directory-header-title-size, 1.5rem); line-height: 1.2; font-weight: 650; }
.blog-page .lang-switcher { position: absolute; top: 50%; right: 2rem; transform: translateY(-50%); }
.blog-index { max-width: 820px; margin: 0 auto; padding: 28px 20px 44px; }
.blog-index h1 { margin: 0 0 8px; font-size: 2.2rem; }
.blog-index-lead { color: #c9c3d8; margin: 0 0 28px; }
.blog-index-list { list-style: none; margin: 0; padding: 0; }
.blog-index-item { padding: 18px 0; border-bottom: 1px solid #3a2e55; }
.blog-index-item h2 { margin: 0 0 6px; font-size: 1.35rem; }
.blog-index-link { text-decoration: none; color: inherit; }
.blog-index-link:hover h2 { color: #bb86fc; }
.blog-meta { color: #c9c3d8; font-size: 0.92rem; margin: 0 0 8px; }
.blog-index-summary { margin: 0; color: #e8e1f5; line-height: 1.5; }
@media (max-width: 700px) {
  .blog-page .site-content-wrapper { padding-top: 104px; }
  .blog-page .lang-switcher { right: 0.5rem; }
  .blog-index { padding: 22px 14px 36px; }
}
</style>
</head>
<body class="page-template-default page wp-custom-logo blog-page" style="--directory-header-title-size: ' . directory_escape($headerSizes['desktop']) . '; --directory-header-title-mobile-size: ' . directory_escape($headerSizes['mobile']) . ';">
<a class="skip-link screen-reader-text" href="#content">' . directory_escape($labels['skip']) . '</a>
' . blog_render_sidebar($base, $isRu, $outputPath) . '
<div aria-hidden="true" class="sidebar-overlay"></div>
<div class="site-content-wrapper">
<header class="site-header" id="site-header" role="banner">
<div class="header-inner">
<button aria-label="' . directory_escape($labels['menu']) . '" class="mobile-menu-toggle">☰</button>
<h4>' . directory_escape($headerTitle) . '</h4>
' . directory_render_lang_switcher($base, $langHref, $isRu) . '
</div>
</header>
' . directory_render_top_ad($isRu) . '
<main class="site-main" id="content">
<div class="blog-index">
<h1>' . directory_escape($labels['blog']) . '</h1>
<p class="blog-index-lead">' . directory_escape($description) . '</p>
<ul class="blog-index-list">
' . $items . '</ul>
</div>
</main>
' . directory_render_footer($base, $locale) . '
</div>
' . directory_render_sidebar_script() . '
</body>
</html>
';
}

function blog_page_labels(string $locale): array
{
    if ($locale === 'ru') {
        return [
            'skip' => 'Перейти к содержимому',
            'menu' => 'Меню',
            'home' => 'Главная',
            'blog' => 'Блог',
            'author' => 'Автор',
            'empty' => 'Пока нет опубликованных записей.',
            'published' => 'Опубликовано',
            'updated' => 'Обновлено',
        ];
    }

    return [
        'skip' => 'Skip to content',
        'menu' => 'Menu',
        'home' => 'Home',
        'blog' => 'Blog',
        'author' => 'Author',
        'empty' => 'No published posts yet.',
        'published' => 'Published',
        'updated' => 'Updated',
    ];
}

function blog_post_has_locale(array $post, string $locale): bool
{
    $loc = $post['locales'][$locale] ?? null;
    if (!is_array($loc)) {
        return false;
    }
    $title = trim((string) ($loc['title'] ?? ''));
    $body = trim((string) ($loc['body'] ?? ''));

    return $title !== '' || $body !== '';
}

function blog_output_path_for_locale(array $post, string $locale): string
{
    $canonical = ltrim((string) ($post['canonical_path'] ?? ('blog/' . $post['slug'] . '.html')), '/');
    if ($locale === 'ru') {
        if (str_starts_with($canonical, 'ru/')) {
            return $canonical;
        }

        return 'ru/' . $canonical;
    }

    return $canonical;
}

function blog_index_path(string $locale, ?array $config = null): string
{
    $config ??= blog_config();

    return $config['blog_index_path'][$locale] ?? ($locale === 'ru' ? 'ru/blog/index.html' : 'blog/index.html');
}

/**
 * Canonical absolute URL for a post locale.
 * All public content is under the main site (bitmixlist.org), including /blog/ and legacy root articles.
 * blog.bitmixlist.org is admin-only and never used for public canonicals.
 */
function blog_canonical_url(array $post, string $locale, ?array $config = null): string
{
    $config ??= blog_config();
    $path = blog_output_path_for_locale($post, $locale);

    return rtrim($config['site_base_url'], '/') . '/' . ltrim($path, '/');
}

function blog_index_canonical_url(string $locale, ?array $config = null): string
{
    $config ??= blog_config();

    return rtrim($config['site_base_url'], '/') . '/' . ltrim(blog_index_path($locale, $config), '/');
}

function blog_public_url_path(array $post, string $locale): string
{
    return '/' . blog_output_path_for_locale($post, $locale);
}

function blog_asset_base_prefix(string $outputPath, ?array $config = null): string
{
    $config ??= blog_config();
    if (($config['asset_mode'] ?? 'relative') === 'absolute') {
        return rtrim($config['site_base_url'], '/') . '/';
    }

    return directory_relative_to_root($outputPath);
}

function blog_home_href(string $fromPath, string $locale, ?array $config = null): string
{
    $config ??= blog_config();
    if (($config['asset_mode'] ?? 'relative') === 'absolute') {
        return rtrim($config['site_base_url'], '/') . ($locale === 'ru' ? '/ru/' : '/');
    }

    return blog_relative_href($fromPath, $locale === 'ru' ? 'ru/index.html' : 'index.html');
}

function blog_relative_href(string $fromFile, string $toFile): string
{
    return directory_relative_path($fromFile, $toFile);
}

function blog_format_date_line(string $published, string $updated, string $locale): string
{
    $labels = blog_page_labels($locale);
    $p = blog_human_date($published, $locale);
    $u = blog_human_date($updated, $locale);
    if ($u !== '' && $u !== $p) {
        return $labels['published'] . ' ' . $p . ' · ' . $labels['updated'] . ' ' . $u;
    }

    return $labels['published'] . ' ' . $p;
}

function blog_human_date(string $iso, string $locale): string
{
    $ts = strtotime($iso);
    if ($ts === false) {
        return $iso;
    }

    if ($locale === 'ru') {
        return gmdate('d.m.Y', $ts);
    }

    return gmdate('Y-m-d', $ts);
}

/**
 * Same sidebar as index.html / directory pages (full article nav).
 */
function blog_render_sidebar(string $base, bool $isRu, string $fromPath): string
{
    return directory_render_sidebar($base, $isRu, $fromPath, []);
}
