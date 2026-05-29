<?php

declare(strict_types=1);

function directory_render_page(array $entry, array $categories, string $locale): string
{
    $category = $categories[$entry['category']];
    $content = $entry['content'][$locale];
    $facts = $entry['facts'][$locale];
    $isRu = $locale === 'ru';
    $outputPath = $entry['output_paths'][$locale];
    $base = directory_relative_to_root($outputPath);
    $home = '../index.html';
    $langHref = directory_relative_path($outputPath, $entry['output_paths'][$isRu ? 'en' : 'ru']);
    $langLabel = $isRu ? 'English' : 'Русский';
    $name = $content['name'];
    $title = $name . ' - ' . $category['title'][$locale] . ' - BitMixList';
    $description = directory_page_description($entry, $category, $locale);
    $canonical = 'https://bitmixlist.org/' . $entry['output_paths'][$locale];
    $indexAnchor = directory_relative_public_path($outputPath, directory_section_output_path($entry['category'], $locale));
    $logo = directory_logo_markup($entry, $base, $name);
    $external = $entry['links']['clearnet'] ?? '';
    $tor = $entry['links']['tor'] ?? 'No';
    $mirrors = $entry['links']['mirrors'] ?? [];
    $support = $entry['links']['support'] ?? '';
    $supportHtml = $entry['links']['support_html'] ?? '';
    $verifier = directory_verifier_note($entry, $locale);
    $labels = directory_page_labels($locale);
    $headerTitle = directory_header_title($entry, $category, $locale);
    $headerSizes = directory_header_font_sizes($headerTitle);

    return '<!DOCTYPE html>
<html dir="ltr" lang="' . ($isRu ? 'ru-RU' : 'en-GB') . '">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<title>' . directory_escape($title) . '</title>
<meta content="' . directory_escape($description) . '" name="description"/>
<link href="' . directory_escape($canonical) . '" rel="canonical"/>
<link as="style" data-optimized="2" href="' . $base . 'wp-content/litespeed/css/d4d1cd3e2db3bf373348bdfd89958038.css" onload="this.onload=null;this.rel=\'stylesheet\'" rel="preload"/>
<noscript><link data-optimized="2" href="' . $base . 'wp-content/litespeed/css/d4d1cd3e2db3bf373348bdfd89958038.css" rel="stylesheet"/></noscript>
<link href="' . $base . 'wp-content/litespeed/css/styles.css" rel="stylesheet"/>
' . directory_render_nav_scripts($base) . '
<style>
.directory-page .site-header { display: block; min-height: 108px; padding-top: 1rem; padding-bottom: 0.95rem; }
.directory-page .site-content-wrapper { padding-top: 124px; }
.directory-page .header-inner { gap: 12px; min-height: 42px; align-items: center; position: relative; padding-right: 8rem; }
.directory-page .header-inner h4 { flex: 1 1 0; min-width: 0; font-size: var(--directory-header-title-size, 1.5rem); line-height: 1.2; font-weight: 650; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.directory-page .lang-switcher { position: absolute; top: 50%; right: 2rem; transform: translateY(-50%); margin-left: 0; flex: 0 0 auto; }
.directory-page .sidebar .nav-menu a { font-size: 0.95rem; line-height: 1.25; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.directory-page .mobile-menu-toggle { flex: 0 0 auto; }
.directory-page .sylvester-top, .directory-page .sylvester-top-mobile { margin-top: 1rem !important; }
.directory-meta-nav { display: flex; justify-content: center; gap: 22px; width: 100%; margin: 0.45rem 0 0; padding: 0 1.5rem 0.15rem; overflow-x: auto; scrollbar-width: thin; box-sizing: border-box; }
.directory-meta-link { flex: 0 0 auto; padding: 0 0 3px; border: 0; border-bottom: 2px solid transparent; border-radius: 0; background: transparent; color: #d7d0e6; font-size: 0.9rem; line-height: 1.25; text-decoration: none; white-space: nowrap; }
.directory-meta-link:hover, .directory-meta-link:focus { border-bottom-color: rgba(187, 134, 252, 0.6); background: transparent; color: #fff; text-decoration: none; }
.directory-meta-link.is-active { border-bottom-color: #bb86fc; background: transparent; color: #fff; }
.directory-detail { max-width: 1080px; margin: 0 auto; padding: 28px 20px 44px; }
.directory-breadcrumb { margin-bottom: 18px; color: #c9c3d8; font-size: 0.95rem; }
.directory-breadcrumb a { text-decoration: underline; text-underline-offset: 0.16em; }
.directory-hero { display: grid; grid-template-columns: 144px minmax(0, 1fr); gap: 22px; align-items: center; padding-bottom: 22px; border-bottom: 1px solid #3a2e55; }
.directory-logo { width: 128px; height: 128px; border-radius: 10px; background: linear-gradient(135deg, #955ff5, #3d005c); border: 2px solid #fff; object-fit: contain; box-sizing: border-box; }
.directory-logo--text { display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 800; font-size: 2rem; text-align: center; }
.directory-kicker { color: #c7b8ff; font-size: 0.95rem; margin: 0 0 6px; }
.directory-detail h1 { margin: 0 0 8px; font-size: 2.6rem; line-height: 1.05; letter-spacing: 0; }
.directory-summary { margin: 0; color: #e8e1f5; line-height: 1.55; max-width: 760px; }
.directory-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 18px; }
.directory-button { display: inline-flex; align-items: center; justify-content: center; min-height: 38px; padding: 0 12px; border: 1px solid #7a61f6; border-radius: 7px; background: #1a1234; color: #f2ecff; text-decoration: none; font-weight: 650; }
.directory-button:hover, .directory-button:focus { background: #27184d; color: #fff; text-decoration: none; }
.directory-section { margin-top: 28px; }
.directory-section h2 { margin: 0 0 12px; font-size: 1.35rem; letter-spacing: 0; }
.directory-facts { width: 100%; border-collapse: collapse; table-layout: fixed; }
.directory-facts th, .directory-facts td { vertical-align: top; padding: 12px; overflow-wrap: anywhere; word-break: normal; }
.directory-facts th { width: 30%; color: #f6f2ff; text-align: left; background: #282238; }
.directory-facts td { background: #1c1728; }
.directory-note { margin: 0; padding: 14px 16px; border: 1px solid #4a3a70; border-radius: 8px; background: #181222; line-height: 1.55; }
.directory-footer-spacer { margin-top: 34px; }
@media (max-width: 700px) {
  .directory-page .site-header { min-height: 96px; padding-top: 0.65rem; padding-bottom: 0.55rem; }
  .directory-page .site-content-wrapper { padding-top: 104px; }
  .directory-page .header-inner { gap: 8px; min-height: 38px; padding-left: 0.5rem; padding-right: 4.25rem; }
  .directory-page .header-inner h4 { font-size: var(--directory-header-title-mobile-size, 1.15rem); }
  .directory-page .sidebar .nav-menu a { font-size: 0.9rem; }
  .directory-page .lang-switcher { right: 0.5rem; gap: 4px; }
  .directory-page .lang-link { gap: 0; padding: 3px 5px; }
  .directory-page .lang-link span { display: none; }
  .directory-meta-nav { justify-content: flex-start; gap: 16px; margin-top: 0.35rem; padding-left: 0.75rem; padding-right: 0.75rem; }
  .directory-meta-link { font-size: 0.84rem; }
  .directory-detail h1 { font-size: 2rem; }
  .directory-detail { padding: 22px 14px 36px; }
  .directory-hero { grid-template-columns: 1fr; }
  .directory-logo { width: 112px; height: 112px; }
  .directory-facts, .directory-facts tbody, .directory-facts tr, .directory-facts th, .directory-facts td { display: block; width: 100%; box-sizing: border-box; }
  .directory-facts th { border-bottom: 0; }
}
@media (max-width: 420px) {
  .directory-detail h1 { font-size: 1.75rem; }
}
</style>
</head>
<body class="page-template-default page wp-custom-logo directory-page" style="--directory-header-title-size: ' . directory_escape($headerSizes['desktop']) . '; --directory-header-title-mobile-size: ' . directory_escape($headerSizes['mobile']) . ';">
<a class="skip-link screen-reader-text" href="#content">' . directory_escape($labels['skip']) . '</a>
' . directory_render_sidebar($base, $isRu, $outputPath, $categories) . '
<div aria-hidden="true" class="sidebar-overlay"></div>
<div class="site-content-wrapper">
<header class="site-header" id="site-header" role="banner">
<div class="header-inner">
<button aria-label="' . directory_escape($labels['menu']) . '" class="mobile-menu-toggle">☰</button>
<h4>' . directory_escape($headerTitle) . '</h4>
' . directory_render_lang_switcher($base, $langHref, $isRu) . '
</div>
' . directory_render_meta_nav($categories, $locale, $entry['category'], $outputPath) . '
</header>
' . directory_render_top_ad($isRu) . '
<main class="site-main post-34 page type-page status-publish hentry" id="content">
<article class="page-content directory-detail">
<nav class="directory-breadcrumb"><a href="' . directory_escape($home) . '">' . directory_escape($labels['home']) . '</a> / <a href="' . directory_escape($indexAnchor) . '">' . directory_escape($category['title'][$locale]) . '</a> / ' . directory_escape($name) . '</nav>
<section class="directory-hero">
<div>' . $logo . '</div>
<div>
<p class="directory-kicker">' . directory_escape($category['singular'][$locale]) . '</p>
<h1>' . directory_escape($name) . '</h1>
<p class="directory-summary">' . directory_escape($description) . '</p>
<div class="directory-actions">
' . ($external !== '' ? '<a class="directory-button" href="' . directory_escape($external) . '" rel="noopener noreferrer" target="_blank">' . directory_escape($labels['visit']) . '</a>' : '') . '
<a class="directory-button" href="' . directory_escape($langHref) . '">' . directory_escape($langLabel) . '</a>
</div>
</div>
</section>
<section class="directory-section">
<h2>' . directory_escape($labels['links']) . '</h2>
<table class="directory-facts">
<tbody>
<tr><th>' . directory_escape($labels['clearnet']) . '</th><td>' . directory_external_value($external) . '</td></tr>
<tr><th>' . directory_escape($labels['tor']) . '</th><td>' . directory_external_value($tor) . '</td></tr>
' . ($mirrors !== [] ? '<tr><th>' . directory_escape($labels['mirrors']) . '</th><td>' . directory_render_mirror_links($mirrors) . '</td></tr>' : '') . '
' . ($support !== '' ? '<tr><th>' . directory_escape($labels['support']) . '</th><td>' . directory_render_support_value($support, $supportHtml) . '</td></tr>' : '') . '
</tbody>
</table>
</section>
<section class="directory-section">
<h2>' . directory_escape($labels['facts']) . '</h2>
<table class="directory-facts">
<tbody>
' . directory_render_fact_rows($facts) . '
</tbody>
</table>
</section>
<section class="directory-section">
<h2>' . directory_escape($labels['verification']) . '</h2>
<p class="directory-note">' . directory_escape($verifier) . '</p>
</section>
<div class="directory-footer-spacer"></div>
</article>
</main>
' . directory_render_footer($base) . '
</div>
' . directory_render_sidebar_script() . '
</body>
</html>
';
}

function directory_render_section_page(string $categorySlug, array $data, string $locale): string
{
    $categories = $data['categories'];
    $category = $categories[$categorySlug];
    $isRu = $locale === 'ru';
    $outputPath = directory_section_output_path($categorySlug, $locale);
    $base = directory_relative_to_root($outputPath);
    $home = '../index.html';
    $langHref = directory_relative_public_path($outputPath, directory_section_output_path($categorySlug, $isRu ? 'en' : 'ru'));
    $title = $category['title'][$locale] . ' - BitMixList';
    $description = directory_section_description($category, $locale);
    $canonical = 'https://bitmixlist.org/' . directory_section_public_url($categorySlug, $locale);
    $labels = directory_page_labels($locale);
    $headerTitle = $category['nav_label'][$locale] ?? $category['title'][$locale];
    $headerSizes = directory_header_font_sizes($headerTitle);
    $entries = array_values(array_filter(
        $data['entries'],
        static fn (array $entry): bool => $entry['category'] === $categorySlug
    ));

    return '<!DOCTYPE html>
<html dir="ltr" lang="' . ($isRu ? 'ru-RU' : 'en-GB') . '">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<title>' . directory_escape($title) . '</title>
<meta content="' . directory_escape($description) . '" name="description"/>
<link href="' . directory_escape($canonical) . '" rel="canonical"/>
<link as="style" data-optimized="2" href="' . $base . 'wp-content/litespeed/css/d4d1cd3e2db3bf373348bdfd89958038.css" onload="this.onload=null;this.rel=\'stylesheet\'" rel="preload"/>
<noscript><link data-optimized="2" href="' . $base . 'wp-content/litespeed/css/d4d1cd3e2db3bf373348bdfd89958038.css" rel="stylesheet"/></noscript>
<link href="' . $base . 'wp-content/litespeed/css/styles.css" rel="stylesheet"/>
' . directory_render_nav_scripts($base) . '
<style>
.directory-page .site-header { display: block; min-height: 108px; padding-top: 1rem; padding-bottom: 0.95rem; }
.directory-page .site-content-wrapper { padding-top: 124px; }
.directory-page .header-inner { gap: 12px; min-height: 42px; align-items: center; position: relative; padding-right: 8rem; }
.directory-page .header-inner h4 { flex: 1 1 0; min-width: 0; font-size: var(--directory-header-title-size, 1.5rem); line-height: 1.2; font-weight: 650; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.directory-page .lang-switcher { position: absolute; top: 50%; right: 2rem; transform: translateY(-50%); margin-left: 0; flex: 0 0 auto; }
.directory-page .sidebar .nav-menu a { font-size: 0.95rem; line-height: 1.25; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.directory-page .mobile-menu-toggle { flex: 0 0 auto; }
.directory-page .sylvester-top, .directory-page .sylvester-top-mobile { margin-top: 1rem !important; }
.directory-meta-nav { display: flex; justify-content: center; gap: 22px; width: 100%; margin: 0.45rem 0 0; padding: 0 1.5rem 0.15rem; overflow-x: auto; scrollbar-width: thin; box-sizing: border-box; }
.directory-meta-link { flex: 0 0 auto; padding: 0 0 3px; border: 0; border-bottom: 2px solid transparent; border-radius: 0; background: transparent; color: #d7d0e6; font-size: 0.9rem; line-height: 1.25; text-decoration: none; white-space: nowrap; }
.directory-meta-link:hover, .directory-meta-link:focus { border-bottom-color: rgba(187, 134, 252, 0.6); background: transparent; color: #fff; text-decoration: none; }
.directory-meta-link.is-active { border-bottom-color: #bb86fc; background: transparent; color: #fff; }
.directory-detail { max-width: 1080px; margin: 0 auto; padding: 28px 20px 44px; }
.directory-breadcrumb { margin-bottom: 18px; color: #c9c3d8; font-size: 0.95rem; }
.directory-breadcrumb a { text-decoration: underline; text-underline-offset: 0.16em; }
.directory-detail h1 { margin: 0 0 8px; font-size: 2.6rem; line-height: 1.05; letter-spacing: 0; }
.directory-summary { margin: 0; color: #e8e1f5; line-height: 1.55; max-width: 760px; }
.directory-section { margin-top: 28px; }
.directory-section h2 { margin: 0 0 12px; font-size: 1.35rem; letter-spacing: 0; }
.directory-list { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 12px; }
.directory-list-card { display: grid; grid-template-columns: 64px minmax(0, 1fr); gap: 12px; min-width: 0; padding: 14px; border: 1px solid #3a2e55; border-radius: 8px; background: #181222; }
.directory-list-card .directory-logo { width: 64px; height: 64px; border-radius: 8px; object-fit: contain; }
.directory-list-card .directory-logo--text { font-size: 1.15rem; }
.directory-list-title { margin: 0 0 4px; font-size: 1.05rem; line-height: 1.25; font-weight: 700; }
.directory-list-title a { color: #f6f2ff; text-decoration: none; }
.directory-list-title a:hover, .directory-list-title a:focus { text-decoration: underline; }
.directory-list-summary { margin: 0; color: #d8d0e8; font-size: 0.92rem; line-height: 1.45; }
.directory-list-actions { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
.directory-button { display: inline-flex; align-items: center; justify-content: center; min-height: 34px; padding: 0 10px; border: 1px solid #7a61f6; border-radius: 7px; background: #1a1234; color: #f2ecff; text-decoration: none; font-size: 0.9rem; font-weight: 650; }
.directory-button:hover, .directory-button:focus { background: #27184d; color: #fff; text-decoration: none; }
.directory-facts { width: 100%; border-collapse: collapse; table-layout: fixed; }
.directory-facts th, .directory-facts td { vertical-align: top; padding: 12px; overflow-wrap: anywhere; word-break: normal; }
.directory-facts th { color: #f6f2ff; text-align: left; background: #282238; }
.directory-facts td { background: #1c1728; }
@media (max-width: 700px) {
  .directory-page .site-header { min-height: 96px; padding-top: 0.65rem; padding-bottom: 0.55rem; }
  .directory-page .site-content-wrapper { padding-top: 104px; }
  .directory-page .header-inner { gap: 8px; min-height: 38px; padding-left: 0.5rem; padding-right: 4.25rem; }
  .directory-page .header-inner h4 { font-size: var(--directory-header-title-mobile-size, 1.15rem); }
  .directory-page .sidebar .nav-menu a { font-size: 0.9rem; }
  .directory-page .lang-switcher { right: 0.5rem; gap: 4px; }
  .directory-page .lang-link { gap: 0; padding: 3px 5px; }
  .directory-page .lang-link span { display: none; }
  .directory-meta-nav { justify-content: flex-start; gap: 16px; margin-top: 0.35rem; padding-left: 0.75rem; padding-right: 0.75rem; }
  .directory-meta-link { font-size: 0.84rem; }
  .directory-detail { padding: 22px 14px 36px; }
  .directory-detail h1 { font-size: 2rem; }
  .directory-list { grid-template-columns: 1fr; }
  .directory-facts, .directory-facts tbody, .directory-facts tr, .directory-facts th, .directory-facts td { display: block; width: 100%; box-sizing: border-box; }
}
</style>
</head>
<body class="page-template-default page wp-custom-logo directory-page" style="--directory-header-title-size: ' . directory_escape($headerSizes['desktop']) . '; --directory-header-title-mobile-size: ' . directory_escape($headerSizes['mobile']) . ';">
<a class="skip-link screen-reader-text" href="#content">' . directory_escape($labels['skip']) . '</a>
' . directory_render_sidebar($base, $isRu, $outputPath, $categories) . '
<div aria-hidden="true" class="sidebar-overlay"></div>
<div class="site-content-wrapper">
<header class="site-header" id="site-header" role="banner">
<div class="header-inner">
<button aria-label="' . directory_escape($labels['menu']) . '" class="mobile-menu-toggle">☰</button>
<h4>' . directory_escape($headerTitle) . '</h4>
' . directory_render_lang_switcher($base, $langHref, $isRu) . '
</div>
' . directory_render_meta_nav($categories, $locale, $categorySlug, $outputPath) . '
</header>
' . directory_render_top_ad($isRu) . '
<main class="site-main post-34 page type-page status-publish hentry" id="content">
<article class="page-content directory-detail">
<nav class="directory-breadcrumb"><a href="' . directory_escape($home) . '">' . directory_escape($labels['home']) . '</a> / ' . directory_escape($category['title'][$locale]) . '</nav>
<h1>' . directory_escape($category['title'][$locale]) . '</h1>
<p class="directory-summary">' . directory_escape($description) . '</p>
<section class="directory-section">
<h2>' . directory_escape($locale === 'ru' ? 'Записи' : 'Entries') . '</h2>
<div class="directory-list">
' . directory_render_section_cards($entries, $locale, $base, $outputPath) . '
</div>
</section>
<section class="directory-section">
<h2>' . directory_escape($locale === 'ru' ? 'Сравнение' : 'Comparison') . '</h2>
' . directory_render_section_table($entries, $locale, $outputPath) . '
</section>
</article>
</main>
' . directory_render_footer($base) . '
</div>
' . directory_render_sidebar_script() . '
</body>
</html>
';
}

function directory_page_labels(string $locale): array
{
    if ($locale === 'ru') {
        return [
            'skip' => 'Перейти к содержимому',
            'menu' => 'Открыть меню',
            'home' => 'Главная',
            'visit' => 'Открыть сайт',
            'links' => 'Ссылки',
            'clearnet' => 'Официальный сайт',
            'tor' => 'Tor-сайт',
            'mirrors' => 'Зеркала',
            'support' => 'Поддержка',
            'facts' => 'Параметры',
            'verification' => 'Проверка',
        ];
    }

    return [
        'skip' => 'Skip to content',
        'menu' => 'Open menu',
        'home' => 'Home',
        'visit' => 'Visit official site',
        'links' => 'Links',
        'clearnet' => 'Official site',
        'tor' => 'Tor site',
        'mirrors' => 'Known mirrors',
        'support' => 'Support',
        'facts' => 'Parameters',
        'verification' => 'Verification',
    ];
}

function directory_render_nav_scripts(string $base): string
{
    return '<script src="' . directory_escape($base) . 'wp-content/litespeed/js/ad-loader.js"></script>
<script src="' . directory_escape($base) . 'wp-content/litespeed/js/site-search.js" defer></script>
<script>
document.addEventListener(\'DOMContentLoaded\', function () {
  if (window.bitmixlistLoadTopAd) {
    window.bitmixlistLoadTopAd();
  }
});
</script>';
}

function directory_render_top_ad(bool $isRu): string
{
    if ($isRu) {
        return '<div class="sylvester-top" style="width:728px;height:90px;">
<noscript><a href="https://r7promotions.com/go/bh8697"><img alt="Рекламный баннер" src="https://r7promotions.com/r7-asset/site-banners/post-top-728x90.gif"/></a></noscript>
</div>
<div class="sylvester-top-mobile" style="width:350px;height:50px;">
<noscript><a href="https://r7promotions.com/go/bh8697"><img alt="Рекламный баннер" src="https://r7promotions.com/r7-asset/site-banners/home-350x50-mobile.gif"/></a></noscript>
</div>
<div class="centered"><p>Это реклама. Реклама не одобрена BitMixList.</p></div>';
    }

    return '<div class="sylvester-top" style="width:728px;height:90px;">
<noscript><a href="https://mixtum.io" rel="sponsored noopener noreferrer"><img alt="Sponsored banner" src="https://bitmixlist-adserver-242473302317.us-central1.run.app/tweet?device-width=728&amp;ad=0"/></a></noscript>
</div>
<div class="sylvester-top-mobile" style="width:350px;height:50px;">
<noscript><a href="https://mixtum.io" rel="sponsored noopener noreferrer"><img alt="Sponsored banner" src="https://bitmixlist-adserver-242473302317.us-central1.run.app/tweet?device-width=350&amp;ad=0"/></a></noscript>
</div>
<div class="centered"><p>This is an ad. Ads are not endorsed by BitMixList.</p></div>';
}

function directory_render_meta_nav(array $categories, string $locale, string $currentCategory, string $fromPath): string
{
    $label = $locale === 'ru' ? 'Разделы каталога' : 'Directory sections';
    $links = [];

    foreach ($categories as $slug => $category) {
        $isActive = $slug === $currentCategory;
        $classes = 'directory-meta-link' . ($isActive ? ' is-active' : '');
        $current = $isActive ? ' aria-current="true"' : '';
        $text = $category['nav_label'][$locale] ?? $category['title'][$locale];
        $href = directory_relative_public_path($fromPath, directory_section_output_path($slug, $locale));
        $links[] = '<a class="' . directory_escape($classes) . '" href="' . directory_escape($href) . '"' . $current . '>' . directory_escape($text) . '</a>';
    }

    return '<nav class="directory-meta-nav" aria-label="' . directory_escape($label) . '">
' . implode("\n", $links) . '
</nav>';
}

function directory_section_output_path(string $categorySlug, string $locale): string
{
    return ($locale === 'ru' ? 'ru/' : '') . $categorySlug . '/index.html';
}

function directory_section_public_url(string $categorySlug, string $locale): string
{
    return ($locale === 'ru' ? 'ru/' : '') . $categorySlug . '/';
}

function directory_relative_public_path(string $fromFile, string $toFile): string
{
    $path = directory_relative_path($fromFile, $toFile);
    if ($path === 'index.html') {
        return './';
    }
    if (str_ends_with($path, '/index.html')) {
        return substr($path, 0, -strlen('index.html'));
    }

    return $path;
}

function directory_section_description(array $category, string $locale): string
{
    if ($locale === 'ru') {
        return 'Раздел BitMixList для категории "' . $category['title'][$locale] . '" с отдельными страницами записей, ссылками и параметрами.';
    }

    return 'BitMixList directory section for ' . $category['title'][$locale] . ', with individual entry pages, links, and parameters.';
}

function directory_render_section_cards(array $entries, string $locale, string $base, string $fromPath): string
{
    $cards = '';
    foreach ($entries as $entry) {
        $cards .= directory_render_section_card($entry, $locale, $base, $fromPath) . "\n";
    }

    return $cards;
}

function directory_render_section_card(array $entry, string $locale, string $base, string $fromPath): string
{
    $content = $entry['content'][$locale];
    $name = $content['name'];
    $summary = trim($content['summary'] ?? '');
    $entryHref = directory_relative_path($fromPath, $entry['output_paths'][$locale]);
    $external = $entry['links']['clearnet'] ?? '';
    $detailsLabel = $locale === 'ru' ? 'Параметры' : 'Details';
    $visitLabel = $entry['type'] === 'tool'
        ? ($locale === 'ru' ? 'Открыть проект' : 'Visit project')
        : ($locale === 'ru' ? 'Открыть сайт' : 'Visit site');

    return '<article class="directory-list-card">
<div>' . directory_logo_markup($entry, $base, $name) . '</div>
<div>
<h3 class="directory-list-title"><a href="' . directory_escape($entryHref) . '">' . directory_escape($name) . '</a></h3>
' . ($summary !== '' ? '<p class="directory-list-summary">' . directory_escape($summary) . '</p>' : '') . '
<div class="directory-list-actions">
<a class="directory-button" href="' . directory_escape($entryHref) . '">' . directory_escape($detailsLabel) . '</a>
' . ($external !== '' ? '<a class="directory-button" href="' . directory_escape($external) . '" rel="noopener noreferrer" target="_blank">' . directory_escape($visitLabel) . '</a>' : '') . '
</div>
</div>
</article>';
}

function directory_render_section_table(array $entries, string $locale, string $fromPath): string
{
    $nameLabel = $locale === 'ru' ? 'Название' : 'Name';
    $siteLabel = $locale === 'ru' ? 'Официальный сайт' : 'Official site';
    $torLabel = $locale === 'ru' ? 'Tor-сайт' : 'Tor site';
    $summaryLabel = $locale === 'ru' ? 'Кратко' : 'Summary';
    $rows = '';

    foreach ($entries as $entry) {
        $content = $entry['content'][$locale];
        $name = $content['name'];
        $summary = trim($content['summary'] ?? '');
        $entryHref = directory_relative_path($fromPath, $entry['output_paths'][$locale]);
        $external = $entry['links']['clearnet'] ?? '';
        $tor = $entry['links']['tor'] ?? 'No';

        $rows .= '<tr><td><a href="' . directory_escape($entryHref) . '">' . directory_escape($name) . '</a></td><td>' . directory_external_value($external) . '</td><td>' . directory_external_value($tor) . '</td><td>' . directory_escape($summary) . '</td></tr>' . "\n";
    }

    return '<table class="directory-facts">
<thead><tr><th>' . directory_escape($nameLabel) . '</th><th>' . directory_escape($siteLabel) . '</th><th>' . directory_escape($torLabel) . '</th><th>' . directory_escape($summaryLabel) . '</th></tr></thead>
<tbody>
' . $rows . '</tbody>
</table>';
}

function directory_page_description(array $entry, array $category, string $locale): string
{
    $content = $entry['content'][$locale];
    $summary = trim($content['summary'] ?? '');
    $name = $content['name'];
    $categoryName = $category['singular'][$locale];

    if ($locale === 'ru') {
        if ($summary !== '') {
            return $name . ': ' . $summary . '. Страница параметров BitMixList для категории "' . $categoryName . '".';
        }
        return $name . ': страница параметров BitMixList для категории "' . $categoryName . '".';
    }

    if ($summary !== '') {
        return $name . ': ' . $summary . '. BitMixList parameter page for this ' . strtolower($categoryName) . '.';
    }

    return $name . ': BitMixList parameter page for this ' . strtolower($categoryName) . '.';
}

function directory_header_title(array $entry, array $category, string $locale): string
{
    return $entry['content'][$locale]['name'] . ' - ' . $category['singular'][$locale];
}

function directory_header_font_sizes(string $title): array
{
    $length = mb_strlen($title, 'UTF-8');

    $desktop = '1.5rem';
    if ($length > 64) {
        $desktop = '0.92rem';
    } elseif ($length > 54) {
        $desktop = '1.02rem';
    } elseif ($length > 44) {
        $desktop = '1.14rem';
    } elseif ($length > 34) {
        $desktop = '1.26rem';
    } elseif ($length > 30) {
        $desktop = '1.38rem';
    }

    $mobile = '1.15rem';
    if ($length > 50) {
        $mobile = '0.7rem';
    } elseif ($length > 40) {
        $mobile = '0.78rem';
    } elseif ($length > 30) {
        $mobile = '0.88rem';
    } elseif ($length > 22) {
        $mobile = '1rem';
    }

    return [
        'desktop' => $desktop,
        'mobile' => $mobile,
    ];
}

function directory_verifier_note(array $entry, string $locale): string
{
    $support = $entry['links']['support'] ?? '';
    $isMixer = $entry['category'] === 'mixers';
    $hasPgp = preg_match('/pgp/i', $support) === 1;

    if ($locale === 'ru') {
        if ($hasPgp) {
            return 'Для этой записи указан PGP-канал. Сохраняйте письмо гарантии и проверяйте подпись в верификаторе гарантий BitMixList перед тем, как полагаться на возврат.';
        }
        if ($isMixer) {
            return 'Сохраняйте письмо гарантии, если сервис его выдает, и проверяйте его в верификаторе гарантий BitMixList перед тем, как полагаться на возврат.';
        }
        return 'Проверяйте официальный домен и любые гарантийные условия перед отправкой средств.';
    }

    if ($hasPgp) {
        return 'This entry lists a PGP support channel. Save any letter of guarantee and verify the signature in the BitMixList guarantee verifier before relying on a refund.';
    }
    if ($isMixer) {
        return 'Save the letter of guarantee when the service provides one, and verify it in the BitMixList guarantee verifier before relying on a refund.';
    }

    return 'Verify the official domain and any guarantee terms before sending funds.';
}

function directory_logo_markup(array $entry, string $base, string $name): string
{
    $webp = $entry['assets']['webp'] ?? '';
    $image = $entry['assets']['image'] ?? '';
    $alt = $entry['assets']['alt'] ?? $name . ' logo';

    if ($image === '') {
        return '<div aria-label="' . directory_escape($alt) . '" class="directory-logo directory-logo--text">' . directory_escape(directory_initials($name)) . '</div>';
    }

    $webpMarkup = $webp !== '' ? '<source srcset="' . directory_escape($base . ltrim($webp, '/')) . '" type="image/webp"/>' : '';

    return '<picture>' . $webpMarkup . '<img alt="' . directory_escape($alt) . '" class="directory-logo" src="' . directory_escape($base . ltrim($image, '/')) . '"/></picture>';
}

function directory_initials(string $name): string
{
    $words = preg_split('/\s+/u', preg_replace('/[^[:alnum:]\s]+/u', ' ', $name) ?? $name) ?: [];
    $initials = '';
    foreach ($words as $word) {
        if ($word === '') {
            continue;
        }
        $initials .= mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8');
        if (mb_strlen($initials, 'UTF-8') >= 3) {
            break;
        }
    }

    return $initials !== '' ? $initials : mb_substr($name, 0, 2, 'UTF-8');
}

function directory_render_fact_rows(array $facts): string
{
    $rows = '';
    foreach ($facts as $fact) {
        $value = trim($fact['value'] ?? '');
        if ($value === '') {
            continue;
        }
        $rows .= '<tr><th>' . directory_escape($fact['label'] ?? '') . '</th><td>' . directory_escape($value) . '</td></tr>' . "\n";
    }

    return $rows;
}

function directory_render_support_value(string $support, string $supportHtml): string
{
    $supportHtml = trim($supportHtml);
    if ($supportHtml === '') {
        return directory_escape($support);
    }

    return directory_normalize_inline_links($supportHtml);
}

function directory_normalize_inline_links(string $html): string
{
    $dom = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="UTF-8"><body>' . $html . '</body>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOWARNING | LIBXML_NOERROR);
    libxml_clear_errors();

    foreach ($dom->getElementsByTagName('a') as $anchor) {
        if (!$anchor instanceof DOMElement) {
            continue;
        }
        $href = $anchor->getAttribute('href');
        if (preg_match('#^https?://#i', $href)) {
            $anchor->setAttribute('target', '_blank');
        }
        $anchor->setAttribute('rel', 'noopener noreferrer');
    }

    $body = $dom->getElementsByTagName('body')->item(0);
    if (!$body) {
        return directory_escape($html);
    }

    $output = '';
    foreach ($body->childNodes as $child) {
        $output .= $dom->saveHTML($child);
    }

    return trim($output);
}

function directory_external_value(string $value): string
{
    if ($value === '' || $value === 'No') {
        return 'No';
    }

    return '<a href="' . directory_escape($value) . '" rel="noopener noreferrer" target="_blank">' . directory_escape($value) . '</a>';
}

function directory_render_mirror_links(array $mirrors): string
{
    $links = [];
    foreach ($mirrors as $mirror) {
        $url = $mirror['url'] ?? '';
        $label = $mirror['label'] ?? $url;
        if ($url === '') {
            continue;
        }
        $links[] = '<a href="' . directory_escape($url) . '" rel="noopener noreferrer" target="_blank">' . directory_escape($label) . '</a>';
    }

    return implode(', ', $links);
}

function directory_render_sidebar(string $base, bool $isRu, string $fromPath, array $categories): string
{
    $home = '../index.html';
    $rootHome = $home;
    $labels = $isRu
        ? [
            ['Проверка на скам', '../scam-lookup.html'],
            ['Верифицировать гарантию', '../letter-verify.html'],
            ['AML-чекер', '../aml-check.html'],
            ['Ранняя история', '../early-history.html'],
            ['Конфиденциальность миксеров', '../mixer-privacy.html'],
            ['Эволюция регулирования', '../evolving-regulation.html'],
            ['Преступность', '../crime.html'],
            ['Регуляторное давление', '../crackdown.html'],
            ['Последствия', '../aftermath.html'],
            ['Зачем нам нужны миксеры', '../mixers-necessity.html'],
            ['Часто задаваемые вопросы', '../faq.html'],
            ['Допустимое использование', '../terms-and-conditions.html'],
            ['Журнал изменений', '../changelog.html'],
        ]
        : [
            ['Scam Lookup', '../scam-lookup.html'],
            ['Verify Guarrantee', '../letter-verify.html'],
            ['AML Checker', '../aml-check.html'],
            ['Early History', '../early-history.html'],
            ['Mixer Privacy', '../mixer-privacy.html'],
            ['Evolving Regulation', '../evolving-regulation.html'],
            ['Crime', '../crime.html'],
            ['Crackdown', '../crackdown.html'],
            ['Aftermath', '../aftermath.html'],
            ['Why We Need Mixers', '../mixers-necessity.html'],
            ['FAQ', '../faq.html'],
            ['Acceptable Use', '../terms-and-conditions.html'],
            ['Changelog', '../changelog.html'],
        ];

    $items = '';
    foreach ($labels as [$label, $href]) {
        $items .= '<li class="menu-item"><a class="nav-link" href="' . directory_escape($href) . '">' . directory_escape($label) . '</a></li>' . "\n";
    }

    return '<nav class="sidebar">
<div class="logo-container">
<button aria-label="Collapse navigation menu" class="sidebar-menu-toggle">☰</button>
<a class="custom-logo-link" href="' . directory_escape($rootHome) . '" rel="home">
<picture>
<source srcset="' . $base . 'wp-content/uploads/2023/12/logo-2-e1702276596201.webp" type="image/webp"/>
<img alt="BitMixList - Bitcoin Mixer List" class="custom-logo has-transparency" decoding="async" src="' . $base . 'wp-content/uploads/2023/12/logo-2-e1702276596201.jpg" width="200"/>
</picture>
</a>
</div>
<ul class="nav-menu">
' . $items . '</ul>
</nav>';
}

function directory_render_lang_switcher(string $base, string $langHref, bool $isRu): string
{
    $enFlag = $base . 'wp-content/uploads/2023/12/flag-en.svg';
    $ruFlag = $base . 'wp-content/uploads/2023/12/flag-ru.svg';

    if ($isRu) {
        return '<div class="lang-switcher" aria-label="Language selector">
<a class="lang-link" href="' . directory_escape($langHref) . '" hreflang="en" lang="en">
<img src="' . directory_escape($enFlag) . '" alt="EN flag" width="14" height="10"/>
<span>EN</span>
</a>
<span class="lang-link is-active" aria-current="true">
<img src="' . directory_escape($ruFlag) . '" alt="RU flag" width="14" height="10"/>
<span>RU</span>
</span>
</div>';
    }

    return '<div class="lang-switcher" aria-label="Language selector">
<span class="lang-link is-active" aria-current="true">
<img src="' . directory_escape($enFlag) . '" alt="EN flag" width="14" height="10"/>
<span>EN</span>
</span>
<a class="lang-link" href="' . directory_escape($langHref) . '" hreflang="ru" lang="ru">
<img src="' . directory_escape($ruFlag) . '" alt="RU flag" width="14" height="10"/>
<span>RU</span>
</a>
</div>';
}

function directory_render_sidebar_script(): string
{
    return '<script>
document.addEventListener("DOMContentLoaded", function () {
  const menuToggle = document.querySelector(".mobile-menu-toggle");
  const sidebarToggle = document.querySelector(".sidebar-menu-toggle");
  const sidebar = document.querySelector(".sidebar");
  const overlay = document.querySelector(".sidebar-overlay");
  const navLinks = document.querySelectorAll(".nav-menu .nav-link");
  let touchStartX = null;

  function syncOverlay() {
    if (!overlay || !sidebar) return;
    const shouldShow = sidebar.classList.contains("active") && window.innerWidth <= 1024;
    overlay.classList.toggle("active", shouldShow);
  }

  function toggleSidebar(event) {
    if (event) event.stopPropagation();
    if (!sidebar) return;
    sidebar.classList.toggle("active");
    syncOverlay();
  }

  function closeSidebar() {
    if (!sidebar) return;
    sidebar.classList.remove("active");
    syncOverlay();
  }

  if (menuToggle && sidebar) {
    menuToggle.addEventListener("click", toggleSidebar);
  }

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener("click", function (event) {
      event.stopPropagation();
      closeSidebar();
    });
  }

  if (overlay) {
    overlay.addEventListener("click", closeSidebar);
  }

  navLinks.forEach(function (link) {
    link.addEventListener("click", closeSidebar);
  });

  if (sidebar) {
    sidebar.addEventListener("touchstart", function (event) {
      touchStartX = event.touches[0].clientX;
    }, { passive: true });

    sidebar.addEventListener("touchmove", function (event) {
      if (touchStartX === null) return;
      const deltaX = event.touches[0].clientX - touchStartX;
      if (deltaX < -60) closeSidebar();
    }, { passive: true });

    sidebar.addEventListener("touchend", function () {
      touchStartX = null;
    });
  }

  document.addEventListener("click", function (event) {
    if (!sidebar || !sidebar.classList.contains("active")) return;
    const clickedToggle = (menuToggle && menuToggle.contains(event.target)) || (sidebarToggle && sidebarToggle.contains(event.target));
    if (!sidebar.contains(event.target) && !clickedToggle) closeSidebar();
  });

  window.addEventListener("resize", syncOverlay);
  syncOverlay();
});
</script>';
}

function directory_render_footer(string $base): string
{
    return '<footer class="site-footer" id="site-footer" role="contentinfo"></footer>
<div class="footer">
<div>Copyright © 2023-2026 <strong><a href="https://bitcointalk.org/index.php?action=profile;u=2739424">NotATether</a></strong>.</div>
<div>Contact: admin [at] notatether [dot] com</div>
<div><em>Disclaimers:</em></div>
<div>Only mix funds you have obtained lawfully.</div>
<div>Do not use BitMixList for money laundering!</div>
<div style="font-size: 12px;">TOR: mixlistihakx3uexhl3wv7xxpnso75pl2fxxupulqz3gpoybum62puid.onion</div>
<hr/>
<div>Donate (Click to get addresses)</div>
<div class="donate">
<div><a href="bitcoin:bc1q6sac2xn46fwtv3jqtn606pewz8w6r7hlnwzgvp?amount=0.0001"><picture>
<source srcset="' . $base . 'wp-content/uploads/2023/12/bitcoin.webp" type="image/webp"/>
<img src="' . $base . 'wp-content/uploads/2023/12/bitcoin.jpg" title="Donate with Bitcoin" width="150px"/>
</picture></a></div>
<div><a href="monero:89m7X1HMSiY1jy175wSmsrZ6Bzmeo1DUPgVsxP2d9qcDMScoB9YgJmKBLWhQy72E4fiEysHY1rMuQUP965vsAwrU3ktAN1E?tx_amount=0.050000000000"><picture>
<source srcset="' . $base . 'wp-content/uploads/2023/12/monero.webp" type="image/webp"/>
<img src="' . $base . 'wp-content/uploads/2023/12/monero.jpg" title="Donate with Monero" width="150px"/>
</picture></a></div>
</div>
</div>';
}

function directory_escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
}

function directory_relative_to_root(string $fromFile): string
{
    $depth = substr_count(trim($fromFile, '/'), '/');
    return $depth === 0 ? '' : str_repeat('../', $depth);
}

function directory_relative_path(string $fromFile, string $toFile): string
{
    $fromParts = explode('/', trim($fromFile, '/'));
    array_pop($fromParts);
    $toParts = explode('/', trim($toFile, '/'));

    while ($fromParts !== [] && $toParts !== [] && $fromParts[0] === $toParts[0]) {
        array_shift($fromParts);
        array_shift($toParts);
    }

    return str_repeat('../', count($fromParts)) . implode('/', $toParts);
}
