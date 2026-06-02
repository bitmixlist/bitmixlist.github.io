<?php

declare(strict_types=1);

const DIRECTORY_ASSET_VERSION = '20260602-1';

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
    $notes = directory_entry_notes($entry, $locale);
    $config = trim($entry['config'][$locale] ?? '');
    $volumeHistory = is_array($entry['volume_history'] ?? null) ? $entry['volume_history'] : [];
    $verifier = directory_verifier_note($entry, $locale);
    $labels = directory_page_labels($locale);
    $headerTitle = directory_header_title($entry, $category, $locale);
    $headerSizes = directory_header_font_sizes($headerTitle);
    $statusStyles = (directory_entry_has_status($entry) ? directory_status_styles() : '')
        . (directory_entry_has_status_target($entry) ? directory_live_status_styles() : '');
    $actions = array_values(array_filter([
        directory_render_live_status_badge($entry, $locale),
        directory_render_external_action($entry, $locale, $external, $labels['visit']),
    ]));
    $actionsHtml = implode("\n\t\t", $actions);

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
' . directory_render_entry_verification_scripts($entry, $base) . '
<style>
.directory-page .site-header { display: block; min-height: 108px; padding-top: 1rem; padding-bottom: 0.95rem; }
.directory-page .site-content-wrapper { padding-top: 124px; }
.directory-page .header-inner { gap: 12px; min-height: 42px; align-items: center; position: relative; padding-right: 8rem; }
.directory-page .header-inner h4 { flex: 1 1 0; min-width: 0; font-size: var(--directory-header-title-size, 1.5rem); line-height: 1.2; font-weight: 650; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.directory-page .lang-switcher { position: absolute; top: 50%; right: 2rem; transform: translateY(-50%); margin-left: 0; flex: 0 0 auto; }
.directory-page .sidebar .nav-menu a { font-size: 1rem; line-height: 1.25; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
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
.directory-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; margin-top: 18px; }
.directory-button { display: inline-flex; align-items: center; justify-content: center; min-height: 38px; padding: 0 12px; border: 1px solid #7a61f6; border-radius: 7px; background: #1a1234; color: #f2ecff; text-decoration: none; font-weight: 650; }
.directory-button:hover, .directory-button:focus { background: #27184d; color: #fff; text-decoration: none; }
.directory-section { margin-top: 28px; }
.directory-section h2 { margin: 0 0 12px; font-size: 1.35rem; letter-spacing: 0; }
.directory-table-wrap { margin: 0; overflow-x: auto; }
.directory-facts { width: 100%; border-collapse: collapse; table-layout: fixed; }
.directory-facts th, .directory-facts td { vertical-align: top; padding: 12px; overflow-wrap: normal; word-break: normal; hyphens: none; }
.directory-facts th { color: #f6f2ff; text-align: left; background: #282238; }
.directory-facts td { background: #1c1728; }
.directory-facts .directory-nowrap { white-space: nowrap; overflow-wrap: normal; word-break: normal; }
.directory-entry-table { table-layout: auto; min-width: 620px; }
.directory-entry-table th:first-child, .directory-entry-table td:first-child { width: 34%; }
	.directory-entry-table tbody td:first-child { color: #f6f2ff; font-weight: 650; }
	.directory-entry-table thead th { font-size: 0.88rem; }
	' . directory_sort_styles() . '
	' . directory_coin_styles() . $statusStyles . '.directory-note { margin: 0; padding: 14px 16px; border: 1px solid #4a3a70; border-radius: 8px; background: #181222; line-height: 1.55; }
.directory-note > :first-child { margin-top: 0; }
.directory-note > :last-child { margin-bottom: 0; }
.directory-config { margin: 0; padding: 16px 18px; border: 1px solid #3a2e55; border-radius: 8px; background: #181222; line-height: 1.55; }
.directory-config > :first-child { margin-top: 0; }
.directory-config > :last-child { margin-bottom: 0; }
.directory-config ul { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 8px 18px; margin: 0; padding-left: 1.2rem; }
.directory-config li { margin: 0; }
	.directory-config-source { margin: 0 0 10px; color: #c9c3d8; font-size: 0.92rem; line-height: 1.45; }
	.directory-config-json { margin: 0; max-height: 540px; overflow: auto; padding: 14px 16px; border: 1px solid #3a2e55; border-radius: 8px; background: #0f0d16; color: #f6f2ff; line-height: 1.55; white-space: pre; }
	.directory-config-json code { font: 0.92rem/1.55 "IBM Plex Mono", ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace; }
	.directory-volume { padding: 16px 18px; border: 1px solid #2d2d2d; border-radius: 8px; background: #1a1a1a; color: #e0e0e0; box-shadow: 0 12px 30px rgba(0, 0, 0, 0.22); }
	.directory-volume-source { margin: 0 0 12px; color: #cfcfcf; font-size: 0.92rem; line-height: 1.45; }
	.directory-volume-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; margin: 0 0 14px; }
	.directory-volume-stat { padding: 10px 12px; border: 1px solid #333; border-radius: 7px; background: #121212; }
	.directory-volume-stat dt { margin: 0 0 4px; color: #bdbdbd; font-size: 0.78rem; font-weight: 650; text-transform: uppercase; letter-spacing: 0.04em; }
	.directory-volume-stat dd { margin: 0; color: #f4f4f4; font-weight: 700; line-height: 1.3; }
	.directory-volume-stat small { display: block; margin-top: 2px; color: #bdbdbd; font-weight: 500; }
	.directory-volume-chart { width: 100%; height: auto; margin: 2px 0 16px; border: 1px solid #333; border-radius: 7px; background: #101010; overflow: hidden; }
	.directory-volume-chart svg { display: block; width: 100%; height: auto; }
	.directory-volume-table-wrap { margin: 0; overflow-x: auto; }
	.directory-volume-table { width: 100%; border-collapse: collapse; }
	.directory-volume-table th, .directory-volume-table td { padding: 9px 10px; border-top: 1px solid #303030; text-align: left; white-space: nowrap; }
	.directory-volume-table th { color: #f1f1f1; background: #202020; font-size: 0.85rem; }
	.directory-volume-table td { background: #161616; }
	.directory-tool-grid { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 14px; }
.directory-verification-tool { flex: 1 1 calc(50% - 6px); }
.directory-tool-grid > .directory-verification-tool:only-child { flex-basis: 100%; }
.directory-inline-tool { min-width: 0; padding: 16px; border: 1px solid #2d2d2d; border-radius: 8px; background: #1a1a1a; color: #e0e0e0; box-shadow: 0 12px 30px rgba(0, 0, 0, 0.22); }
.directory-inline-tool h3 { margin: 0 0 8px; color: #f1f1f1; font-size: 1.05rem; font-weight: 650; letter-spacing: 0; }
.directory-inline-tool p { margin: 0 0 12px; color: #cfcfcf; line-height: 1.5; }
.directory-inline-tool label { display: block; margin: 0 0 6px; color: #d6d6d6; font-size: 0.9rem; font-weight: 650; }
.directory-inline-tool input, .directory-inline-tool textarea { width: 100%; box-sizing: border-box; border: 1px solid #3a3a3a; border-radius: 6px; background: #121212; color: #f4f4f4; padding: 10px 12px; font: inherit; }
.directory-inline-tool textarea { min-height: 168px; resize: vertical; font-family: var(--bitmixlist-font-mono, "IBM Plex Mono", ui-monospace, SFMono-Regular, Menlo, Consolas, monospace); font-size: 0.9rem; line-height: 1.45; }
.directory-inline-tool input:focus, .directory-inline-tool textarea:focus { border-color: #bb86fc; outline: 2px solid rgba(187, 134, 252, 0.18); outline-offset: 0; }
.directory-inline-actions { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; margin-top: 10px; }
.directory-inline-tool button { display: inline-flex; align-items: center; justify-content: center; min-height: 40px; padding: 0 12px; border: 1px solid #454545; border-radius: 6px; background: #2d2d2d; color: #f4f4f4; font-size: 0.9rem; font-weight: 650; line-height: 1.2; cursor: pointer; }
.directory-inline-tool button:hover, .directory-inline-tool button:focus { border-color: #bb86fc; background: #383838; color: #fff; outline: none; }
.directory-inline-tool .vg-status { margin: 0; padding: 10px 12px; border: 1px solid #3a3a3a; border-radius: 6px; background: #181818; color: #e5e5e5; line-height: 1.45; }
.directory-inline-tool .vg-status[hidden] { display: none; }
.directory-inline-tool .vg-status--success { border-color: #2f7d48; background: #14251b; color: #b8f7c8; }
.directory-inline-tool .vg-status--error { border-color: #8b2f36; background: #2a1417; color: #ffc4c8; }
.directory-inline-tool .vg-status--warning { border-color: #80652a; background: #261f12; color: #ffe4a3; }
.directory-inline-tool .vg-status--info { border-color: #3a3a3a; background: #181818; color: #e5e5e5; }
.directory-pgp-key { flex: 1 1 100%; grid-column: 1 / -1; }
.directory-pgp-key pre { max-height: 360px; margin: 0; padding: 12px; overflow: auto; border: 1px solid #303030; border-radius: 6px; background: #121212; color: #f4f4f4; font-family: var(--bitmixlist-font-mono, "IBM Plex Mono", ui-monospace, SFMono-Regular, Menlo, Consolas, monospace); font-size: 0.82rem; line-height: 1.42; white-space: pre-wrap; overflow-wrap: anywhere; }
' . directory_icon_styles() . '
.directory-footer-spacer { margin-top: 34px; }
@media (max-width: 700px) {
  .directory-page .site-header { min-height: 96px; padding-top: 0.65rem; padding-bottom: 0.55rem; }
  .directory-page .site-content-wrapper { padding-top: 104px; }
  .directory-page .header-inner { gap: 8px; min-height: 38px; padding-left: 0.5rem; padding-right: 4.25rem; }
  .directory-page .header-inner h4 { font-size: var(--directory-header-title-mobile-size, 1.15rem); }
  .directory-page .sidebar .nav-menu a { font-size: 1rem; }
  .directory-page .lang-switcher { right: 0.5rem; gap: 4px; }
  .directory-page .lang-link { gap: 0; padding: 3px 5px; }
  .directory-page .lang-link span { display: none; }
  .directory-meta-nav { justify-content: flex-start; gap: 16px; margin-top: 0.35rem; padding-left: 0.75rem; padding-right: 0.75rem; }
  .directory-meta-link { font-size: 0.84rem; }
  .directory-detail h1 { font-size: 2rem; }
  .directory-detail { padding: 22px 14px 36px; }
  .directory-hero { grid-template-columns: 1fr; }
  .directory-logo { width: 128px; height: 128px; }
  .directory-verification-tool, .directory-pgp-key { flex-basis: 100%; }
' . directory_mobile_table_card_styles('  ') . '
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
		' . $actionsHtml . '
		</div>
		</div>
		</section>' . directory_render_status_notice($entry, $locale) . '
		<section class="directory-section">
	<h2>' . directory_section_heading($labels['links'], 'links') . '</h2>
	<figure class="wp-block-table directory-table-wrap">
	<table class="directory-facts directory-entry-table">
	<thead><tr>' . directory_table_header($labels['table_field']) . directory_table_header($labels['table_value']) . '</tr></thead>
<tbody>
' . directory_entry_table_row($labels['clearnet'], directory_external_value($external)) . '
' . (directory_entry_has_status_target($entry) ? directory_entry_table_row($labels['live_status'], directory_render_live_status_badge($entry, $locale)) : '') . '
' . directory_entry_table_row($labels['tor'], directory_tor_value($tor)) . '
' . ($mirrors !== [] ? directory_entry_table_row($labels['mirrors'], directory_render_mirror_links($mirrors)) : '') . '
' . ($support !== '' ? directory_entry_table_row($labels['support'], directory_render_support_value($support, $supportHtml)) : '') . '
</tbody>
</table>
	</figure>
	</section>
	<section class="directory-section">
	<h2>' . directory_section_heading($labels['facts'], 'parameters') . '</h2>
	<figure class="wp-block-table directory-table-wrap">
	<table class="directory-facts directory-entry-table">
	<thead><tr>' . directory_table_header($labels['table_field']) . directory_table_header($labels['table_value']) . '</tr></thead>
<tbody>
' . directory_render_fact_rows($facts, $base) . '
</tbody>
</table>
	</figure>
</section>
' . directory_render_entry_config($config, $labels) . directory_render_volume_history($volumeHistory, $labels) . directory_render_entry_notes($notes, $labels) . '
<section class="directory-section">
<h2>' . directory_section_heading($labels['verification'], 'verification') . '</h2>
<p class="directory-note">' . directory_escape($verifier) . '</p>
' . directory_render_entry_verification_tools($entry, $locale) . '
</section>
<div class="directory-footer-spacer"></div>
</article>
</main>
' . directory_render_footer($base, $locale) . '
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
    $sectionCaution = trim($data['section_cautions'][$locale][$categorySlug] ?? '');
    $sectionLead = directory_render_optional_blocks([
        directory_render_section_cautions($sectionCaution),
        directory_render_mixer_fee_filter($entries, $categorySlug, $locale),
        directory_render_exchange_pair_filter($entries, $categorySlug, $locale, $base),
    ]);
    $statusStyles = (directory_entries_have_status($entries) ? directory_status_styles() : '')
        . (directory_entries_have_status_targets($entries) ? directory_live_status_styles() : '');
    $statusScopeAttr = directory_entries_have_status_targets($entries) ? ' data-directory-status-scope' : '';

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
.directory-page .site-header { display: block; min-height: 108px; padding-top: 1rem; padding-bottom: 0.95rem; }
.directory-page .site-content-wrapper { padding-top: 124px; }
.directory-page .header-inner { gap: 12px; min-height: 42px; align-items: center; position: relative; padding-right: 8rem; }
.directory-page .header-inner h4 { flex: 1 1 0; min-width: 0; font-size: var(--directory-header-title-size, 1.5rem); line-height: 1.2; font-weight: 650; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.directory-page .lang-switcher { position: absolute; top: 50%; right: 2rem; transform: translateY(-50%); margin-left: 0; flex: 0 0 auto; }
.directory-page .sidebar .nav-menu a { font-size: 1rem; line-height: 1.25; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
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
.directory-filter { margin: 22px 0 0; max-width: 560px; }
.directory-filter-label { display: block; margin: 0 0 6px; color: #f6f2ff; font-size: 0.95rem; font-weight: 650; }
.directory-filter-input { width: 100%; min-height: 40px; box-sizing: border-box; border: 1px solid #4a3a70; border-radius: 7px; background: #121018; color: #f6f2ff; padding: 0 12px; font: inherit; }
.directory-filter-input:focus { border-color: #8a6cff; outline: 2px solid rgba(138, 108, 255, 0.24); outline-offset: 2px; }
.directory-filter-empty { margin: 10px 0 0; color: #c9c3d8; }
' . directory_status_filter_styles() . '
.directory-pair-filter { padding: 16px 18px; border: 1px solid #2d2d2d; border-radius: 8px; background: #1a1a1a; box-shadow: 0 12px 30px rgba(0, 0, 0, 0.22); }
.directory-pair-filter h2 { margin-bottom: 14px; }
.directory-pair-filter__controls { display: grid; grid-template-columns: repeat(2, minmax(220px, 1fr)) auto; gap: 12px; align-items: start; }
.directory-pair-filter__control { display: grid; gap: 6px; color: #f6f2ff; font-size: 0.92rem; font-weight: 650; }
.directory-pair-filter__select { position: absolute; width: 1px; height: 1px; margin: -1px; padding: 0; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap; border: 0; }
.directory-pair-filter__select:focus { border-color: #8a6cff; outline: 2px solid rgba(138, 108, 255, 0.24); outline-offset: 2px; }
.directory-pair-filter__coin-grid { display: flex; flex-wrap: wrap; gap: 6px; }
.directory-pair-filter__coin { display: inline-flex; align-items: center; justify-content: center; gap: 6px; min-height: 36px; padding: 5px 8px; border: 1px solid #4a3a70; border-radius: 7px; background: #121018; color: #f6f2ff; font: inherit; font-size: 0.84rem; font-weight: 650; line-height: 1.15; cursor: pointer; }
.directory-pair-filter__coin:hover, .directory-pair-filter__coin:focus { border-color: #8a6cff; background: #1b1428; color: #fff; outline: none; }
.directory-pair-filter__coin.is-selected { border-color: #bb86fc; background: #2a1c41; color: #fff; }
.directory-pair-filter__coin img { display: block; flex: 0 0 20px; width: 20px; height: 20px; }
.directory-pair-filter__reset { min-height: 40px; padding: 0 13px; border: 1px solid #7a61f6; border-radius: 7px; background: #1a1234; color: #f2ecff; font: inherit; font-size: 0.9rem; font-weight: 650; cursor: pointer; }
.directory-pair-filter__reset:hover, .directory-pair-filter__reset:focus { background: #27184d; color: #fff; }
.directory-pair-filter__status, .directory-pair-filter__empty { margin: 12px 0 0; color: #d8d0e8; font-size: 0.92rem; line-height: 1.45; }
.directory-pair-filter__empty { color: #ffcfdf; }
.directory-fee-filter { padding: 16px 18px; border: 1px solid #2d2d2d; border-radius: 8px; background: #1a1a1a; box-shadow: 0 12px 30px rgba(0, 0, 0, 0.22); }
.directory-fee-filter h2 { margin-bottom: 14px; }
.directory-fee-filter__controls { display: grid; grid-template-columns: minmax(220px, 1fr) auto; gap: 12px; align-items: end; }
.directory-fee-filter__control { display: grid; gap: 8px; color: #f6f2ff; font-size: 0.92rem; font-weight: 650; }
.directory-fee-filter__label-row { display: flex; align-items: baseline; justify-content: space-between; gap: 12px; }
.directory-fee-filter__value { color: #c7b8ff; font-weight: 750; white-space: nowrap; }
.directory-fee-filter__range { width: 100%; accent-color: #bb86fc; }
.directory-fee-filter__reset { min-height: 40px; padding: 0 13px; border: 1px solid #7a61f6; border-radius: 7px; background: #1a1234; color: #f2ecff; font: inherit; font-size: 0.9rem; font-weight: 650; cursor: pointer; }
.directory-fee-filter__reset:hover, .directory-fee-filter__reset:focus { background: #27184d; color: #fff; }
.directory-fee-filter__status, .directory-fee-filter__empty { margin: 12px 0 0; color: #d8d0e8; font-size: 0.92rem; line-height: 1.45; }
.directory-fee-filter__empty { color: #ffcfdf; }
.directory-list { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 12px; }
.directory-list-card { display: grid; grid-template-columns: 128px minmax(0, 1fr); gap: 14px; min-width: 0; padding: 14px; border: 1px solid #3a2e55; border-radius: 8px; background: #181222; }
.directory-list-card .directory-logo { width: 128px; height: 128px; border-radius: 10px; object-fit: contain; }
.directory-list-card .directory-logo--text { font-size: 1.8rem; }
	.directory-list-title { margin: 0 0 4px; font-size: 1.05rem; line-height: 1.25; font-weight: 700; }
	.directory-list-title a { color: #f6f2ff; text-decoration: none; }
	.directory-list-title a:hover, .directory-list-title a:focus { text-decoration: underline; }
	.directory-list-summary { margin: 0; color: #d8d0e8; font-size: 0.92rem; line-height: 1.45; }
	' . directory_coin_styles() . $statusStyles . '.directory-list-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-top: 10px; }
.directory-button { display: inline-flex; align-items: center; justify-content: center; min-height: 34px; padding: 0 10px; border: 1px solid #7a61f6; border-radius: 7px; background: #1a1234; color: #f2ecff; text-decoration: none; font-size: 0.9rem; font-weight: 650; }
.directory-button:hover, .directory-button:focus { background: #27184d; color: #fff; text-decoration: none; }
.directory-icon-button { display: inline-flex; align-items: center; justify-content: center; flex: 0 0 auto; width: 34px; height: 34px; min-height: 34px; margin-top: 0; margin-left: auto; padding: 0; border: 1px solid #7a61f6; border-radius: 7px; background: #1a1234; color: #f2ecff; text-decoration: none !important; box-sizing: border-box; }
.directory-icon-button:hover, .directory-icon-button:focus { background: #27184d; color: #fff; text-decoration: none !important; }
.directory-icon-button svg { width: 17px; height: 17px; }
.tool-registry { max-width: none; margin: 28px 0 0; padding: 0; }
.tool-registry__header h2 { margin: 0 0 10px; font-size: 2rem; letter-spacing: 0; }
.tool-registry .muted { margin: 0 0 16px; color: #d8d0e8; line-height: 1.45; }
.tool-list { list-style: none; padding: 0; margin: 0; display: grid; gap: 12px; }
.tool { border: 1px solid rgba(255, 255, 255, 0.1); background: rgba(255, 255, 255, 0.03); border-radius: 16px; padding: 14px 14px 12px; }
.tool__header { display: flex; align-items: center; gap: 12px; }
.tool__header .tool__name { flex: 1 1 auto; min-width: 0; }
.tool__name { color: #f6f2ff; font-size: 1.05rem; font-weight: 700; text-decoration: none; }
.tool__name:hover, .tool__name:focus { color: #fff; text-decoration: underline; }
.tool__desc { margin: 8px 0 10px; color: #d8d0e8; line-height: 1.45; }
.tool__meta { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 0; }
.pill { padding: 4px 10px; border: 1px solid rgba(255, 255, 255, 0.14); border-radius: 999px; color: #e7def6; font-size: 0.82rem; line-height: 1.25; }
.directory-table-wrap { margin: 0; overflow-x: auto; }
.directory-facts { width: 100%; border-collapse: collapse; table-layout: fixed; }
.directory-facts th, .directory-facts td { vertical-align: top; padding: 12px; overflow-wrap: normal; word-break: normal; hyphens: none; }
.directory-facts th { color: #f6f2ff; text-align: left; background: #282238; }
.directory-facts td { background: #1c1728; }
.directory-facts .directory-nowrap { white-space: nowrap; overflow-wrap: normal; word-break: normal; }
.directory-comparison-table { table-layout: auto; min-width: 1040px; }
.directory-comparison-table--mixers { min-width: 1380px; }
.directory-comparison-table--mixers th.directory-coins-cell,
.directory-comparison-table--mixers td.directory-coins-cell { width: 12rem; max-width: 12rem; }
.directory-comparison-table--mixers td.directory-coins-cell .coin-list { max-width: 12rem; }
.directory-comparison-table--coordinators { min-width: 960px; }
' . directory_sort_styles() . '
.directory-section-cautions { margin-top: 22px; }
.directory-caution { margin: 0; padding: 16px 18px; border: 1px solid #2d2d2d; border-radius: 8px; background: #1a1a1a; color: #e0e0e0; box-shadow: 0 12px 30px rgba(0, 0, 0, 0.22); line-height: 1.55; }
.directory-caution + .directory-caution { margin-top: 12px; }
.directory-caution h3 { margin: 0 0 8px; color: #f2ecff; font-size: 1rem; letter-spacing: 0; }
.directory-caution p { margin: 0; }
.directory-caution a { color: #bb86fc; font-weight: 700; text-decoration: underline; text-underline-offset: 0.16em; }
' . directory_icon_styles() . '
@media (max-width: 700px) {
  .directory-page .site-header { min-height: 96px; padding-top: 0.65rem; padding-bottom: 0.55rem; }
  .directory-page .site-content-wrapper { padding-top: 104px; }
  .directory-page .header-inner { gap: 8px; min-height: 38px; padding-left: 0.5rem; padding-right: 4.25rem; }
  .directory-page .header-inner h4 { font-size: var(--directory-header-title-mobile-size, 1.15rem); }
  .directory-page .sidebar .nav-menu a { font-size: 1rem; }
	  .directory-page .lang-switcher { right: 0.5rem; gap: 4px; }
	  .directory-page .lang-link { gap: 0; padding: 3px 5px; }
	  .directory-page .lang-link span { display: none; }
	  .directory-meta-nav { justify-content: flex-start; gap: 16px; margin-top: 0.35rem; padding-left: 0.75rem; padding-right: 0.75rem; }
	  .directory-meta-link { font-size: 0.84rem; }
	  .directory-detail { padding: 22px 14px 36px; }
	  .directory-detail h1 { font-size: 2rem; }
	  .directory-fee-filter__controls { grid-template-columns: 1fr; }
	  .directory-fee-filter__reset { width: 100%; }
	  .directory-pair-filter__controls { grid-template-columns: 1fr; }
	  .directory-pair-filter__reset { width: 100%; }
  .directory-list { grid-template-columns: 1fr; }
' . directory_mobile_table_card_styles('  ') . '
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
<article class="page-content directory-detail" data-directory-filter-scope data-directory-pair-scope' . $statusScopeAttr . '>
		<nav class="directory-breadcrumb"><a href="' . directory_escape($home) . '">' . directory_escape($labels['home']) . '</a> / ' . directory_escape($category['title'][$locale]) . '</nav>
		<h1>' . directory_escape($category['title'][$locale]) . '</h1>
		<p class="directory-summary">' . directory_escape($description) . '</p>
' . $sectionLead . directory_render_section_entries_section($entries, $locale, $base, $outputPath, $category, $categorySlug) . '
	<section class="directory-section" data-directory-filter-scope>
	<h2>' . directory_section_heading($locale === 'ru' ? 'Таблица данных' : 'Data Sheet', 'data-sheet') . '</h2>
	' . directory_render_data_sheet_filter($categorySlug, $locale) . '
' . directory_render_section_table($entries, $locale, $base, $outputPath, $categorySlug) . '
</section>
</article>
</main>
' . directory_render_footer($base, $locale) . '
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
            'table_field' => 'Параметр',
            'table_value' => 'Значение',
            'clearnet' => 'Официальный сайт',
            'tor' => 'Tor-сайт',
            'mirrors' => 'Зеркала',
            'support' => 'Поддержка',
            'live_status' => 'Доступность',
            'status_checking' => 'Проверка',
            'status_online' => 'Онлайн',
            'status_offline' => 'Оффлайн',
            'status_unknown' => 'Неизвестно',
            'facts' => 'Параметры',
            'config' => 'Конфигурация координатора',
            'config_source' => 'Получено во время сборки из Wabisator config.json.',
            'volume_history' => 'История объема',
            'volume_source' => 'Получено во время сборки из Wabisator volumes_history.json.',
            'volume_total' => 'Общий объем',
            'volume_ath' => 'Дневной максимум',
            'volume_latest' => 'Последний день',
            'volume_average_30d' => 'Среднее за 30 дней',
            'volume_recent' => 'Последние дневные объемы',
            'volume_date' => 'Дата',
            'volume_btc' => 'Объем (BTC)',
            'volume_updated' => 'Обновлено',
            'notes' => 'Примечания',
            'verification' => 'Проверка',
            'domain_check_title' => 'Проверка официального домена',
            'domain_check_help' => 'Введите обычный или onion-домен и сравните его с официальными доменами на этой странице.',
            'domain_check_label' => 'Домен для проверки',
            'domain_check_placeholder' => 'example.com или адрес .onion',
            'domain_check_button' => 'Проверить домен',
            'letter_check_title' => 'Верификатор гарантийного письма',
            'letter_check_help' => 'Вставьте гарантийное письмо от этого сервиса.',
            'letter_check_label' => 'Гарантийное письмо',
            'letter_check_placeholder' => 'Вставьте гарантийное письмо...',
            'letter_check_button' => 'Проверить письмо',
            'pgp_key_title' => 'PGP-публичный ключ',
            'pgp_key_help' => 'Это PGP-публичный ключ сервиса. Его можно импортировать в другое приложение.',
        ];
    }

    return [
        'skip' => 'Skip to content',
        'menu' => 'Open menu',
        'home' => 'Home',
        'visit' => 'Visit official site',
        'links' => 'Links',
        'table_field' => 'Parameter',
        'table_value' => 'Value',
        'clearnet' => 'Official site',
        'tor' => 'Tor site',
        'mirrors' => 'Known mirrors',
        'support' => 'Support',
        'live_status' => 'Live status',
        'status_checking' => 'Checking',
        'status_online' => 'Online',
        'status_offline' => 'Offline',
        'status_unknown' => 'Unknown',
        'facts' => 'Parameters',
        'config' => 'Coordinator config',
        'config_source' => 'Fetched at build time from Wabisator config.json.',
        'volume_history' => 'Volume history',
        'volume_source' => 'Fetched at build time from Wabisator volumes_history.json.',
        'volume_total' => 'Total volume',
        'volume_ath' => 'All-time high',
        'volume_latest' => 'Latest day',
        'volume_average_30d' => '30-day average',
        'volume_recent' => 'Recent daily volume',
        'volume_date' => 'Date',
        'volume_btc' => 'Volume (BTC)',
        'volume_updated' => 'Updated',
        'notes' => 'Notes',
        'verification' => 'Verification',
        'domain_check_title' => 'Official domain check',
        'domain_check_help' => 'Enter a clearnet or onion domain and compare it with the official domains listed on this page.',
        'domain_check_label' => 'Domain to check',
        'domain_check_placeholder' => 'example.com or .onion address',
        'domain_check_button' => 'Check domain',
        'letter_check_title' => 'Letter of guarantee verifier',
        'letter_check_help' => 'Paste a letter of guarantee from this service.',
        'letter_check_label' => 'Letter of guarantee',
        'letter_check_placeholder' => 'Paste the letter of guarantee...',
        'letter_check_button' => 'Verify letter',
        'pgp_key_title' => 'PGP public key',
        'pgp_key_help' => 'This is the service\'s PGP public key if you want to import it into another application.',
    ];
}

function directory_render_nav_scripts(string $base): string
{
    return '<script src="' . directory_escape(directory_js_asset_url($base, 'wp-content/litespeed/js/ad-loader.js')) . '"></script>
<script src="' . directory_escape(directory_js_asset_url($base, 'wp-content/litespeed/js/site-search.js')) . '" defer></script>
<script src="' . directory_escape(directory_js_asset_url($base, 'wp-content/litespeed/js/directory-filter.js')) . '" defer></script>
<script src="' . directory_escape(directory_js_asset_url($base, 'wp-content/litespeed/js/site-status.js')) . '" data-status-url="' . directory_escape(directory_status_feed_url($base)) . '" defer></script>
<script>
document.addEventListener(\'DOMContentLoaded\', function () {
  if (window.bitmixlistLoadTopAd) {
    window.bitmixlistLoadTopAd();
  }
});
</script>';
}

function directory_render_entry_verification_scripts(array $entry, string $base): string
{
    if (($entry['type'] ?? 'service') !== 'service') {
        return '';
    }

    $scripts = '';
    if (directory_official_domains_for_entry($entry) !== []) {
        $scripts .= '<script src="' . directory_escape(directory_js_asset_url($base, 'wp-content/litespeed/js/scamwhammer.js')) . '" defer></script>' . "\n";
    }

    if (directory_letter_verifier_domain($entry) !== '') {
        $scripts .= '<script src="' . directory_escape(directory_js_asset_url($base, 'wp-content/litespeed/js/openpgp.min.js')) . '" defer></script>' . "\n"
            . '<script src="' . directory_escape(directory_js_asset_url($base, 'wp-content/litespeed/js/btc-message-verifier.min.js')) . '" defer></script>' . "\n"
            . '<script src="' . directory_escape(directory_js_asset_url($base, 'wp-content/litespeed/js/verifytool.js')) . '" defer></script>' . "\n";
    }

    return rtrim($scripts);
}

function directory_render_entry_verification_tools(array $entry, string $locale): string
{
    if (($entry['type'] ?? 'service') !== 'service') {
        return '';
    }

    $labels = directory_page_labels($locale);
    $tools = '';
    $name = $entry['content'][$locale]['name'] ?? $entry['content']['en']['name'] ?? '';
    $slug = $entry['slug'] ?? directory_slugify($name);
    $domains = directory_official_domains_for_entry($entry);

    if ($domains !== []) {
        $id = 'domain-check-' . preg_replace('/[^a-z0-9-]+/i', '-', $slug . '-' . $locale);
        $domainsJson = json_encode(array_values($domains), JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP);
        if (!is_string($domainsJson)) {
            $domainsJson = '[]';
        }

        $tools .= '<form class="directory-inline-tool directory-verification-tool" data-official-domains="' . directory_escape($domainsJson) . '" data-service-domain-check>
	<h3>' . directory_icon_label('domain-check', $labels['domain_check_title']) . '</h3>
	<p>' . directory_escape($labels['domain_check_help']) . '</p>
	<label for="' . directory_escape($id) . '">' . directory_escape($labels['domain_check_label']) . '</label>
	<input autocomplete="off" data-domain-input id="' . directory_escape($id) . '" placeholder="' . directory_escape($labels['domain_check_placeholder']) . '" type="search"/>
	<div class="directory-inline-actions">
	<button type="submit">' . directory_icon_label('filter', $labels['domain_check_button']) . '</button>
	<p class="vg-status" data-domain-status hidden></p>
	</div>
	</form>';
    }

    $verifierDomain = directory_letter_verifier_domain($entry);
    if ($verifierDomain !== '') {
        $id = 'letter-check-' . preg_replace('/[^a-z0-9-]+/i', '-', $slug . '-' . $locale);
        $tools .= '<form class="directory-inline-tool directory-verification-tool" data-letter-mixer="' . directory_escape($verifierDomain) . '" data-letter-verify-form>
	<h3>' . directory_icon_label('letter-check', $labels['letter_check_title']) . '</h3>
	<p>' . directory_escape($labels['letter_check_help']) . '</p>
	<label for="' . directory_escape($id) . '">' . directory_escape($labels['letter_check_label']) . '</label>
	<textarea data-letter-message id="' . directory_escape($id) . '" placeholder="' . directory_escape($labels['letter_check_placeholder']) . '"></textarea>
	<div class="directory-inline-actions">
	<button type="submit">' . directory_icon_label('verification', $labels['letter_check_button']) . '</button>
	<p class="vg-status" data-letter-status hidden></p>
	</div>
	</form>';
    }

    $pgpKey = directory_pgp_public_key_for_entry($entry);
    if ($pgpKey !== '') {
        $tools .= '<section class="directory-inline-tool directory-pgp-key" data-search-exclude>
	<h3>' . directory_icon_label('pgp-key', $labels['pgp_key_title']) . '</h3>
	<p>' . directory_escape($labels['pgp_key_help']) . '</p>
	<pre><code>' . directory_escape($pgpKey) . '</code></pre>
</section>';
    }

    if ($tools === '') {
        return '';
    }

    return '<div class="directory-tool-grid">
' . $tools . '
</div>';
}

function directory_official_domains_for_entry(array $entry): array
{
    $sources = [
        $entry['links']['clearnet'] ?? '',
        $entry['links']['tor'] ?? '',
    ];

    foreach ($entry['links']['mirrors'] ?? [] as $mirror) {
        $sources[] = $mirror['url'] ?? '';
    }

    $domains = [];
    foreach ($sources as $source) {
        $domain = directory_domain_for_check((string) $source);
        if ($domain === '' || directory_is_proxy_listing_domain($domain)) {
            continue;
        }

        $domains[$domain] = $domain;
    }

    return array_values($domains);
}

function directory_letter_verifier_domain(array $entry): string
{
    $details = directory_letter_verifier_details_for_entry($entry);
    return $details['domain'] ?? '';
}

function directory_letter_verifier_details_for_entry(array $entry): array
{
    $supported = directory_supported_letter_verifier_details();
    foreach (directory_official_domains_for_entry($entry) as $domain) {
        if (isset($supported[$domain])) {
            return ['domain' => $domain] + $supported[$domain];
        }
    }

    return [];
}

function directory_pgp_public_key_for_entry(array $entry): string
{
    $details = directory_letter_verifier_details_for_entry($entry);
    if (($details['type'] ?? '') !== 'pgp') {
        return '';
    }

    $keyIndex = $details['key_index'] ?? null;
    if (!is_int($keyIndex)) {
        return '';
    }

    $keys = directory_verifytool_public_pgp_keys();
    return $keys[$keyIndex] ?? '';
}

function directory_supported_letter_verifier_domains(): array
{
    return array_combine(
        array_keys(directory_supported_letter_verifier_details()),
        array_keys(directory_supported_letter_verifier_details())
    ) ?: [];
}

function directory_supported_letter_verifier_details(): array
{
    $jamblerPgp = [
        'mixer.money',
        'mixtum.io',
        'webmixer.io',
        'mixerdream.com',
        'thormixer.io',
        'mixy.money',
        'mixtura.money',
        'trustmixer.io',
        'bmix.io',
        'okmix.io',
        'dreadpirate.io',
        'bitxer.io',
    ];

    $details = [];
    foreach ($jamblerPgp as $domain) {
        $details[$domain] = ['type' => 'pgp', 'key_index' => 0];
    }

    foreach ([
        'jokermix.to' => 5,
        'b1exch.to' => 9,
        'zeusmix.to' => 8,
        'mixer.black' => 10,
        'mixtwix.io' => 11,
    ] as $domain => $keyIndex) {
        $details[$domain] = ['type' => 'pgp', 'key_index' => $keyIndex];
    }

    foreach ([
        'coinomize.biz',
        'anonymixer.com',
        'mixero.io',
        'genesismix.cx',
    ] as $domain) {
        $details[$domain] = ['type' => 'bitcoin', 'key_index' => null];
    }

    return $details;
}

function directory_verifytool_public_pgp_keys(): array
{
    static $keys = null;
    if ($keys !== null) {
        return $keys;
    }

    $path = dirname(__DIR__, 2) . '/wp-content/litespeed/js/verifytool.js';
    $source = is_file($path) ? file_get_contents($path) : false;
    if (!is_string($source)) {
        $keys = [];
        return $keys;
    }

    $keys = directory_parse_verifytool_public_pgp_keys($source);
    return $keys;
}

function directory_parse_verifytool_public_pgp_keys(string $source): array
{
    $start = strpos($source, 'const keys = [');
    if ($start === false) {
        return [];
    }

    $start = strpos($source, '[', $start);
    if ($start === false) {
        return [];
    }
    $start++;

    $end = strpos($source, '// Add other keys', $start);
    if ($end === false) {
        $end = strpos($source, '];', $start);
    }
    if ($end === false) {
        return [];
    }

    $body = substr($source, $start, $end - $start);
    $keys = [];
    $index = 0;
    $position = 0;
    $length = strlen($body);

    while ($position < $length) {
        $char = $body[$position];
        if (ctype_space($char) || $char === ',' || $char === ')') {
            $position++;
            continue;
        }

        if (substr_compare($body, 'cleanPGPKey', $position, strlen('cleanPGPKey')) === 0) {
            $open = strpos($body, '`', $position);
            if ($open === false) {
                break;
            }
            $close = strpos($body, '`', $open + 1);
            if ($close === false) {
                break;
            }

            $keys[$index] = directory_clean_pgp_key(substr($body, $open + 1, $close - $open - 1));
            $index++;
            $position = $close + 1;
            continue;
        }

        if ($char === '"' || $char === "'") {
            $quote = $char;
            $position++;
            while ($position < $length) {
                if ($body[$position] === '\\') {
                    $position += 2;
                    continue;
                }
                if ($body[$position] === $quote) {
                    $position++;
                    break;
                }
                $position++;
            }
            $index++;
            continue;
        }

        $position++;
    }

    return $keys;
}

function directory_clean_pgp_key(string $rawKey): string
{
    $lines = preg_split('/\R/u', $rawKey) ?: [];
    $lines = array_values(array_filter(array_map('trim', $lines), static fn (string $line): bool => $line !== ''));

    foreach ($lines as $index => $line) {
        if (str_contains($line, 'BEGIN PGP PUBLIC KEY BLOCK')) {
            $lines[$index] = $line . "\n";
        } elseif (str_contains($line, 'END PGP PUBLIC KEY BLOCK')) {
            $lines[$index] = "\n" . $line;
        }
    }

    return implode("\n", $lines);
}

function directory_domain_for_check(string $value): string
{
    $value = trim($value);
    if ($value === '' || $value === 'No') {
        return '';
    }

    $host = parse_url($value, PHP_URL_HOST);
    if (!is_string($host) || $host === '') {
        $host = parse_url('https://' . ltrim($value, '/'), PHP_URL_HOST);
    }

    if (!is_string($host) || $host === '') {
        if (preg_match('/([a-z0-9-]+\.onion)(?:[\/:?#]|$)/i', $value, $match) === 1) {
            $host = $match[1];
        } else {
            return '';
        }
    }

    $host = mb_strtolower($host, 'UTF-8');
    $host = preg_replace('/^www\./i', '', $host) ?? $host;
    return trim($host, '.');
}

function directory_is_proxy_listing_domain(string $domain): bool
{
    return in_array($domain, [
        'orangefren.com',
        'rnwis2whetqcj4oknksnc5l24jbh33nflunifff3xtjjonnoxu3ld6id.onion',
        'bitcointalk.org',
    ], true);
}

function directory_render_directory_filter(string $scopeId, array $category, string $locale): string
{
    $isTool = ($category['type'] ?? 'service') === 'tool';
    $label = $locale === 'ru'
        ? ($isTool ? 'Фильтр инструментов' : 'Фильтр сервисов')
        : ($isTool ? 'Filter tools' : 'Filter services');
    $placeholder = $locale === 'ru'
        ? 'Название, домен, параметр...'
        : 'Name, domain, parameter...';
    $empty = $locale === 'ru' ? 'Нет совпадающих записей.' : 'No matching entries.';
    $id = 'directory-filter-' . preg_replace('/[^a-z0-9-]+/i', '-', $scopeId);

    return '<div class="directory-filter">
	<label class="directory-filter-label" for="' . directory_escape($id) . '">' . directory_icon_label('filter', $label) . '</label>
	<input autocomplete="off" class="directory-filter-input" data-directory-filter-input id="' . directory_escape($id) . '" placeholder="' . directory_escape($placeholder) . '" type="search"/>
<p class="directory-filter-empty" data-directory-filter-empty hidden>' . directory_escape($empty) . '</p>
</div>';
}

function directory_render_status_filter(array $entries, string $locale, string $scopeId): string
{
    if (!directory_entries_have_status_targets($entries)) {
        return '';
    }

    $id = 'directory-status-filter-' . preg_replace('/[^a-z0-9-]+/i', '-', $scopeId . '-' . $locale);
    $legend = $locale === 'ru' ? 'Доступность' : 'Availability';
    $all = $locale === 'ru' ? 'Все сервисы' : 'All services';
    $online = $locale === 'ru' ? 'Только онлайн' : 'Online only';

    return '<fieldset class="directory-status-filter" data-directory-status-filter>
	<legend>' . directory_icon_label('activity', $legend) . '</legend>
	<div class="directory-status-filter-options">
	<label class="directory-status-filter-option" for="' . directory_escape($id) . '-all"><input checked id="' . directory_escape($id) . '-all" name="' . directory_escape($id) . '" type="radio" value="all"/><span>' . directory_escape($all) . '</span></label>
	<label class="directory-status-filter-option" for="' . directory_escape($id) . '-online"><input id="' . directory_escape($id) . '-online" name="' . directory_escape($id) . '" type="radio" value="online"/><span>' . directory_escape($online) . '</span></label>
	</div>
</fieldset>';
}

function directory_render_data_sheet_filter(string $scopeId, string $locale): string
{
    $label = $locale === 'ru' ? 'Фильтр таблицы' : 'Filter data sheet';
    $placeholder = $locale === 'ru' ? 'Название, домен, параметр...' : 'Name, domain, parameter...';
    $empty = $locale === 'ru' ? 'Нет совпадающих строк.' : 'No matching rows.';
    $id = 'directory-data-sheet-filter-' . preg_replace('/[^a-z0-9-]+/i', '-', $scopeId . '-' . $locale);

    return '<div class="directory-filter">
	<label class="directory-filter-label" for="' . directory_escape($id) . '">' . directory_icon_label('filter', $label) . '</label>
	<input autocomplete="off" class="directory-filter-input" data-directory-filter-input id="' . directory_escape($id) . '" placeholder="' . directory_escape($placeholder) . '" type="search"/>
	<p class="directory-filter-empty" data-directory-filter-empty hidden>' . directory_escape($empty) . '</p>
		</div>';
}

function directory_render_optional_blocks(array $blocks): string
{
    $html = '';
    foreach ($blocks as $block) {
        $block = trim((string) $block);
        if ($block !== '') {
            $html .= $block . "\n";
        }
    }

    return $html;
}

function directory_render_mixer_fee_filter(array $entries, string $categorySlug, string $locale): string
{
    if ($categorySlug !== 'mixers') {
        return '';
    }

    $fees = array_values(array_filter(
        array_map('directory_mixer_fee_max_percent', $entries),
        static fn (?float $fee): bool => $fee !== null
    ));
    if ($fees === []) {
        return '';
    }

    $max = max(1.0, ceil(max($fees) * 10) / 10);
    $maxValue = directory_format_fee_filter_number($max);
    $id = 'directory-fee-filter-' . preg_replace('/[^a-z0-9-]+/i', '-', $categorySlug . '-' . $locale);
    $labels = $locale === 'ru'
        ? [
            'title' => 'Фильтр комиссии',
            'label' => 'Максимальная процентная комиссия',
            'default' => 'Любая комиссия',
            'template' => '≤ {value}%',
            'reset' => 'Сбросить',
            'empty' => 'Нет миксеров с такой комиссией или ниже.',
            'count_prefix' => 'Подходящих миксеров:',
        ]
        : [
            'title' => 'Fee Filter',
            'label' => 'Maximum percentage fee',
            'default' => 'Any fee',
            'template' => '≤ {value}%',
            'reset' => 'Reset',
            'empty' => 'No mixers at or below that fee.',
            'count_prefix' => 'Matching mixers:',
        ];

    return '<section class="directory-section directory-fee-filter" data-directory-fee-count-prefix="' . directory_escape($labels['count_prefix']) . '" data-directory-fee-default-label="' . directory_escape($labels['default']) . '" data-directory-fee-filter data-directory-fee-value-template="' . directory_escape($labels['template']) . '">
	<h2>' . directory_section_heading($labels['title'], 'parameters') . '</h2>
	<div class="directory-fee-filter__controls">
	<label class="directory-fee-filter__control" for="' . directory_escape($id) . '"><span class="directory-fee-filter__label-row"><span>' . directory_escape($labels['label']) . '</span><output class="directory-fee-filter__value" data-directory-fee-output for="' . directory_escape($id) . '">' . directory_escape($labels['default']) . '</output></span><input class="directory-fee-filter__range" data-directory-fee-default="' . directory_escape($maxValue) . '" data-directory-fee-input id="' . directory_escape($id) . '" max="' . directory_escape($maxValue) . '" min="0" step="0.1" type="range" value="' . directory_escape($maxValue) . '"/></label>
	<button class="directory-fee-filter__reset" data-directory-fee-reset type="button">' . directory_escape($labels['reset']) . '</button>
	</div>
	<p class="directory-fee-filter__status" data-directory-fee-status hidden></p>
	<p class="directory-fee-filter__empty" data-directory-fee-empty hidden>' . directory_escape($labels['empty']) . '</p>
	</section>';
}

function directory_render_exchange_pair_filter(array $entries, string $categorySlug, string $locale, string $base): string
{
    if (!directory_pair_filter_enabled($categorySlug)) {
        return '';
    }

    $coins = directory_pair_filter_symbols($entries);
    if (count($coins) < 2) {
        return '';
    }

    $idBase = 'directory-pair-' . preg_replace('/[^a-z0-9-]+/i', '-', $categorySlug . '-' . $locale);
    $labels = $locale === 'ru'
        ? [
            'title' => 'Торговая пара',
            'send' => 'Отправляете',
            'receive' => 'Получаете',
            'any' => 'Любая монета',
            'reset' => 'Сбросить',
            'empty' => 'Нет совпадающих сервисов.',
            'count_prefix' => 'Совпадений:',
        ]
        : [
            'title' => 'Swap Pair',
            'send' => 'Send',
            'receive' => 'Receive',
            'any' => 'Any coin',
            'reset' => 'Reset',
            'empty' => 'No services support that pair.',
            'count_prefix' => 'Matching services:',
        ];
    $sendOptions = directory_render_pair_coin_options($coins, $labels['any']);
    $receiveOptions = $sendOptions;
    $sendButtons = directory_render_pair_coin_buttons($coins, $labels['any'], $base, 'send');
    $receiveButtons = directory_render_pair_coin_buttons($coins, $labels['any'], $base, 'receive');

    return '<section class="directory-section directory-pair-filter" data-directory-pair-count-prefix="' . directory_escape($labels['count_prefix']) . '" data-directory-pair-filter>
	<h2>' . directory_section_heading($labels['title'], 'swap') . '</h2>
	<div class="directory-pair-filter__controls">
	<div class="directory-pair-filter__control"><label for="' . directory_escape($idBase . '-send') . '">' . directory_escape($labels['send']) . '</label><select class="directory-pair-filter__select" data-directory-pair-send id="' . directory_escape($idBase . '-send') . '">' . $sendOptions . '</select>' . $sendButtons . '</div>
	<div class="directory-pair-filter__control"><label for="' . directory_escape($idBase . '-receive') . '">' . directory_escape($labels['receive']) . '</label><select class="directory-pair-filter__select" data-directory-pair-receive id="' . directory_escape($idBase . '-receive') . '">' . $receiveOptions . '</select>' . $receiveButtons . '</div>
	<button class="directory-pair-filter__reset" data-directory-pair-reset type="button">' . directory_escape($labels['reset']) . '</button>
	</div>
	<p class="directory-pair-filter__status" data-directory-pair-status hidden></p>
	<p class="directory-pair-filter__empty" data-directory-pair-empty hidden>' . directory_escape($labels['empty']) . '</p>
	</section>';
}

function directory_render_pair_coin_options(array $coins, string $emptyLabel): string
{
    $options = '<option value="">' . directory_escape($emptyLabel) . '</option>';
    foreach ($coins as $coin) {
        $token = directory_pair_coin_token($coin);
        $title = directory_pair_coin_title($coin);
        $label = directory_pair_coin_label($coin);
        $options .= '<option title="' . directory_escape($title) . '" value="' . directory_escape($token) . '">' . directory_escape($label) . '</option>';
    }

    return $options;
}

function directory_render_pair_coin_buttons(array $coins, string $emptyLabel, string $base, string $target): string
{
    $buttons = '<button class="directory-pair-filter__coin is-selected" data-directory-pair-choice data-directory-pair-target="' . directory_escape($target) . '" data-directory-pair-value="" type="button"><span>' . directory_escape($emptyLabel) . '</span></button>';
    foreach ($coins as $coin) {
        $token = directory_pair_coin_token($coin);
        $title = directory_pair_coin_title($coin);
        $label = directory_pair_coin_label($coin);
        $icon = directory_pair_coin_icon($coin);
        $src = $base . 'wp-content/uploads/crypto-icons/' . $icon . '.svg';
        $buttons .= '<button class="directory-pair-filter__coin" data-directory-pair-choice data-directory-pair-target="' . directory_escape($target) . '" data-directory-pair-value="' . directory_escape($token) . '" title="' . directory_escape($title) . '" type="button"><img alt="" aria-hidden="true" decoding="async" loading="lazy" src="' . directory_escape($src) . '"/><span>' . directory_escape($label) . '</span></button>';
    }

    return '<div class="directory-pair-filter__coin-grid" data-directory-pair-choice-group="' . directory_escape($target) . '">' . $buttons . '</div>';
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
        $links[] = '<a class="' . directory_escape($classes) . '" href="' . directory_escape($href) . '"' . $current . '>' . directory_icon_label(directory_category_icon($slug), $text) . '</a>';
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

function directory_render_section_entries_section(array $entries, string $locale, string $base, string $fromPath, array $category, string $categorySlug): string
{
    if (($category['type'] ?? 'service') === 'tool') {
        return directory_render_tool_registry_section($entries, $locale, $fromPath, $category, $categorySlug);
    }

	return directory_render_optional_blocks([
        directory_render_directory_filter($categorySlug, $category, $locale),
        directory_render_status_filter($entries, $locale, $categorySlug),
    ]) . '
	<section class="directory-section">
	<h2>' . directory_section_heading($locale === 'ru' ? 'Записи' : 'Entries', 'entries') . '</h2>
	<div class="directory-list">
' . directory_render_section_cards($entries, $locale, $base, $fromPath) . '
</div>
</section>';
}

function directory_render_tool_registry_section(array $entries, string $locale, string $fromPath, array $category, string $categorySlug): string
{
    $title = $category['index_label'][$locale] ?? $category['title'][$locale];

    return '<section class="directory-section tool-registry">
	<header class="tool-registry__header">
	<h2>' . directory_section_heading($title, directory_category_icon($categorySlug)) . '</h2>
	</header>
' . directory_render_directory_filter($categorySlug, $category, $locale) . '
' . directory_render_tool_registry_list($entries, $locale, $fromPath) . '
</section>';
}

function directory_render_tool_registry_list(array $entries, string $locale, string $fromPath): string
{
    $cards = '';
    foreach ($entries as $entry) {
        $cards .= directory_render_tool_registry_card($entry, $locale, $fromPath) . "\n";
    }

    return '<ul class="tool-list" id="toolList" role="list">
' . $cards . '</ul>';
}

function directory_render_tool_registry_card(array $entry, string $locale, string $fromPath): string
{
    $content = $entry['content'][$locale];
    $name = $content['name'];
    $summary = trim($content['summary'] ?? '');
    $entryHref = directory_relative_path($fromPath, $entry['output_paths'][$locale]);
    $external = $entry['links']['clearnet'] ?? '';
    $visitLabel = $locale === 'ru' ? 'Открыть проект' : 'Visit project';
    $tags = directory_tool_tags($entry, $locale);
    $dataTags = trim(implode(' ', array_merge($tags, [$name])));

    return '<li class="tool" data-directory-filter-item data-directory-filter-text="' . directory_escape(directory_filter_text_for_entry($entry, $locale, false)) . '" data-tags="' . directory_escape($dataTags) . '">
<div class="tool__header">
<a class="tool__name" href="' . directory_escape($entryHref) . '">' . directory_escape($name) . '</a>
' . ($external !== '' ? directory_external_icon_button($external, $visitLabel, 'tool-visit') : '') . '
</div>
' . ($summary !== '' ? '<p class="tool__desc">' . directory_escape($summary) . '</p>' : '') . '
' . ($tags !== [] ? '<div class="tool__meta">' . directory_render_pills($tags) . '</div>' : '') . '
</li>';
}

function directory_tool_tags(array $entry, string $locale): array
{
    foreach ($entry['facts'][$locale] ?? [] as $fact) {
        $label = mb_strtolower($fact['label'] ?? '', 'UTF-8');
        if (str_contains($label, 'tag') || str_contains($label, 'категор')) {
            return array_values(array_filter(array_map('trim', explode(',', $fact['value'] ?? ''))));
        }
    }

    return [];
}

function directory_render_pills(array $tags): string
{
    return implode('', array_map(
        static fn (string $tag): string => '<span class="pill">' . directory_escape($tag) . '</span>',
        $tags
    ));
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
    $isTool = ($entry['type'] ?? 'service') === 'tool';
    $visitLabel = $isTool
        ? ($locale === 'ru' ? 'Открыть проект' : 'Visit project')
        : ($locale === 'ru' ? 'Открыть сайт' : 'Visit site');
    $externalAction = directory_render_external_action($entry, $locale, $external, $visitLabel, $isTool, 'directory-list-external');
    $actions = array_values(array_filter([
        directory_render_live_status_badge($entry, $locale),
        '<a class="directory-button" href="' . directory_escape($entryHref) . '">' . directory_icon_label('details', $detailsLabel) . '</a>',
        $externalAction,
    ]));
    $actionsHtml = implode("\n\t\t", $actions);
    $status = directory_entry_status($entry);
    $statusClass = $status === [] ? '' : ' directory-list-card--' . directory_escape((string) ($status['type'] ?? 'status'));
    $pairAttributes = directory_pair_filter_item_attributes($entry, true);

    return '<article class="directory-list-card' . $statusClass . '" data-directory-filter-item data-directory-filter-text="' . directory_escape(directory_filter_text_for_entry($entry, $locale, false)) . '"' . directory_status_item_attributes($entry) . $pairAttributes . directory_fee_filter_item_attributes($entry) . '>
		<div class="directory-card-media">' . directory_logo_markup($entry, $base, $name) . directory_render_status_sign($entry, $locale) . '</div>
		<div>
	<h3 class="directory-list-title"><a href="' . directory_escape($entryHref) . '">' . directory_escape($name) . '</a></h3>' . directory_render_status_badge_line($entry, $locale, true, "\t\t") . '
		' . ($summary !== '' ? '<p class="directory-list-summary">' . directory_render_card_summary($summary, $base) . '</p>' : '') . '
		<div class="directory-list-actions">
		' . $actionsHtml . '
</div>
</div>
</article>';
}

function directory_render_section_table(array $entries, string $locale, string $base, string $fromPath, string $categorySlug): string
{
    $labels = directory_section_table_labels($entries, $locale, $categorySlug);
    $rows = '';

    foreach ($entries as $entry) {
        $rows .= directory_render_section_table_row($entry, $locale, $base, $fromPath, $labels['facts'], $labels['status'], $labels['live_status']) . "\n";
    }

    return '<figure class="wp-block-table directory-table-wrap">
		<table class="directory-facts directory-comparison-table directory-comparison-table--' . directory_escape($categorySlug) . '">
		<thead><tr>' . directory_table_header($labels['name']) . directory_render_status_header($labels['status']) . directory_render_status_header($labels['live_status']) . directory_table_header($labels['site']) . directory_table_header($labels['tor']) . directory_render_section_fact_headers($labels['facts']) . '</tr></thead>
	<tbody>
	' . $rows . '</tbody>
	</table>
</figure>';
}

function directory_section_table_labels(array $entries, string $locale, string $categorySlug): array
{
    $facts = [];
    $seen = [];
    $isTool = ($entries[0]['type'] ?? 'service') === 'tool';

    foreach ($entries as $entry) {
        foreach ($entry['facts'][$locale] ?? [] as $fact) {
            $label = trim($fact['label'] ?? '');
            if ($label === '') {
                continue;
            }
            if ($categorySlug === 'mixers' && directory_is_support_channel_header($label)) {
                continue;
            }
            if (directory_should_hide_section_fact($label, $categorySlug)) {
                continue;
            }

            $key = mb_strtolower($label, 'UTF-8');
            if ($isTool && (str_contains($key, 'project link') || str_contains($key, 'ссылка проекта'))) {
                continue;
            }

            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $facts[] = $label;
            }
        }
    }

    return [
        'name' => $locale === 'ru' ? 'Название' : 'Name',
        'status' => directory_entries_have_status($entries) ? ($locale === 'ru' ? 'Статус' : 'Status') : '',
        'live_status' => directory_entries_have_status_targets($entries) ? ($locale === 'ru' ? 'Доступность' : 'Live status') : '',
        'site' => $locale === 'ru' ? 'Веб-сайт' : 'Website',
        'tor' => $locale === 'ru' ? 'Tor-сайт' : 'Tor Site',
        'facts' => $facts,
    ];
}

function directory_should_hide_section_fact(string $label, string $categorySlug): bool
{
    if ($categorySlug !== 'mixers') {
        return false;
    }

    return in_array(mb_strtolower(trim($label), 'UTF-8'), ['resells', 'реселл'], true);
}

function directory_render_section_fact_headers(array $labels): string
{
    $headers = '';
    foreach ($labels as $label) {
        $headers .= directory_table_header($label);
    }

    return $headers;
}

function directory_render_section_table_row(array $entry, string $locale, string $base, string $fromPath, array $factLabels, string $statusLabel = '', string $liveStatusLabel = ''): string
{
    $display = directory_base_name($entry['table_display'][$locale] ?? $entry['content'][$locale]['name']);
    $entryHref = directory_relative_path($fromPath, $entry['output_paths'][$locale]);
    $external = $entry['links']['clearnet'] ?? '';
    $tor = $entry['links']['tor'] ?? 'No';
    $nameLabel = $locale === 'ru' ? 'Название' : 'Name';
    $siteLabel = $locale === 'ru' ? 'Веб-сайт' : 'Website';
    $torLabel = $locale === 'ru' ? 'Tor-сайт' : 'Tor Site';
    $factMap = [];

    foreach ($entry['facts'][$locale] ?? [] as $fact) {
        $factMap[mb_strtolower($fact['label'] ?? '', 'UTF-8')] = $fact;
    }

    $cells = directory_table_cell('<a class="directory-link" href="' . directory_escape($entryHref) . '">' . directory_escape($display) . '</a>', $nameLabel);
    if ($statusLabel !== '') {
        $cells .= directory_table_cell(directory_render_status_badge($entry, $locale, false), $statusLabel);
    }
    if ($liveStatusLabel !== '') {
        $cells .= directory_table_cell(directory_render_live_status_badge($entry, $locale), $liveStatusLabel);
    }
    $cells .= directory_table_cell(directory_table_external_value($external, $display), $siteLabel);
    $cells .= directory_table_cell(directory_table_tor_value($tor), $torLabel);

    foreach ($factLabels as $label) {
        $fact = $factMap[mb_strtolower($label, 'UTF-8')] ?? null;
        $cells .= directory_table_cell($fact ? directory_render_section_fact_value($fact, $base) : '', $label);
    }
    $pairAttributes = directory_pair_filter_item_attributes($entry, false);

    return '<tr data-directory-filter-item data-directory-filter-text="' . directory_escape(directory_filter_text_for_entry($entry, $locale, false)) . '"' . directory_status_item_attributes($entry) . $pairAttributes . directory_fee_filter_item_attributes($entry) . '>' . $cells . '</tr>';
}

function directory_fee_filter_item_attributes(array $entry): string
{
    if (($entry['category'] ?? '') !== 'mixers') {
        return '';
    }

    $fee = directory_mixer_fee_max_percent($entry);
    $value = $fee === null ? '' : directory_format_fee_filter_number($fee);

    return ' data-directory-fee-max="' . directory_escape($value) . '"';
}

function directory_mixer_fee_max_percent(array $entry): ?float
{
    foreach (['en', 'ru'] as $locale) {
        foreach ($entry['facts'][$locale] ?? [] as $fact) {
            $label = (string) ($fact['label'] ?? '');
            if (!directory_is_mixing_fee_fact_label_for_extract($label)) {
                continue;
            }

            return directory_max_percent_in_text((string) ($fact['value'] ?? ''));
        }
    }

    return null;
}

function directory_max_percent_in_text(string $value): ?float
{
    $value = str_replace(',', '.', $value);
    $value = str_replace(['−', '–', '—'], '-', $value);
    $matchCount = preg_match_all('/(?:\d+(?:\.\d+)?|\.\d+)(?=\s*%)/u', $value, $matches);
    if ($matchCount === false || $matchCount === 0 || ($matches[0] ?? []) === []) {
        return null;
    }

    $numbers = array_map(static fn (string $match): float => (float) $match, $matches[0]);
    return max($numbers);
}

function directory_format_fee_filter_number(float $value): string
{
    return rtrim(rtrim(number_format($value, 3, '.', ''), '0'), '.');
}

function directory_render_section_fact_value(array $fact, string $base): string
{
    if (directory_is_coin_fact_label((string) ($fact['label'] ?? ''))) {
        return directory_render_coin_value((string) ($fact['value'] ?? ''), $base);
    }

    if (directory_is_table_plain_fact_label((string) ($fact['label'] ?? ''))) {
        return directory_escape($fact['value'] ?? '');
    }

    $html = trim($fact['html'] ?? '');
    if ($html !== '') {
        return directory_normalize_inline_links($html);
    }

    return directory_escape($fact['value'] ?? '');
}

function directory_pair_filter_enabled(string $categorySlug): bool
{
    return in_array($categorySlug, ['neverkyc-exchanges', 'instant-exchanges'], true);
}

function directory_pair_filter_symbols(array $entries): array
{
    $symbols = [];
    foreach ($entries as $entry) {
        $support = directory_entry_pair_support($entry);
        foreach (directory_pair_support_symbols($support) as $symbol) {
            $symbols[$symbol] = true;
        }
    }

    $symbols = array_keys($symbols);
    usort($symbols, static fn (string $left, string $right): int => directory_pair_coin_sort_weight($left) <=> directory_pair_coin_sort_weight($right)
        ?: strcmp($left, $right));

    return $symbols;
}

function directory_entry_pair_support(array $entry): array
{
    $category = (string) ($entry['category'] ?? '');
    $slug = (string) ($entry['slug'] ?? '');
    $map = directory_exchange_pair_support_map();

    return $map[$category][$slug] ?? [];
}

function directory_exchange_pair_support_map(): array
{
    $stablecoins = ['USDT-ERC20', 'USDT-TRC20', 'USDT-BEP20', 'USDT-SPL', 'USDC-ERC20', 'USDC-BEP20', 'USDC-SPL'];
    $core = array_merge(['BTC', 'XMR', 'ETH', 'LTC', 'BNB', 'SOL', 'TRX'], $stablecoins);
    $privacyCore = ['BTC', 'XMR', 'ETH', 'LTC', 'USDT-ERC20', 'USDT-TRC20', 'USDC-ERC20'];
    $thorchainCore = ['BTC', 'ETH', 'LTC', 'BNB', 'USDT-ERC20', 'USDT-BEP20', 'USDC-ERC20', 'USDC-BEP20'];
    $atomicCore = ['BTC', 'XMR', 'LTC'];
    $btcXmr = [
        ['BTC', 'XMR'],
        ['XMR', 'BTC'],
    ];

    return [
        'neverkyc-exchanges' => [
            'b1exch' => ['coins' => $privacyCore],
            'crypton' => ['coins' => ['BTC', 'XMR', 'USDT-TRC20']],
            'dex-fo' => ['coins' => $privacyCore],
            'tomboi-io' => ['coins' => $core],
            'thorchainswap' => ['coins' => $thorchainCore],
            'bridgoro' => ['coins' => $core],
            'sageswap' => ['coins' => $privacyCore],
            'splash-tf' => ['pairs' => $btcXmr],
            'eigenwallet' => ['pairs' => $btcXmr],
            'basicswap-beta' => ['coins' => $atomicCore],
            'trevoid' => ['pairs' => $btcXmr],
        ],
        'instant-exchanges' => [
            'quickex' => ['coins' => $core],
            'cce-cash' => ['coins' => $core],
            'wizardswap' => ['coins' => $core],
            'trocador' => ['coins' => $core],
            'chainswap' => ['coins' => $core],
            'pegasusswap' => ['pairs' => $btcXmr],
            'exolix' => ['coins' => $core],
            'bitcoinvn' => ['coins' => ['BTC', 'XMR', 'ETH', 'LTC', 'USDT-ERC20', 'USDT-TRC20', 'USDC-ERC20']],
        ],
    ];
}

function directory_pair_filter_item_attributes(array $entry, bool $isResult): string
{
    $support = directory_entry_pair_support($entry);
    if ($support === []) {
        return '';
    }

    $attributes = ' data-directory-pair-item';
    if ($isResult) {
        $attributes .= ' data-directory-pair-result';
    }

    $coins = directory_pair_support_coins($support);
    if ($coins !== []) {
        $attributes .= ' data-directory-pair-coins="' . directory_escape(implode(' ', array_map('directory_pair_coin_token', $coins))) . '"';
    }

    $pairs = directory_pair_support_pairs($support);
    if ($pairs !== []) {
        $attributes .= ' data-directory-pairs="' . directory_escape(implode(' ', $pairs)) . '"';
    }

    return $attributes;
}

function directory_pair_support_symbols(array $support): array
{
    $symbols = [];
    foreach (directory_pair_support_coins($support) as $coin) {
        $symbols[$coin] = true;
    }
    foreach ($support['pairs'] ?? [] as $pair) {
        if (!is_array($pair) || count($pair) < 2) {
            continue;
        }
        $send = directory_pair_coin_symbol((string) $pair[0]);
        $receive = directory_pair_coin_symbol((string) $pair[1]);
        if ($send !== '') {
            $symbols[$send] = true;
        }
        if ($receive !== '') {
            $symbols[$receive] = true;
        }
    }

    return array_keys($symbols);
}

function directory_pair_support_coins(array $support): array
{
    $coins = [];
    foreach ($support['coins'] ?? [] as $coin) {
        $symbol = directory_pair_coin_symbol((string) $coin);
        if ($symbol !== '') {
            $coins[$symbol] = true;
        }
    }

    return array_keys($coins);
}

function directory_pair_support_pairs(array $support): array
{
    $pairs = [];
    foreach ($support['pairs'] ?? [] as $pair) {
        if (!is_array($pair) || count($pair) < 2) {
            continue;
        }
        $send = directory_pair_coin_token((string) $pair[0]);
        $receive = directory_pair_coin_token((string) $pair[1]);
        if ($send === '' || $receive === '' || $send === $receive) {
            continue;
        }
        $pairs[$send . '>' . $receive] = true;
    }

    return array_keys($pairs);
}

function directory_pair_coin_symbol(string $coin): string
{
    return strtoupper(trim($coin));
}

function directory_pair_coin_token(string $coin): string
{
    $token = strtolower(directory_pair_coin_symbol($coin));
    return preg_replace('/[^a-z0-9]+/', '-', $token) ?? '';
}

function directory_pair_coin_title(string $coin): string
{
    return directory_pair_coin_meta($coin)['title'];
}

function directory_pair_coin_label(string $coin): string
{
    return directory_pair_coin_meta($coin)['label'];
}

function directory_pair_coin_icon(string $coin): string
{
    return directory_pair_coin_meta($coin)['icon'];
}

function directory_pair_coin_meta(string $coin): array
{
    $symbol = directory_pair_coin_symbol($coin);
    $token = directory_pair_coin_token($symbol);
    $catalog = [
        'btc' => ['label' => 'BTC', 'title' => 'Bitcoin (BTC)', 'icon' => 'btc', 'weight' => 10],
        'xmr' => ['label' => 'XMR', 'title' => 'Monero (XMR)', 'icon' => 'xmr', 'weight' => 20],
        'eth' => ['label' => 'ETH', 'title' => 'Ethereum (ETH)', 'icon' => 'eth', 'weight' => 30],
        'ltc' => ['label' => 'LTC', 'title' => 'Litecoin (LTC)', 'icon' => 'ltc', 'weight' => 40],
        'usdt-erc20' => ['label' => 'USDT ERC-20', 'title' => 'Tether on Ethereum (USDT ERC-20)', 'icon' => 'usdt', 'weight' => 50],
        'usdt-trc20' => ['label' => 'USDT TRC-20', 'title' => 'Tether on TRON (USDT TRC-20)', 'icon' => 'usdt', 'weight' => 51],
        'usdt-bep20' => ['label' => 'USDT BEP-20', 'title' => 'Tether on BNB Smart Chain (USDT BEP-20)', 'icon' => 'usdt', 'weight' => 52],
        'usdt-spl' => ['label' => 'USDT SPL', 'title' => 'Tether on Solana (USDT SPL)', 'icon' => 'usdt', 'weight' => 53],
        'usdc-erc20' => ['label' => 'USDC ERC-20', 'title' => 'USD Coin on Ethereum (USDC ERC-20)', 'icon' => 'usdc', 'weight' => 60],
        'usdc-bep20' => ['label' => 'USDC BEP-20', 'title' => 'USD Coin on BNB Smart Chain (USDC BEP-20)', 'icon' => 'usdc', 'weight' => 61],
        'usdc-spl' => ['label' => 'USDC SPL', 'title' => 'USD Coin on Solana (USDC SPL)', 'icon' => 'usdc', 'weight' => 62],
        'bnb' => ['label' => 'BNB', 'title' => 'BNB (BNB)', 'icon' => 'bnb', 'weight' => 70],
        'sol' => ['label' => 'SOL', 'title' => 'Solana (SOL)', 'icon' => 'sol', 'weight' => 80],
        'trx' => ['label' => 'TRX', 'title' => 'TRON (TRX)', 'icon' => 'trx', 'weight' => 90],
    ];

    if (isset($catalog[$token])) {
        return $catalog[$token];
    }

    return [
        'label' => $symbol,
        'title' => $symbol,
        'icon' => $token,
        'weight' => 1000,
    ];
}

function directory_pair_coin_sort_weight(string $coin): int
{
    return directory_pair_coin_meta($coin)['weight'];
}

function directory_coin_styles(string $indent = ''): string
{
    $lines = [
        '.coin-list { display: flex; flex-wrap: wrap; align-items: center; gap: 6px; min-width: 0; }',
        '.coin-badge { display: inline-flex; align-items: center; gap: 5px; min-height: 26px; padding: 2px 7px 2px 4px; border: 1px solid rgba(255, 255, 255, 0.14); border-radius: 999px; background: #121018; color: #f6f2ff; font-size: 0.82rem; font-weight: 650; line-height: 1; white-space: nowrap; }',
        '.coin-badge img { display: block; flex: 0 0 20px; width: 20px; height: 20px; }',
        '.directory-facts th.directory-coins-cell { white-space: nowrap; overflow-wrap: normal; word-break: normal; }',
        '.directory-facts td.directory-coins-cell { white-space: normal; overflow-wrap: normal; word-break: normal; }',
        '.directory-facts td.directory-coins-cell .coin-list { max-width: 100%; }',
        '.directory-list-summary .coin-list { display: inline-flex; margin-left: 4px; vertical-align: middle; }',
    ];

    return $indent . implode("\n" . $indent, $lines) . "\n";
}

function directory_status_styles(string $indent = ''): string
{
    $lines = [
        '.directory-button--disabled, .directory-button--disabled:hover, .directory-button--disabled:focus { border-color: #5d5668; background: #24212c; color: #aaa2b8; cursor: not-allowed; filter: saturate(0.65); opacity: 0.78; pointer-events: none; text-decoration: none; }',
        '.directory-status-badge { display: inline-flex; align-items: center; gap: 5px; min-height: 26px; margin: 6px 0 2px; padding: 2px 8px; border: 1px solid #d49a24; border-radius: 999px; background: #2a1d08; color: #ffd88a; font-size: 0.78rem; font-weight: 750; line-height: 1.1; text-transform: uppercase; letter-spacing: 0.02em; }',
        '.directory-status-badge--scam-accusation { border-color: #b8444e; background: #2a1014; color: #ffc7cd; }',
        '.directory-status-badge .directory-icon { width: 0.95em; height: 0.95em; }',
        '.directory-list-card--maintenance { position: relative; border-color: #8a6b2b; background: linear-gradient(180deg, #1d1820, #181222); }',
        '.directory-list-card--scam-accusation { position: relative; border-color: #9d2f3b; background: linear-gradient(180deg, #251419, #181222); }',
        '.directory-card-media { position: relative; min-width: 0; width: 128px; max-width: 100%; }',
        '.directory-list-card--maintenance .directory-card-media a, .directory-list-card--scam-accusation .directory-card-media a { display: block; }',
        '.directory-list-card--maintenance .directory-card-media .directory-logo, .directory-list-card--scam-accusation .directory-card-media .directory-logo { opacity: 0.72; filter: grayscale(0.25) saturate(0.85) contrast(0.96); }',
        '.directory-list-card--maintenance .directory-card-media::after { content: ""; position: absolute; inset: 0; width: 128px; height: 128px; max-width: 100%; border-radius: 10px; background: repeating-linear-gradient(135deg, rgba(246, 184, 63, 0.34) 0 10px, rgba(27, 20, 16, 0.24) 10px 20px); opacity: 0.5; pointer-events: none; }',
        '.directory-list-card--scam-accusation .directory-card-media::after { content: ""; position: absolute; inset: 0; width: 128px; height: 128px; max-width: 100%; border-radius: 10px; background: repeating-linear-gradient(135deg, rgba(220, 54, 68, 0.34) 0 10px, rgba(27, 20, 22, 0.24) 10px 20px); opacity: 0.55; pointer-events: none; }',
        '.directory-card-status-sign { position: absolute; right: 6px; bottom: 6px; z-index: 1; display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; border: 2px solid #1c1406; border-radius: 6px; background: #f6b83f; color: #241400; transform: rotate(45deg); box-shadow: 0 4px 10px rgba(0, 0, 0, 0.35); }',
        '.directory-card-status-sign--scam-accusation { border-color: #24070b; background: #dc3644; color: #fff1f3; }',
        '.directory-card-status-sign .directory-icon { width: 18px; height: 18px; transform: rotate(-45deg); stroke-width: 2.4; }',
        '.directory-maintenance-notice { position: relative; display: grid; grid-template-columns: 76px minmax(0, 1fr); gap: 18px; margin: 24px 0 0; padding: 24px 22px 20px; overflow: hidden; border: 1px solid #9b7424; border-radius: 8px; background: #1b1510; box-shadow: 0 14px 34px rgba(0, 0, 0, 0.28); }',
        '.directory-maintenance-notice::before { content: ""; position: absolute; top: 0; left: 0; right: 0; height: 10px; background: repeating-linear-gradient(135deg, #f6b83f 0 14px, #1b1410 14px 28px); }',
        '.directory-maintenance-notice--scam-accusation { border-color: #a12f3b; background: #211014; }',
        '.directory-maintenance-notice--scam-accusation::before { background: repeating-linear-gradient(135deg, #dc3644 0 14px, #1d0d10 14px 28px); }',
        '.directory-maintenance-sign { display: flex; align-items: center; justify-content: center; width: 62px; height: 62px; margin-top: 6px; border: 3px solid #1c1406; border-radius: 8px; background: #f6b83f; color: #241400; transform: rotate(45deg); box-shadow: 0 6px 16px rgba(0, 0, 0, 0.35); }',
        '.directory-maintenance-sign--scam-accusation { border-color: #24070b; background: #dc3644; color: #fff1f3; }',
        '.directory-maintenance-sign .directory-icon { width: 32px; height: 32px; transform: rotate(-45deg); stroke-width: 2.4; }',
        '.directory-maintenance-body { min-width: 0; }',
        '.directory-maintenance-kicker { margin: 0 0 6px; color: #ffd88a; font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }',
        '.directory-maintenance-notice--scam-accusation .directory-maintenance-kicker { color: #ff9da7; }',
        '.directory-maintenance-notice h2 { margin: 0 0 8px; color: #fff3cf; font-size: 1.35rem; letter-spacing: 0; }',
        '.directory-maintenance-notice--scam-accusation h2 { color: #ffe8eb; }',
        '.directory-maintenance-lead { margin: 0 0 12px; color: #f2dfb8; line-height: 1.55; }',
        '.directory-maintenance-notice--scam-accusation .directory-maintenance-lead { color: #f5c8cd; }',
        '.directory-maintenance-list { margin: 0; padding-left: 1.1rem; color: #e8d4aa; line-height: 1.5; }',
        '.directory-maintenance-notice--scam-accusation .directory-maintenance-list { color: #edc3c8; }',
        '.directory-maintenance-source { margin: 12px 0 0; color: #cdbb92; font-size: 0.9rem; }',
        '.directory-maintenance-notice--scam-accusation .directory-maintenance-source { color: #d8a8ae; }',
        '.directory-maintenance-source a { color: #ffe2a4; text-decoration: underline; text-underline-offset: 0.16em; }',
        '.directory-maintenance-notice--scam-accusation .directory-maintenance-source a { color: #ffb7bf; }',
        '@media (max-width: 700px) { .directory-maintenance-notice { grid-template-columns: 1fr; padding: 22px 16px 18px; } .directory-maintenance-sign { width: 54px; height: 54px; margin: 2px 0 0; } }',
    ];

    return $indent . implode("\n" . $indent, $lines) . "\n";
}

function directory_live_status_styles(string $indent = ''): string
{
    $lines = [
        '.directory-site-status { display: inline-flex; align-items: center; gap: 6px; min-height: 28px; padding: 3px 9px; border: 1px solid #565068; border-radius: 999px; background: #17131f; color: #d6d0e4; font-size: 0.8rem; font-weight: 750; line-height: 1.1; white-space: nowrap; }',
        '.directory-site-status::before { content: ""; width: 0.58em; height: 0.58em; border-radius: 999px; background: #8b8498; box-shadow: 0 0 0 3px rgba(139, 132, 152, 0.16); }',
        '.directory-site-status[data-site-status-state="online"] { border-color: #2e8b57; background: #102019; color: #b9f6ce; }',
        '.directory-site-status[data-site-status-state="online"]::before { background: #40d477; box-shadow: 0 0 0 3px rgba(64, 212, 119, 0.18); }',
        '.directory-site-status[data-site-status-state="offline"] { border-color: #9b3844; background: #251216; color: #ffc4cc; }',
        '.directory-site-status[data-site-status-state="offline"]::before { background: #ff6374; box-shadow: 0 0 0 3px rgba(255, 99, 116, 0.18); }',
        '.directory-site-status[data-site-status-state="unknown"] { border-color: #6a617a; background: #181420; color: #d8d0e8; }',
        '.directory-site-status[hidden] { display: none; }',
    ];

    return $indent . implode("\n" . $indent, $lines) . "\n";
}

function directory_status_filter_styles(string $indent = ''): string
{
    $lines = [
        '.directory-status-filter { display: inline-flex; flex-wrap: wrap; align-items: center; gap: 10px; margin: 12px 0 0; padding: 0; border: 0; color: #f6f2ff; }',
        '.directory-status-filter legend { display: inline-flex; align-items: center; gap: 0.35rem; margin: 0; padding: 0; color: #f6f2ff; font-size: 0.95rem; font-weight: 650; }',
        '.directory-status-filter legend .directory-icon { color: #bb86fc; }',
        '.directory-status-filter-options { display: inline-flex; flex: 0 0 auto; overflow: hidden; border: 1px solid #4a3a70; border-radius: 7px; background: #121018; }',
        '.directory-status-filter-option { position: relative; display: inline-flex; align-items: center; min-height: 34px; cursor: pointer; }',
        '.directory-status-filter-option input { position: absolute; opacity: 0; pointer-events: none; }',
        '.directory-status-filter-option span { display: inline-flex; align-items: center; justify-content: center; min-height: 34px; padding: 0 11px; color: #d8d0e8; font-size: 0.88rem; font-weight: 650; line-height: 1.1; }',
        '.directory-status-filter-option + .directory-status-filter-option span { border-left: 1px solid #4a3a70; }',
        '.directory-status-filter-option input:checked + span { background: #2a1c41; color: #fff; }',
        '.directory-status-filter-option:focus-within span { outline: 2px solid rgba(138, 108, 255, 0.32); outline-offset: -2px; }',
    ];

    return $indent . implode("\n" . $indent, $lines) . "\n";
}

function directory_render_live_status_badge(array $entry, string $locale): string
{
    if (!directory_entry_has_status_target($entry)) {
        return '';
    }

    $labels = directory_page_labels($locale);
    $labelMap = [
        'checking' => $labels['status_checking'],
        'online' => $labels['status_online'],
        'offline' => $labels['status_offline'],
        'unknown' => $labels['status_unknown'],
    ];
    $json = json_encode($labelMap, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if (!is_string($json)) {
        $json = '{}';
    }

    return '<span class="directory-site-status" data-site-status-id="' . directory_escape(directory_live_status_id($entry)) . '" data-site-status-labels="' . directory_escape($json) . '" data-site-status-state="unknown"><span data-site-status-text>' . directory_escape($labels['status_checking']) . '</span></span>';
}

function directory_entry_has_status_target(array $entry): bool
{
    return directory_status_targets_for_entry($entry) !== [];
}

function directory_entries_have_status_targets(array $entries): bool
{
    foreach ($entries as $entry) {
        if (directory_entry_has_status_target($entry)) {
            return true;
        }
    }

    return false;
}

function directory_live_status_id(array $entry): string
{
    return (string) ($entry['category'] ?? '') . ':' . (string) ($entry['slug'] ?? '');
}

function directory_status_item_attributes(array $entry): string
{
    if (($entry['type'] ?? 'service') !== 'service') {
        return '';
    }

    $attributes = ' data-directory-status-item data-directory-status-state="unknown"';
    if (directory_entry_has_status_target($entry)) {
        $attributes .= ' data-directory-status-id="' . directory_escape(directory_live_status_id($entry)) . '"';
    }

    return $attributes;
}

function directory_status_feed_url(string $base): string
{
    $configured = trim((string) getenv('BITMIXLIST_STATUS_FEED_URL'));
    if ($configured !== '') {
        return $configured;
    }

    return $base . 'site-status.json';
}

function directory_status_targets_for_entry(array $entry): array
{
    if (($entry['type'] ?? 'service') !== 'service') {
        return [];
    }

    $override = directory_status_target_override($entry);
    $url = $override !== '' ? $override : (string) ($entry['links']['clearnet'] ?? '');
    $url = directory_normalize_status_target_url($url);
    if ($url === '') {
        return [];
    }

    $host = parse_url($url, PHP_URL_HOST);
    if (!is_string($host) || $host === '' || str_ends_with(strtolower($host), '.onion')) {
        return [];
    }
    if ($override === '' && directory_is_proxy_listing_domain($host)) {
        return [];
    }

    return [
        [
            'kind' => 'clearnet',
            'url' => $url,
            'source' => $override === '' ? 'listed' : 'override',
        ],
    ];
}

function directory_normalize_status_target_url(string $url): string
{
    $url = trim($url);
    if ($url === '' || $url === 'No') {
        return '';
    }
    if (!preg_match('~^https?://~i', $url)) {
        return '';
    }

    return $url;
}

function directory_status_target_override(array $entry): string
{
    $categorySlug = (string) ($entry['category'] ?? '');
    $slug = (string) ($entry['slug'] ?? '');
    $overrides = [
        'instant-exchanges' => [
            'quickex' => 'https://quickex.io',
            'wizardswap' => 'https://wizardswap.io',
            'pegasusswap' => 'https://pegasusswap.com',
            'exolix' => 'https://exolix.com',
            'bitcoinvn' => 'https://bitcoinvn.io',
        ],
    ];

    return $overrides[$categorySlug][$slug] ?? '';
}

function directory_status_target_manifest(array $data): array
{
    $targets = [];
    foreach ($data['entries'] ?? [] as $entry) {
        $entryTargets = directory_status_targets_for_entry($entry);
        if ($entryTargets === []) {
            continue;
        }

        $targets[] = [
            'id' => directory_live_status_id($entry),
            'category' => (string) ($entry['category'] ?? ''),
            'slug' => (string) ($entry['slug'] ?? ''),
            'name' => (string) ($entry['content']['en']['name'] ?? $entry['slug'] ?? ''),
            'targets' => $entryTargets,
        ];
    }

    return [
        'schema_version' => 1,
        'ttl_seconds' => 3600,
        'targets' => $targets,
    ];
}

function directory_status_targets_json(array $data): string
{
    $json = json_encode(directory_status_target_manifest($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if (!is_string($json)) {
        throw new RuntimeException('Unable to encode site status target manifest: ' . json_last_error_msg());
    }

    return $json . "\n";
}

function directory_write_status_targets(string $root, array $data): void
{
    $json = directory_status_targets_json($data);
    file_put_contents($root . '/site-status-targets.json', $json);
    if (is_dir($root . '/site-status-checker')) {
        file_put_contents($root . '/site-status-checker/site-status-targets.json', $json);
    }
}

function directory_entry_status(array $entry): array
{
    return is_array($entry['status'] ?? null) ? $entry['status'] : [];
}

function directory_entry_has_status(array $entry): bool
{
    return directory_entry_status($entry) !== [];
}

function directory_entries_have_status(array $entries): bool
{
    foreach ($entries as $entry) {
        if (directory_entry_has_status($entry)) {
            return true;
        }
    }

    return false;
}

function directory_status_text(array $status, string $key, string $locale): string
{
    $value = $status[$key] ?? '';
    if (!is_array($value)) {
        return trim((string) $value);
    }

    if (isset($value[$locale])) {
        return trim((string) $value[$locale]);
    }
    if (isset($value['en'])) {
        return trim((string) $value['en']);
    }

    foreach ($value as $candidate) {
        if (!is_array($candidate)) {
            return trim((string) $candidate);
        }
    }

    return '';
}

function directory_status_items(array $status, string $locale): array
{
    $items = $status['items'] ?? [];
    if (isset($items[$locale]) && is_array($items[$locale])) {
        return array_values(array_filter(array_map('trim', array_map('strval', $items[$locale]))));
    }
    if (isset($items['en']) && is_array($items['en'])) {
        return array_values(array_filter(array_map('trim', array_map('strval', $items['en']))));
    }

    return [];
}

function directory_render_status_badge(array $entry, string $locale, bool $withIcon = false): string
{
    $status = directory_entry_status($entry);
    if ($status === []) {
        return '';
    }

    $label = directory_status_text($status, 'label', $locale);
    if ($label === '') {
        return '';
    }

    $icon = $withIcon ? directory_icon('maintenance') : '';
    $type = preg_replace('/[^a-z0-9-]+/i', '-', (string) ($status['type'] ?? 'status')) ?: 'status';
    $class = 'directory-status-badge directory-status-badge--' . strtolower($type);

    return '<span class="' . directory_escape($class) . '">' . $icon . '<span>' . directory_escape($label) . '</span></span>';
}

function directory_render_status_badge_line(array $entry, string $locale, bool $withIcon, string $indent): string
{
    $badge = directory_render_status_badge($entry, $locale, $withIcon);
    if ($badge === '') {
        return '';
    }

    return "\n" . $indent . $badge;
}

function directory_render_status_sign(array $entry, string $locale): string
{
    $status = directory_entry_status($entry);
    if ($status === []) {
        return '';
    }

    $label = directory_status_text($status, 'label', $locale);
    $type = preg_replace('/[^a-z0-9-]+/i', '-', (string) ($status['type'] ?? 'status')) ?: 'status';
    $class = 'directory-card-status-sign directory-card-status-sign--' . strtolower($type);

    return '<span class="' . directory_escape($class) . '" title="' . directory_escape($label) . '" aria-label="' . directory_escape($label) . '">' . directory_icon('maintenance') . '</span>';
}

function directory_render_status_header(string $label): string
{
    return $label === '' ? '' : directory_table_header($label);
}

function directory_render_external_action(array $entry, string $locale, string $external, string $visitLabel, bool $isTool = false, string $class = ''): string
{
    if (directory_entry_has_status($entry)) {
        return directory_render_disabled_external_action($visitLabel, $class);
    }

    if ($external === '') {
        return '';
    }

    if ($isTool) {
        return directory_external_icon_button($external, $visitLabel, $class);
    }

    $classes = trim('directory-button ' . $class);

    return '<a class="' . directory_escape($classes) . '" href="' . directory_escape($external) . '" rel="noopener noreferrer" target="_blank">' . directory_icon_label('external-link', $visitLabel) . '</a>';
}

function directory_render_disabled_external_action(string $label, string $class = ''): string
{
    $label = trim($label);
    if ($label === '') {
        return '';
    }

    $classes = trim('directory-button directory-button--disabled ' . $class);

    return '<span class="' . directory_escape($classes) . '" aria-disabled="true">' . directory_icon_label('external-link', $label) . '</span>';
}

function directory_render_status_notice(array $entry, string $locale): string
{
    $status = directory_entry_status($entry);
    if ($status === []) {
        return '';
    }

    $label = directory_status_text($status, 'label', $locale);
    $title = directory_status_text($status, 'title', $locale);
    $lead = directory_status_text($status, 'lead', $locale);
    $items = directory_status_items($status, $locale);
    $source = directory_render_status_source($status, $locale);
    $type = preg_replace('/[^a-z0-9-]+/i', '-', (string) ($status['type'] ?? 'status')) ?: 'status';
    $noticeClass = 'directory-maintenance-notice directory-maintenance-notice--' . strtolower($type);
    $signClass = 'directory-maintenance-sign directory-maintenance-sign--' . strtolower($type);
    $list = '';
    foreach ($items as $item) {
        $list .= '<li>' . directory_escape($item) . '</li>';
    }

    return '
		<section class="' . directory_escape($noticeClass) . '" aria-labelledby="directory-maintenance-title">
	<div class="' . directory_escape($signClass) . '">' . directory_icon('maintenance') . '</div>
	<div class="directory-maintenance-body">
	<p class="directory-maintenance-kicker">' . directory_escape($label) . '</p>
	<h2 id="directory-maintenance-title">' . directory_escape($title) . '</h2>
	' . ($lead !== '' ? '<p class="directory-maintenance-lead">' . directory_escape($lead) . '</p>' : '') . '
	' . ($list !== '' ? '<ul class="directory-maintenance-list">' . $list . '</ul>' : '') . '
	' . $source . '
	</div>
	</section>';
}

function directory_render_status_source(array $status, string $locale): string
{
    $source = $status['source'] ?? [];
    if (!is_array($source)) {
        return '';
    }

    $url = trim((string) ($source['url'] ?? ''));
    $label = directory_status_text($source, 'label', $locale);
    if ($url === '' || $label === '') {
        return '';
    }

    $sourceLabel = $locale === 'ru' ? 'Источник' : 'Source';

    return '<p class="directory-maintenance-source">' . directory_escape($sourceLabel) . ': <a href="' . directory_escape($url) . '" rel="noopener noreferrer" target="_blank">' . directory_escape($label) . '</a></p>';
}

function directory_normalize_coin_style_rules(string $html): string
{
    $indentedBlock = '          .directory-facts th.directory-coins-cell { white-space: nowrap; overflow-wrap: normal; word-break: normal; }' . "\n"
        . '          .directory-facts td.directory-coins-cell { white-space: normal; overflow-wrap: normal; word-break: normal; }' . "\n"
        . '          .directory-facts td.directory-coins-cell .coin-list { max-width: 100%; }' . "\n";
    $unindentedBlock = '.directory-facts th.directory-coins-cell { white-space: nowrap; overflow-wrap: normal; word-break: normal; }' . "\n"
        . '.directory-facts td.directory-coins-cell { white-space: normal; overflow-wrap: normal; word-break: normal; }' . "\n"
        . '.directory-facts td.directory-coins-cell .coin-list { max-width: 100%; }' . "\n";

    if (str_contains($html, $indentedBlock) && str_contains($html, $unindentedBlock)) {
        $html = str_replace($unindentedBlock, '', $html);
    }

    foreach (['          ', ''] as $indent) {
        $old = $indent . '.directory-facts .directory-coins-cell { white-space: normal; overflow-wrap: anywhere; }';
        $new = $indent . '.directory-facts th.directory-coins-cell { white-space: nowrap; overflow-wrap: normal; word-break: normal; }' . "\n"
            . $indent . '.directory-facts td.directory-coins-cell { white-space: normal; overflow-wrap: normal; word-break: normal; }' . "\n";
        $html = preg_replace('~^' . preg_quote($old, '~') . '\R~m', $new, $html) ?? $html;
        $oldCoinList = $indent . '.directory-facts .directory-coins-cell .coin-list { max-width: 100%; }';
        $newCoinList = $indent . '.directory-facts td.directory-coins-cell .coin-list { max-width: 100%; }' . "\n";
        $html = preg_replace('~^' . preg_quote($oldCoinList, '~') . '\R~m', $newCoinList, $html) ?? $html;

        $coinImageRule = $indent . '.coin-badge img { display: block; flex: 0 0 20px; width: 20px; height: 20px; }';
        $coinTableRules = $indent . '.directory-facts th.directory-coins-cell { white-space: nowrap; overflow-wrap: normal; word-break: normal; }' . "\n"
            . $indent . '.directory-facts td.directory-coins-cell { white-space: normal; overflow-wrap: normal; word-break: normal; }' . "\n"
            . $indent . '.directory-facts td.directory-coins-cell .coin-list { max-width: 100%; }' . "\n";
        if (preg_match('~^' . preg_quote($coinImageRule, '~') . '$~m', $html) === 1 && !str_contains($html, $indent . '.directory-facts th.directory-coins-cell')) {
            $html = preg_replace('~^' . preg_quote($coinImageRule, '~') . '\R~m', $coinImageRule . "\n" . $coinTableRules, $html, 1) ?? $html;
        }
    }

    return $html;
}

function directory_normalize_table_wrap_style_rules(string $html): string
{
    $oldRules = [
        '.directory-facts th, .directory-facts td { vertical-align: top; padding: 12px; overflow-wrap: anywhere; word-break: normal; }',
        '.homepage-directory .directory-facts th, .homepage-directory .directory-facts td { vertical-align: top; padding: 12px; overflow-wrap: anywhere; word-break: normal; }',
    ];

    foreach (['          ', ''] as $indent) {
        foreach ($oldRules as $oldRule) {
            $old = $indent . $oldRule;
            $new = str_replace(
                'overflow-wrap: anywhere; word-break: normal;',
                'overflow-wrap: normal; word-break: normal; hyphens: none;',
                $old
            );
            $html = str_replace($old, $new, $html);
        }
    }

    return $html;
}

function directory_sort_styles(string $indent = ''): string
{
    $lines = [
        '.directory-facts thead th[data-sortable-column] { cursor: pointer; user-select: none; }',
        '.directory-sort-button { all: unset; display: inline-flex; align-items: center; justify-content: flex-start; gap: 6px; max-width: 100%; color: inherit; font: inherit; font-weight: inherit; line-height: inherit; cursor: pointer; }',
        '.directory-sort-label { min-width: 0; }',
        '.directory-sort-button:focus-visible { outline: 2px solid #bb86fc; outline-offset: 3px; border-radius: 4px; }',
        '.directory-sort-indicator { display: inline-flex; align-items: center; justify-content: center; flex: 0 0 0.8em; color: #c7b8ff; font-size: 0.78em; line-height: 1; text-align: center; }',
    ];

    return $indent . implode("\n" . $indent, $lines) . "\n";
}

function directory_normalize_sort_style_rules(string $html): string
{
    foreach (['          ', ''] as $indent) {
        $oldButton = $indent . '.directory-sort-button { all: unset; display: inline-flex; align-items: center; gap: 6px; color: inherit; font: inherit; font-weight: inherit; line-height: inherit; cursor: pointer; }';
        $newButton = $indent . '.directory-sort-button { all: unset; display: inline-flex; align-items: center; justify-content: flex-start; gap: 6px; max-width: 100%; color: inherit; font: inherit; font-weight: inherit; line-height: inherit; cursor: pointer; }';
        $html = str_replace($oldButton, $newButton, $html);

        $labelRule = $indent . '.directory-sort-label { min-width: 0; }';
        if (str_contains($html, $newButton) && !str_contains($html, $labelRule)) {
            $html = str_replace($newButton . "\n", $newButton . "\n" . $labelRule . "\n", $html);
        }

        $oldIndicator = $indent . '.directory-sort-indicator { flex: 0 0 0.8em; color: #c7b8ff; font-size: 0.78em; line-height: 1; text-align: center; }';
        $newIndicator = $indent . '.directory-sort-indicator { display: inline-flex; align-items: center; justify-content: center; flex: 0 0 0.8em; color: #c7b8ff; font-size: 0.78em; line-height: 1; text-align: center; }';
        $html = str_replace($oldIndicator, $newIndicator, $html);
    }

    return $html;
}

function directory_render_card_summary(string $summary, string $base): string
{
    if (preg_match('/^([^:]+):\s*(.+)$/u', $summary, $match) === 1 && directory_is_coin_fact_label($match[1])) {
        return directory_escape($match[1]) . ': ' . directory_render_coin_value($match[2], $base);
    }

    return directory_escape($summary);
}

function directory_is_coin_fact_label(string $label): bool
{
    $normalized = mb_strtolower(trim($label), 'UTF-8');
    return $normalized === 'coins'
        || $normalized === 'crypto'
        || str_contains($normalized, 'монет')
        || str_contains($normalized, 'криптовалют');
}

function directory_is_table_plain_fact_label(string $label): bool
{
    return false;
}

function directory_render_coin_value(string $value, string $base): string
{
    $value = trim(html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    if ($value === '') {
        return '';
    }

    $coins = directory_coin_items($value);
    if ($coins === []) {
        return directory_escape($value);
    }

    $badges = '';
    foreach ($coins as $coin) {
        $icon = $coin['icon'];
        $label = $coin['label'];
        $title = $coin['title'];
        $src = $base . 'wp-content/uploads/crypto-icons/' . $icon . '.svg';
        $badges .= '<span class="coin-badge" title="' . directory_escape($title) . '"><img alt="" aria-hidden="true" decoding="async" loading="lazy" src="' . directory_escape($src) . '"/><span class="coin-label">' . directory_escape($label) . '</span></span>';
    }

    return '<span class="coin-list" aria-label="' . directory_escape($value) . '">' . $badges . '</span>';
}

function directory_coin_items(string $value): array
{
    $normalized = mb_strtolower(trim($value), 'UTF-8');
    if ($normalized === 'many' || str_contains($normalized, 'много')) {
        return [[
            'icon' => 'many',
            'label' => str_contains($normalized, 'много') ? 'Много' : 'Many',
            'title' => str_contains($normalized, 'много') ? 'Много монет' : 'Many coins',
        ]];
    }

    $items = [];
    foreach (preg_split('/\s*,\s*/u', $value) ?: [] as $part) {
        $symbol = strtoupper(trim($part));
        if ($symbol === '') {
            continue;
        }

        $icon = strtolower($symbol);
        if ($icon === 'ln-btc') {
            $icon = 'ln-btc';
        }

        $titles = directory_coin_titles();
        if (!isset($titles[$icon])) {
            return [];
        }

        $items[] = [
            'icon' => $icon,
            'label' => $symbol,
            'title' => $titles[$icon] . ' (' . $symbol . ')',
        ];
    }

    return $items;
}

function directory_coin_titles(): array
{
    return [
        'btc' => 'Bitcoin',
        'ln-btc' => 'Lightning Bitcoin',
        'eth' => 'Ethereum',
        'ltc' => 'Litecoin',
        'trx' => 'TRON',
        'sol' => 'Solana',
        'usdt' => 'Tether',
        'bnb' => 'BNB',
        'usdc' => 'USD Coin',
        'xmr' => 'Monero',
    ];
}

function directory_icon_styles(string $indent = ''): string
{
    $lines = [
        '.directory-icon { display: inline-block; flex: 0 0 auto; width: 1em; height: 1em; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; vertical-align: -0.14em; }',
        '.directory-heading-icon { color: #bb86fc; margin-right: 0.42rem; }',
        '.directory-meta-link { display: inline-flex; align-items: center; gap: 0.38rem; }',
        '.directory-meta-link .directory-icon { width: 0.95rem; height: 0.95rem; color: #bb86fc; }',
        '.directory-button { gap: 0.42rem; }',
        '.directory-button .directory-icon { width: 1rem; height: 1rem; }',
        '.directory-icon-button .directory-icon { width: 17px; height: 17px; }',
        '.directory-section-link { display: inline-flex; align-items: center; gap: 0.35rem; }',
        '.directory-section-link .directory-icon { width: 0.95rem; height: 0.95rem; }',
        '.directory-filter-label .directory-icon { margin-right: 0.35rem; color: #bb86fc; }',
        '.directory-inline-tool h3 { display: flex; align-items: center; gap: 0.45rem; }',
        '.directory-inline-tool h3 .directory-icon { color: #bb86fc; }',
        '.directory-inline-tool button { gap: 0.42rem; }',
    ];

    return $indent . implode("\n" . $indent, $lines) . "\n";
}

function directory_category_icon(string $categorySlug): string
{
    return [
        'mixers' => 'mixers',
        'neverkyc-exchanges' => 'shield-check',
        'instant-exchanges' => 'instant',
        'p2p-markets' => 'users',
        'coordinators' => 'network',
        'privacy-tools' => 'lock',
    ][$categorySlug] ?? 'entries';
}

function directory_section_heading(string $label, string $icon): string
{
    return directory_icon($icon, 'directory-heading-icon') . '<span>' . directory_escape($label) . '</span>';
}

function directory_icon_label(string $icon, string $label): string
{
    return directory_icon($icon) . '<span>' . directory_escape($label) . '</span>';
}

function directory_icon(string $name, string $class = ''): string
{
    $paths = directory_icon_paths();
    if (!isset($paths[$name])) {
        return '';
    }

    $classes = trim('directory-icon ' . $class);

    return '<svg aria-hidden="true" class="' . directory_escape($classes) . '" focusable="false" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">' . $paths[$name] . '</svg>';
}

function directory_icon_paths(): array
{
    return [
        'activity' => '<path d="M22 12h-4l-3 8-6-16-3 8H2"></path>',
        'arrow-right' => '<path d="M5 12h14"></path><path d="m13 6 6 6-6 6"></path>',
        'config' => '<path d="M8 3 4 7l4 4"></path><path d="m16 3 4 4-4 4"></path><path d="m14 4-4 16"></path>',
        'data-sheet' => '<rect x="3" y="4" width="18" height="16" rx="2"></rect><path d="M3 10h18"></path><path d="M9 4v16"></path><path d="M15 4v16"></path>',
        'details' => '<path d="M8 6h13"></path><path d="M8 12h13"></path><path d="M8 18h13"></path><path d="M3 6h.01"></path><path d="M3 12h.01"></path><path d="M3 18h.01"></path>',
        'domain-check' => '<circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path><path d="m8.5 11 1.8 1.8 3.5-3.6"></path>',
        'entries' => '<rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect><rect x="14" y="14" width="7" height="7" rx="1"></rect>',
        'external-link' => '<path d="M15 3h6v6"></path><path d="M10 14 21 3"></path><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>',
        'filter' => '<circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path>',
        'instant' => '<path d="M13 2 4 14h7l-1 8 9-12h-7l1-8z"></path>',
        'letter-check' => '<path d="M6 3h9l5 5v13H6z"></path><path d="M14 3v6h6"></path><path d="m9 15 2 2 4-4"></path>',
        'links' => '<circle cx="12" cy="12" r="9"></circle><path d="M3 12h18"></path><path d="M12 3c3 3 3 15 0 18"></path><path d="M12 3c-3 3-3 15 0 18"></path>',
        'lock' => '<rect x="4" y="11" width="16" height="10" rx="2"></rect><path d="M8 11V7a4 4 0 0 1 8 0v4"></path>',
        'maintenance' => '<path d="M10.6 2.6 2.6 10.6a2 2 0 0 0 0 2.8l8 8a2 2 0 0 0 2.8 0l8-8a2 2 0 0 0 0-2.8l-8-8a2 2 0 0 0-2.8 0z"></path><path d="M12 8v5"></path><path d="M12 16h.01"></path>',
        'mixers' => '<path d="M16 3h5v5"></path><path d="M4 20 21 3"></path><path d="M21 16v5h-5"></path><path d="m15 15 6 6"></path><path d="M4 4l5 5"></path>',
        'network' => '<circle cx="6" cy="6" r="3"></circle><circle cx="18" cy="6" r="3"></circle><circle cx="12" cy="18" r="3"></circle><path d="m8.4 8.1 3.2 7.8"></path><path d="m15.6 8.1-3.2 7.8"></path><path d="M9 6h6"></path>',
        'notes' => '<path d="M6 3h9l5 5v13H6z"></path><path d="M14 3v6h6"></path><path d="M9 13h6"></path><path d="M9 17h4"></path>',
        'parameters' => '<path d="M4 6h10"></path><path d="M18 6h2"></path><circle cx="16" cy="6" r="2"></circle><path d="M4 12h3"></path><path d="M11 12h9"></path><circle cx="9" cy="12" r="2"></circle><path d="M4 18h12"></path><path d="M20 18h.01"></path><circle cx="18" cy="18" r="2"></circle>',
        'pgp-key' => '<circle cx="7.5" cy="14.5" r="4.5"></circle><path d="M11 11 21 1"></path><path d="m16 6 2 2"></path><path d="m19 3 2 2"></path>',
        'shield-check' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="m9 12 2 2 4-4"></path>',
        'swap' => '<path d="M7 7h11"></path><path d="m14 3 4 4-4 4"></path><path d="M17 17H6"></path><path d="m10 21-4-4 4-4"></path>',
        'users' => '<circle cx="9" cy="8" r="4"></circle><path d="M2 21a7 7 0 0 1 14 0"></path><path d="M17 11a4 4 0 0 0 0-6"></path><path d="M22 21a7 7 0 0 0-5-6.7"></path>',
        'verification' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="m9 12 2 2 4-4"></path>',
        'volume' => '<path d="M3 19h18"></path><path d="M5 16 10 9l4 4 5-8"></path><path d="M19 5v5h-5"></path>',
    ];
}

function directory_external_icon_button(string $href, string $label, string $class = ''): string
{
    $classes = trim('directory-icon-button ' . $class);

    return '<a aria-label="' . directory_escape($label) . '" class="' . directory_escape($classes) . '" href="' . directory_escape($href) . '" rel="noopener noreferrer" target="_blank" title="' . directory_escape($label) . '">'
        . directory_external_icon_svg()
        . '<span class="screen-reader-text">' . directory_escape($label) . '</span>'
        . '</a>';
}

function directory_external_icon_svg(): string
{
    return directory_icon('external-link');
}

function directory_filter_text_for_entry(array $entry, string $locale, bool $includeSupport = true): string
{
    $parts = [
        $entry['content'][$locale]['name'] ?? '',
        $entry['content'][$locale]['summary'] ?? '',
        $entry['links']['clearnet'] ?? '',
        $entry['links']['tor'] ?? '',
    ];

    if ($includeSupport) {
        $parts[] = $entry['links']['support'] ?? '';
    }

    $status = directory_entry_status($entry);
    if ($status !== []) {
        $parts[] = directory_status_text($status, 'label', $locale);
        $parts[] = directory_status_text($status, 'title', $locale);
        $parts[] = directory_status_text($status, 'lead', $locale);
        $parts = array_merge($parts, directory_status_items($status, $locale));
    }

    foreach ($entry['facts'][$locale] ?? [] as $fact) {
        $parts[] = $fact['label'] ?? '';
        $parts[] = $fact['value'] ?? '';
    }

    return trim(preg_replace('/\s+/u', ' ', implode(' ', array_filter($parts))) ?? '');
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

function directory_render_fact_rows(array $facts, string $base): string
{
    $rows = '';
    foreach ($facts as $fact) {
        $value = trim($fact['value'] ?? '');
        if ($value === '') {
            continue;
        }
        $label = (string) ($fact['label'] ?? '');
        $rendered = directory_render_entry_fact_value($fact, $base);
        $rows .= directory_entry_table_row($label, $rendered) . "\n";
    }

    return $rows;
}

function directory_render_entry_fact_value(array $fact, string $base): string
{
    if (directory_is_coin_fact_label((string) ($fact['label'] ?? ''))) {
        return directory_render_coin_value((string) ($fact['value'] ?? ''), $base);
    }

    $html = trim($fact['html'] ?? '');
    if ($html !== '') {
        return directory_normalize_inline_links($html);
    }

    return directory_escape($fact['value'] ?? '');
}

function directory_entry_table_row(string $label, string $valueHtml): string
{
    return '<tr>'
        . directory_table_cell(directory_escape($label), $label)
        . directory_table_cell($valueHtml, $label)
        . '</tr>';
}

function directory_render_entry_notes(string $notes, array $labels): string
{
    $notes = trim($notes);
    if ($notes === '') {
        return '';
    }

    return '<section class="directory-section directory-entry-notes">
	<h2>' . directory_section_heading($labels['notes'], 'notes') . '</h2>
	<div class="directory-note">' . directory_normalize_inline_links($notes) . '</div>
	</section>';
}

function directory_entry_notes(array $entry, string $locale): string
{
    $notes = trim($entry['notes'][$locale] ?? '');
    $generatedNote = directory_generated_entry_note($entry, $locale);

    if ($generatedNote === '') {
        return $notes;
    }

    return trim($notes . "\n" . $generatedNote);
}

function directory_generated_entry_note(array $entry, string $locale): string
{
    if (($entry['category'] ?? '') !== 'instant-exchanges') {
        return '';
    }

    $slug = $entry['slug'] ?? '';
    if ($slug === 'chainswap') {
        return '';
    }

    if ($slug === 'trocador') {
        if ($locale === 'ru') {
            return '<p data-generated-entry-note="instant-trocador-guarantee">Trocador не покрывается гарантией Orangefren. У Trocador есть собственная система гарантий для обменов, маршрутизируемых через его платформу. Перед отправкой средств изучите условия гарантии Trocador.</p>';
        }

        return '<p data-generated-entry-note="instant-trocador-guarantee">Trocador is not covered by the Orangefren Guarantee. Trocador has its own guarantee system for swaps routed through its platform. Review Trocador\'s guarantee terms before sending funds.</p>';
    }

    if ($locale === 'ru') {
        return '<p data-generated-entry-note="instant-orangefren-guarantee">Эти обменники предлагают больше монет и ликвидности, но в редких случаях могут подпадать под KYC/AML. Обмены, совершенные через ссылки Orangefren на этой странице, покрываются <a href="https://orangefren.com" rel="noopener noreferrer">гарантией Orangefren</a>. Гарантия действует только если вы начинаете обмен, перейдя по ссылке Orangefren на этой странице; прямые переходы на сайт сервиса не покрываются Orangefren. Сервисы с гарантией сначала ведут на сайт Orangefren, а затем перенаправляют вас к самому сервису. Фактические URL сервиса указаны в разделе ссылок выше. Действуют условия и положения. Подробнее — на сайте Orangefren.</p>';
    }

    return '<p data-generated-entry-note="instant-orangefren-guarantee">These exchangers have more coins and liquidity, but in rare circumstances are subject to KYC/AML. Exchanges made through Orangefren links on this page are covered by the <a href="https://orangefren.com" rel="noopener noreferrer">Orangefren Guarantee</a>. The guarantee applies only if you start the swap by clicking the Orangefren link on this page; direct visits to the service are not covered by Orangefren. Guaranteed services link to Orangefren\'s site before redirecting you to the service. The actual service URLs are listed in the links section above. Terms and conditions apply. Check the Orangefren website for more details.</p>';
}

function directory_render_entry_config(string $config, array $labels): string
{
    $config = trim($config);
    if ($config === '') {
        return '';
    }

    if (directory_is_json_config($config)) {
        return '<section class="directory-section directory-entry-config">
	<h2>' . directory_section_heading($labels['config'], 'config') . '</h2>
	<p class="directory-config-source">' . directory_escape($labels['config_source']) . ' <a href="' . directory_escape(DIRECTORY_WABISATOR_CONFIG_URL) . '" rel="noopener noreferrer" target="_blank">Wabisator config.json</a></p>
	<pre class="directory-config-json"><code>' . directory_escape($config) . '</code></pre>
	</section>';
    }

    return '<section class="directory-section directory-entry-config">
	<h2>' . directory_section_heading($labels['config'], 'config') . '</h2>
	<div class="directory-config">' . directory_normalize_inline_links($config) . '</div>
		</section>';
}

function directory_render_volume_history(array $history, array $labels): string
{
    $daily = $history['daily'] ?? [];
    if (!is_array($daily) || $daily === []) {
        return '';
    }

    $last = $daily[array_key_last($daily)];
    $recentRows = array_reverse(array_slice($daily, -30));
    $averageRows = array_slice($daily, -30);
    $average = count($averageRows) > 0
        ? array_sum(array_map(static fn (array $row): float => (float)$row['volume'], $averageRows)) / count($averageRows)
        : 0.0;
    $ath = is_array($history['ath'] ?? null) ? $history['ath'] : [];
    $updated = directory_format_volume_updated((string)($history['updated_at'] ?? ''));

    $source = '<p class="directory-volume-source">'
        . directory_escape($labels['volume_source']) . ' '
        . '<a href="' . directory_escape(DIRECTORY_WABISATOR_VOLUME_HISTORY_URL) . '" rel="noopener noreferrer" target="_blank">Wabisator volumes_history.json</a>'
        . ($updated !== '' ? ' ' . directory_escape($labels['volume_updated']) . ': ' . directory_escape($updated) . '.' : '')
        . '</p>';

    $rows = '';
    foreach ($recentRows as $row) {
        $rows .= '<tr><td>' . directory_escape((string)$row['date']) . '</td><td>' . directory_escape(directory_format_btc((float)$row['volume'])) . '</td></tr>';
    }

    return '<section class="directory-section directory-entry-volume-history">
	<h2>' . directory_section_heading($labels['volume_history'], 'volume') . '</h2>
	<div class="directory-volume">
' . $source . '
<dl class="directory-volume-stats">
<div class="directory-volume-stat"><dt>' . directory_escape($labels['volume_total']) . '</dt><dd>' . directory_escape(directory_format_btc((float)($history['total_volume'] ?? 0.0), 2)) . '</dd></div>
<div class="directory-volume-stat"><dt>' . directory_escape($labels['volume_ath']) . '</dt><dd>' . directory_escape(directory_format_btc((float)($ath['volume'] ?? 0.0), 2)) . '<small>' . directory_escape((string)($ath['date'] ?? '')) . '</small></dd></div>
<div class="directory-volume-stat"><dt>' . directory_escape($labels['volume_latest']) . '</dt><dd>' . directory_escape(directory_format_btc((float)($last['volume'] ?? 0.0))) . '<small>' . directory_escape((string)($last['date'] ?? '')) . '</small></dd></div>
<div class="directory-volume-stat"><dt>' . directory_escape($labels['volume_average_30d']) . '</dt><dd>' . directory_escape(directory_format_btc($average, 2)) . '</dd></div>
</dl>
<div class="directory-volume-chart">' . directory_render_volume_sparkline($daily, $labels) . '</div>
	<h3>' . directory_icon_label('data-sheet', $labels['volume_recent']) . '</h3>
	<figure class="directory-volume-table-wrap">
<table class="directory-volume-table">
<thead><tr><th>' . directory_escape($labels['volume_date']) . '</th><th>' . directory_escape($labels['volume_btc']) . '</th></tr></thead>
<tbody>
' . $rows . '
</tbody>
</table>
</figure>
</div>
</section>';
}

function directory_format_btc(float $value, int $decimals = 8): string
{
    return number_format($value, $decimals, '.', '') . ' BTC';
}

function directory_format_volume_updated(string $value): string
{
    $value = trim($value);
    if ($value === '') {
        return '';
    }

    if (ctype_digit($value)) {
        return gmdate('Y-m-d H:i:s \U\T\C', (int)$value);
    }

    $timestamp = strtotime($value);
    if ($timestamp !== false) {
        return gmdate('Y-m-d H:i:s \U\T\C', $timestamp);
    }

    return $value;
}

function directory_render_volume_sparkline(array $daily, array $labels): string
{
    $points = array_slice($daily, -90);
    if ($points === []) {
        return '';
    }

    $width = 720;
    $height = 240;
    $marginLeft = 72;
    $marginRight = 18;
    $marginTop = 18;
    $marginBottom = 52;
    $plotWidth = $width - $marginLeft - $marginRight;
    $plotHeight = $height - $marginTop - $marginBottom;
    $max = max(array_map(static fn (array $row): float => (float)$row['volume'], $points));
    $max = $max > 0 ? $max : 1.0;
    $count = count($points);
    $coordinates = [];
    foreach ($points as $index => $row) {
        $x = $count > 1 ? $marginLeft + ($plotWidth * ($index / ($count - 1))) : $marginLeft + ($plotWidth / 2);
        $y = $marginTop + $plotHeight - (((float)$row['volume'] / $max) * $plotHeight);
        $coordinates[] = round($x, 2) . ',' . round($y, 2);
    }

    $axisY = $marginTop + $plotHeight;
    $axisXEnd = $marginLeft + $plotWidth;
    $chartTitle = $labels['volume_history'] ?? 'Volume history';
    $xAxisLabel = $labels['volume_date'] ?? 'Date';
    $yAxisLabel = $labels['volume_btc'] ?? 'Volume (BTC)';
    $tickHtml = '';
    foreach ([0.0, 0.25, 0.5, 0.75, 1.0] as $tick) {
        $tickValue = $max * $tick;
        $y = $marginTop + $plotHeight - ($plotHeight * $tick);
        $tickHtml .= '<line x1="' . $marginLeft . '" y1="' . round($y, 2) . '" x2="' . $axisXEnd . '" y2="' . round($y, 2) . '" stroke="#252525" stroke-width="1"/>
<line x1="' . ($marginLeft - 5) . '" y1="' . round($y, 2) . '" x2="' . $marginLeft . '" y2="' . round($y, 2) . '" stroke="#6a6a6a" stroke-width="1"/>
<text x="' . ($marginLeft - 9) . '" y="' . round($y + 3.5, 2) . '" fill="#bdbdbd" font-size="10" text-anchor="end">' . directory_escape(directory_format_chart_tick($tickValue)) . '</text>
';
    }

    $dateTickIndexes = array_values(array_unique([0, (int) floor(($count - 1) / 2), $count - 1]));
    foreach ($dateTickIndexes as $tickIndex) {
        $x = $count > 1 ? $marginLeft + ($plotWidth * ($tickIndex / ($count - 1))) : $marginLeft + ($plotWidth / 2);
        $date = directory_chart_date_label((string)($points[$tickIndex]['date'] ?? ''));
        $tickHtml .= '<line x1="' . round($x, 2) . '" y1="' . $axisY . '" x2="' . round($x, 2) . '" y2="' . ($axisY + 5) . '" stroke="#6a6a6a" stroke-width="1"/>
<text x="' . round($x, 2) . '" y="' . ($axisY + 20) . '" fill="#bdbdbd" font-size="10" text-anchor="middle">' . directory_escape($date) . '</text>
';
    }

    return '<svg role="img" aria-label="' . directory_escape($chartTitle) . '" focusable="false" viewBox="0 0 ' . $width . ' ' . $height . '" xmlns="http://www.w3.org/2000/svg">
<line x1="' . $marginLeft . '" y1="' . $marginTop . '" x2="' . $marginLeft . '" y2="' . $axisY . '" stroke="#555" stroke-width="1"/>
<line x1="' . $marginLeft . '" y1="' . $axisY . '" x2="' . $axisXEnd . '" y2="' . $axisY . '" stroke="#555" stroke-width="1"/>
' . $tickHtml . '<text x="' . ($marginLeft + ($plotWidth / 2)) . '" y="' . ($height - 10) . '" fill="#d8d8d8" font-size="11" font-weight="650" text-anchor="middle">' . directory_escape($xAxisLabel) . '</text>
<text transform="translate(16 ' . ($marginTop + ($plotHeight / 2)) . ') rotate(-90)" fill="#d8d8d8" font-size="11" font-weight="650" text-anchor="middle">' . directory_escape($yAxisLabel) . '</text>
<polyline fill="none" points="' . directory_escape(implode(' ', $coordinates)) . '" stroke="#bb86fc" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/>
</svg>';
}

function directory_format_chart_tick(float $value): string
{
    if ($value <= 0) {
        return '0';
    }

    if ($value >= 100) {
        return number_format($value, 0, '.', ',');
    }

    if ($value >= 10) {
        return rtrim(rtrim(number_format($value, 1, '.', ','), '0'), '.');
    }

    if ($value >= 1) {
        return rtrim(rtrim(number_format($value, 2, '.', ','), '0'), '.');
    }

    return rtrim(rtrim(number_format($value, 4, '.', ','), '0'), '.');
}

function directory_chart_date_label(string $date): string
{
    $date = trim($date);
    if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $date, $match) === 1) {
        return $match[2] . '-' . $match[3];
    }

    return $date;
}

function directory_is_json_config(string $config): bool
{
    if (!str_starts_with(ltrim($config), '{')) {
        return false;
    }

    json_decode($config, true);
    return json_last_error() === JSON_ERROR_NONE;
}

function directory_render_section_cautions(string $html): string
{
    $html = trim($html);
    if ($html === '') {
        return '';
    }

    return '<section class="directory-section directory-section-cautions">
' . directory_normalize_inline_links($html) . '
</section>';
}

function directory_render_support_value(string $support, string $supportHtml): string
{
    $supportHtml = trim($supportHtml);
    if ($supportHtml === '') {
        return directory_escape($support);
    }

    return directory_normalize_inline_links($supportHtml);
}

function directory_support_channel_labels(string $locale): array
{
    return [
        'email' => 'Email',
        'telegram' => 'Telegram',
        'jabber' => 'Jabber/XMPP',
        'tox' => 'TOX',
        'pgp' => 'PGP',
        'pgp_checker' => 'PGP checker',
        'chat' => 'Chat',
    ];
}

function directory_support_channel_order(): array
{
    return ['email', 'telegram', 'jabber', 'tox', 'pgp', 'pgp_checker', 'chat'];
}

function directory_ordered_support_channels(array $channels): array
{
    $present = [];
    foreach ($channels as $channel) {
        if (!is_string($channel)) {
            continue;
        }

        $channel = trim($channel);
        if ($channel === '') {
            continue;
        }

        $present[$channel] = true;
    }

    $ordered = [];
    foreach (directory_support_channel_order() as $channel) {
        if (isset($present[$channel])) {
            $ordered[] = $channel;
            unset($present[$channel]);
        }
    }

    return array_merge($ordered, array_keys($present));
}

function directory_render_support_channel_headers(array $channels, string $locale): string
{
    $labels = directory_support_channel_labels($locale);
    $headers = '';
    foreach ($channels as $channel) {
        $headers .= directory_table_header($labels[$channel] ?? $channel);
    }

    return $headers;
}

function directory_render_support_channel_cells(array $entry, array $channels, string $locale): string
{
    $labels = directory_support_channel_labels($locale);
    $values = directory_support_channels_for_entry($entry);
    $cells = '';
    foreach ($channels as $channel) {
        $cells .= directory_table_cell($values[$channel] ?? '', $labels[$channel] ?? $channel);
    }

    return $cells;
}

function directory_support_channels_for_entry(array $entry): array
{
    return directory_support_channels(
        (string) ($entry['links']['support'] ?? ''),
        (string) ($entry['links']['support_html'] ?? '')
    );
}

function directory_support_channels(string $support, string $supportHtml): array
{
    $channels = [];
    $supportHtml = trim($supportHtml);
    $supportText = trim($support);

    if ($supportHtml !== '') {
        $dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8"><body>' . $supportHtml . '</body>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOWARNING | LIBXML_NOERROR);
        libxml_clear_errors();

        foreach ($dom->getElementsByTagName('a') as $anchor) {
            if (!$anchor instanceof DOMElement) {
                continue;
            }

            $href = html_entity_decode($anchor->getAttribute('href'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $text = directory_text($anchor);
            $lowerHref = mb_strtolower($href, 'UTF-8');
            $lowerText = mb_strtolower($text, 'UTF-8');
            $channel = '';
            $label = $text;

            if (str_starts_with($lowerHref, 'mailto:')) {
                $channel = 'email';
                $label = substr($href, strlen('mailto:'));
            } elseif (preg_match('/^tox:([a-f0-9]+)/i', $href, $match) === 1) {
                $channel = 'tox';
                $label = $match[1];
            } elseif (str_starts_with($lowerHref, 'xmpp:')) {
                $channel = 'jabber';
                $label = substr($href, strlen('xmpp:'));
            } elseif (preg_match('/(^|\\.)t\\.me$/i', (string) parse_url($href, PHP_URL_HOST)) === 1 || str_contains($lowerHref, 'telegram')) {
                $channel = 'telegram';
            } elseif (str_contains($lowerText, 'pgp checker') || str_contains($lowerHref, 'verify-pgp')) {
                $channel = 'pgp_checker';
            } elseif (str_contains($lowerText, 'pgp') || str_contains($lowerHref, 'pgp')) {
                $channel = 'pgp';
            } elseif (str_contains($lowerText, 'chat') || str_contains($lowerHref, 'chat')) {
                $channel = 'chat';
            }

            if ($channel === '') {
                continue;
            }

            directory_add_support_channel($channels, $channel, directory_support_anchor($href, $label));
        }
    }

    if ($supportText !== '') {
        if (preg_match_all('/[A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,}/i', $supportText, $matches, PREG_OFFSET_CAPTURE) !== false) {
            foreach ($matches[0] as [$email, $offset]) {
                if (preg_match('/(?:Jabber|XMPP):\s*$/iu', substr($supportText, 0, $offset)) === 1) {
                    continue;
                }

                $email = trim($email, '.,;:');
                if (directory_support_channel_contains_text($channels, 'jabber', $email)) {
                    continue;
                }

                directory_add_support_channel($channels, 'email', directory_support_anchor('mailto:' . $email, $email));
            }
        }

        if (preg_match_all('/(?:Jabber|XMPP):\s*([^\/\s]+)/i', $supportText, $matches) !== false) {
            foreach ($matches[1] as $jabber) {
                directory_add_support_channel($channels, 'jabber', directory_support_anchor('xmpp:' . $jabber, $jabber));
            }
        }

        if (preg_match_all('/(?:TOX|Tox):\s*([A-F0-9]{64,})/i', $supportText, $matches) !== false) {
            foreach ($matches[1] as $toxId) {
                directory_add_support_channel($channels, 'tox', directory_support_anchor('tox:' . $toxId, $toxId));
            }
        }

        if (preg_match_all('/(?:^|[\s\/])(@[A-Za-z0-9_]{4,})\b/u', $supportText, $matches) !== false) {
            foreach ($matches[1] as $handle) {
                directory_add_support_channel($channels, 'telegram', directory_support_anchor('https://t.me/' . ltrim($handle, '@'), $handle));
            }
        }
    }

    $rendered = [];
    foreach ($channels as $channel => $values) {
        $rendered[$channel] = implode('<br/>', $values);
    }

    return $rendered;
}

function directory_support_anchor(string $href, string $label): string
{
    return '<a href="' . directory_escape($href) . '">' . directory_escape($label) . '</a>';
}

function directory_support_channel_contains_text(array $channels, string $channel, string $needle): bool
{
    $needle = mb_strtolower(trim($needle), 'UTF-8');
    if ($needle === '') {
        return false;
    }

    foreach ($channels[$channel] ?? [] as $html) {
        $text = mb_strtolower(trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? ''), 'UTF-8');
        if ($text === $needle) {
            return true;
        }
    }

    return false;
}

function directory_add_support_channel(array &$channels, string $channel, string $html): void
{
    $html = trim(directory_normalize_inline_links($html));
    if ($html === '') {
        return;
    }

    $text = mb_strtolower(trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? ''), 'UTF-8');
    foreach ($channels[$channel] ?? [] as $existing) {
        $existingText = mb_strtolower(trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($existing), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? ''), 'UTF-8');
        if ($existingText === $text) {
            return;
        }
    }

    $channels[$channel][] = $html;
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
        if (preg_match('/^tox:([A-Fa-f0-9]+)/', $href, $match) === 1) {
            while ($anchor->firstChild) {
                $anchor->removeChild($anchor->firstChild);
            }
            $anchor->appendChild($dom->createTextNode($match[1]));
            $anchor->setAttribute('title', $match[1]);
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

function directory_tor_value(string $value): string
{
    if ($value === '' || $value === 'No') {
        return 'No';
    }

    return '<a href="' . directory_escape($value) . '" rel="noopener noreferrer" target="_blank">' . directory_escape(directory_onion_label($value)) . '</a>';
}

function directory_table_tor_value(string $value): string
{
    if ($value === '' || $value === 'No') {
        return 'No';
    }

    return '<a href="' . directory_escape($value) . '" rel="noopener noreferrer" target="_blank" title="' . directory_escape(directory_onion_label($value)) . '">Yes</a>';
}

function directory_onion_label(string $value): string
{
    $host = parse_url($value, PHP_URL_HOST);
    if (is_string($host) && $host !== '') {
        $host = preg_replace('/^www\./i', '', $host) ?? $host;
        if (str_ends_with(strtolower($host), '.onion')) {
            return $host;
        }
    }

    if (preg_match('/([a-z0-9-]+\.onion)/i', $value, $match) === 1) {
        return $match[1];
    }

    return $value;
}

function directory_table_header(string $label): string
{
    return '<th' . directory_table_class_attr($label) . ' aria-sort="none" data-sortable-column=""><button class="directory-sort-button" type="button"><span class="directory-sort-label">' . directory_escape($label) . '</span><span aria-hidden="true" class="directory-sort-indicator">↕</span></button></th>';
}

function directory_asset_url(string $base, string $path): string
{
    return $base . $path . '?v=' . DIRECTORY_ASSET_VERSION;
}

function directory_css_asset_url(string $base, string $path): string
{
    return directory_asset_url($base, $path);
}

function directory_js_asset_url(string $base, string $path): string
{
    return directory_asset_url($base, $path);
}

function directory_render_head_assets(string $base, string $canonical, string $title, string $description, string $locale): string
{
    $ogLocale = $locale === 'ru' ? 'ru_RU' : 'en_GB';
    $logoUrl = 'https://bitmixlist.org/wp-content/uploads/2023/12/logo-1-e1701782109696.png';

    return '<link href="https://gmpg.org/xfn/11" rel="profile"/>
<meta content="max-image-preview:large" name="robots"/>
<meta content="' . directory_escape($ogLocale) . '" property="og:locale"/>
<meta content="BitMixList -" property="og:site_name"/>
<meta content="article" property="og:type"/>
<meta content="' . directory_escape($title) . '" property="og:title"/>
<meta content="' . directory_escape($description) . '" property="og:description"/>
<meta content="' . directory_escape($canonical) . '" property="og:url"/>
<meta content="' . directory_escape($logoUrl) . '" property="og:image"/>
<meta content="' . directory_escape($logoUrl) . '" property="og:image:secure_url"/>
<meta content="350" property="og:image:width"/>
<meta content="105" property="og:image:height"/>
<meta content="summary_large_image" name="twitter:card"/>
<meta content="' . directory_escape($title) . '" name="twitter:title"/>
<meta content="' . directory_escape($description) . '" name="twitter:description"/>
<meta content="' . directory_escape($logoUrl) . '" name="twitter:image"/>
<link href="' . directory_asset_url($base, 'wp-content/uploads/2023/12/cropped-favicon-2-32x32.png') . '" rel="icon" sizes="32x32"/>
<link href="' . directory_asset_url($base, 'wp-content/uploads/2023/12/cropped-favicon-2-192x192.png') . '" rel="icon" sizes="192x192"/>
<link href="' . directory_asset_url($base, 'wp-content/uploads/2023/12/cropped-favicon-2-180x180.png') . '" rel="apple-touch-icon"/>
<meta content="' . directory_asset_url($base, 'wp-content/uploads/2023/12/cropped-favicon-2-192x192.png') . '" name="msapplication-TileImage"/>';
}

function directory_version_cacheable_head_urls(string $html): string
{
    $html = str_replace(
        'cropped-favicon-2-270x270.png',
        'cropped-favicon-2-192x192.png',
        $html
    );

    $html = preg_replace_callback(
        '~\bhref=(["\'])([^"\']*wp-content/litespeed/css/[^"\']+?\.css)(?:\?[^"\']*)?\1~',
        static function (array $match): string {
            return 'href=' . $match[1] . $match[2] . '?v=' . DIRECTORY_ASSET_VERSION . $match[1];
        },
        $html
    ) ?? $html;

    $html = preg_replace_callback(
        '~\bsrc=(["\'])([^"\']*wp-content/litespeed/js/[^"\']+?\.js)(?:\?[^"\']*)?\1~',
        static function (array $match): string {
            return 'src=' . $match[1] . $match[2] . '?v=' . DIRECTORY_ASSET_VERSION . $match[1];
        },
        $html
    ) ?? $html;

    return preg_replace_callback(
        '~\b(href|content)=(["\'])([^"\']*wp-content/uploads/2023/12/cropped-favicon-2-[^"\']+\.(?:png|webp|jpg))(?:\?[^"\']*)?\2~i',
        static function (array $match): string {
            return $match[1] . '=' . $match[2] . $match[3] . '?v=' . DIRECTORY_ASSET_VERSION . $match[2];
        },
        $html
    ) ?? $html;
}

function directory_version_css_asset_urls(string $html): string
{
    return directory_version_cacheable_head_urls($html);
}

function directory_table_cell(string $html, string $label): string
{
    $html = directory_unwrap_table_cell_value($html);

    return '<td' . directory_table_class_attr($label) . ' data-label="' . directory_escape($label) . '"><span class="directory-cell-value">' . $html . '</span></td>';
}

function directory_unwrap_table_cell_value(string $html): string
{
    $html = trim($html);

    while (preg_match('/^<span\s+class=(["\'])directory-cell-value\1>(.*)<\/span>$/is', $html, $matches) === 1) {
        $html = trim($matches[2]);
    }

    return $html;
}

function directory_table_class_attr(string $label): string
{
    $classes = [];
    if (directory_is_nowrap_table_label($label)) {
        $classes[] = 'directory-nowrap';
    }
    if (directory_is_coin_table_label($label)) {
        $classes[] = 'directory-coins-cell';
    }

    return $classes === [] ? '' : ' class="' . implode(' ', $classes) . '"';
}

function directory_is_nowrap_table_label(string $label): bool
{
    $normalized = directory_normalize_table_label($label);
    $wrapExact = [
        'mixing fee',
        'coins',
        'crypto',
        'плата за миксинг',
        'комиссия за смешивание',
        'монеты',
        'криптовалюта',
    ];
    if (in_array($normalized, $wrapExact, true)) {
        return false;
    }

    $exact = [
        'name',
        'website',
        'tor site',
        'official site',
        'coordinator link',
        'resells',
        'status',
        'live status',
        'название',
        'веб-сайт',
        'веб сайт',
        'tor-сайт',
        'официальный сайт',
        'url координатора',
        'реселл',
        'статус',
        'доступность',
    ];
    if (in_array($normalized, $exact, true)) {
        return true;
    }

    foreach ([
        'founded',
        'fee',
        'withdraw',
        'minimum',
        'maximum',
        'limit',
        'free up to',
        'time of return',
        'основан',
        'комисс',
        'плата',
        'снятие',
        'миним',
        'максим',
        'лимит',
        'бесплатно до',
        'время возврата',
    ] as $needle) {
        if (str_contains($normalized, $needle)) {
            return true;
        }
    }

    return false;
}

function directory_is_coin_table_label(string $label): bool
{
    return in_array(directory_normalize_table_label($label), [
        'coins',
        'crypto',
        'монеты',
        'криптовалюта',
    ], true);
}

function directory_normalize_table_label(string $label): string
{
    $label = html_entity_decode($label, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $label = preg_replace('/\s+/u', ' ', $label) ?? $label;
    return mb_strtolower(trim($label), 'UTF-8');
}

function directory_mobile_table_card_styles(string $indent = ''): string
{
    $rules = [
        '.directory-table-wrap { overflow-x: visible; }',
        '.directory-entry-table, .directory-comparison-table { min-width: 0; }',
        '.directory-comparison-table--mixers th.directory-coins-cell, .directory-comparison-table--mixers td.directory-coins-cell { width: 100%; max-width: none; }',
        '.directory-comparison-table--mixers td.directory-coins-cell .coin-list { max-width: 100%; }',
        '.directory-facts { border-collapse: separate; border-spacing: 0; table-layout: auto; }',
        '.directory-facts thead { display: none; }',
        '.directory-facts, .directory-facts tbody { display: block; width: 100%; }',
        '.directory-facts tr { display: block; width: 100%; margin: 0 0 12px; overflow: hidden; border: 1px solid #3a2e55; border-radius: 8px; background: #1c1728; box-sizing: border-box; }',
        '.directory-facts td { display: flex; align-items: flex-start; gap: 10px; width: 100%; box-sizing: border-box; padding: 10px 12px; border: 0; border-top: 1px solid #302744; background: #1c1728; white-space: normal; overflow-wrap: anywhere; }',
        '.directory-facts td:first-child { border-top: 0; }',
        '.directory-facts td::before { content: attr(data-label); flex: 0 0 min(8rem, 42%); max-width: 42%; min-width: 0; color: #c7b8ff; font-size: 0.76rem; font-weight: 750; line-height: 1.35; text-transform: uppercase; letter-spacing: 0.02em; overflow-wrap: normal; }',
        '.directory-facts td > .directory-cell-value { display: block; flex: 1 1 auto; min-width: 0; color: #f6f2ff; overflow-wrap: anywhere; }',
        '.directory-facts td > * { min-width: 0; }',
        '.directory-facts td a { overflow-wrap: anywhere; }',
        '.directory-facts .directory-nowrap { white-space: normal; }',
        '.directory-facts td.directory-coins-cell .coin-list { max-width: 100%; }',
        '.directory-entry-table tr { display: grid; grid-template-columns: minmax(6.5rem, 34%) minmax(0, 1fr); }',
        '.directory-entry-table td { display: block; width: auto; border-top: 0; }',
        '.directory-entry-table td::before { content: none; }',
        '.directory-entry-table td:first-child { width: auto; color: #c7b8ff; font-size: 0.82rem; font-weight: 750; line-height: 1.35; overflow-wrap: normal; word-break: normal; hyphens: none; }',
        '.directory-entry-table td:first-child .directory-cell-value { overflow-wrap: normal; word-break: normal; hyphens: none; }',
        '.directory-entry-table td:last-child .directory-cell-value { overflow-wrap: anywhere; }',
    ];

    return $indent . implode("\n" . $indent, $rules);
}

function directory_homepage_mobile_table_card_styles(string $indent = ''): string
{
    $rules = [
        '.homepage-directory .homepage-comparison-table { min-width: 0; }',
        '.homepage-directory-section[data-category="mixers"] .homepage-comparison-table th.directory-coins-cell, .homepage-directory-section[data-category="mixers"] .homepage-comparison-table td.directory-coins-cell { width: 100%; max-width: none; }',
        '.homepage-directory-section[data-category="mixers"] .homepage-comparison-table td.directory-coins-cell .coin-list { max-width: 100%; }',
        '.homepage-directory .directory-facts { border-collapse: separate; border-spacing: 0; table-layout: auto; }',
        '.homepage-directory .directory-facts thead { display: none; }',
        '.homepage-directory .directory-facts, .homepage-directory .directory-facts tbody { display: block; width: 100%; }',
        '.homepage-directory .directory-facts tr { display: block; width: 100%; margin: 0 0 12px; overflow: hidden; border: 1px solid #3a2e55; border-radius: 8px; background: #1c1728; box-sizing: border-box; }',
        '.homepage-directory .directory-facts td { display: flex; align-items: flex-start; gap: 10px; width: 100%; box-sizing: border-box; padding: 10px 12px; border: 0; border-top: 1px solid #302744; background: #1c1728; white-space: normal; overflow-wrap: anywhere; }',
        '.homepage-directory .directory-facts td:first-child { border-top: 0; }',
        '.homepage-directory .directory-facts td::before { content: attr(data-label); flex: 0 0 min(8rem, 42%); max-width: 42%; min-width: 0; color: #c7b8ff; font-size: 0.76rem; font-weight: 750; line-height: 1.35; text-transform: uppercase; letter-spacing: 0.02em; overflow-wrap: normal; }',
        '.homepage-directory .directory-facts td > .directory-cell-value { display: block; flex: 1 1 auto; min-width: 0; color: #f6f2ff; overflow-wrap: anywhere; }',
        '.homepage-directory .directory-facts td > * { min-width: 0; }',
        '.homepage-directory .directory-facts td a { overflow-wrap: anywhere; }',
        '.homepage-directory .directory-facts .directory-nowrap { white-space: normal; }',
        '.homepage-directory .directory-facts td.directory-coins-cell .coin-list { max-width: 100%; }',
    ];

    return $indent . implode("\n" . $indent, $rules);
}

function directory_table_external_value(string $value, string $entryName = ''): string
{
    if ($value === '' || $value === 'No') {
        return 'No';
    }

    return '<a href="' . directory_escape($value) . '" rel="noopener noreferrer" target="_blank">' . directory_escape(directory_table_external_label($value, $entryName)) . '</a>';
}

function directory_table_external_label(string $value, string $entryName = ''): string
{
    $host = parse_url($value, PHP_URL_HOST);
    if (!is_string($host) || $host === '') {
        return $value;
    }

    if (preg_match('/(^|\\.)orangefren\\.com$/i', $host) === 1 && trim($entryName) !== '') {
        return $entryName;
    }

    return preg_replace('/^www\./i', '', $host) ?? $host;
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

function directory_render_footer(string $base, string $locale = 'en'): string
{
    $isRu = $locale === 'ru';
    $copyright = $isRu
        ? '© 2023-2026. <strong><a href="https://bitcointalk.org/index.php?action=profile;u=2739424">NotATether</a></strong>.'
        : 'Copyright © 2023-2026 <strong><a href="https://bitcointalk.org/index.php?action=profile;u=2739424">NotATether</a></strong>.';
    $contact = $isRu ? 'Контакт: admin [at] bitmixlist [dot] org' : 'Contact: admin [at] bitmixlist [dot] org';
    $disclaimers = $isRu ? 'Отказ от ответственности:' : 'Disclaimers:';
    $lawful = $isRu ? 'Смешивайте только те средства, которые вы получили законным путем.' : 'Only mix funds you have obtained lawfully.';
    $laundering = $isRu ? 'Не используйте BitMixList для отмывания денег!' : 'Do not use BitMixList for money laundering!';
    $donate = $isRu ? 'Пожертвовать (нажмите, чтобы получить адреса)' : 'Donate (Click to get addresses)';
    $bitcoinTitle = $isRu ? 'Пожертвовать в Bitcoin' : 'Donate with Bitcoin';
    $moneroTitle = $isRu ? 'Пожертвовать в Monero' : 'Donate with Monero';

    return '<footer class="site-footer" id="site-footer" role="contentinfo"></footer>
<div class="footer">
<div>' . $copyright . '</div>
<div>' . $contact . '</div>
<div><em>' . $disclaimers . '</em></div>
<div>' . $lawful . '</div>
<div>' . $laundering . '</div>
<div style="font-size: 12px;">TOR: mixlistihakx3uexhl3wv7xxpnso75pl2fxxupulqz3gpoybum62puid.onion</div>
<hr/>
<div>' . $donate . '</div>
<div class="donate">
<div><a href="bitcoin:bc1q6sac2xn46fwtv3jqtn606pewz8w6r7hlnwzgvp?amount=0.0001"><picture>
<source srcset="' . $base . 'wp-content/uploads/2023/12/bitcoin.webp" type="image/webp"/>
<img src="' . $base . 'wp-content/uploads/2023/12/bitcoin.jpg" title="' . $bitcoinTitle . '" width="150px"/>
</picture></a></div>
<div><a href="monero:89m7X1HMSiY1jy175wSmsrZ6Bzmeo1DUPgVsxP2d9qcDMScoB9YgJmKBLWhQy72E4fiEysHY1rMuQUP965vsAwrU3ktAN1E?tx_amount=0.050000000000"><picture>
<source srcset="' . $base . 'wp-content/uploads/2023/12/monero.webp" type="image/webp"/>
<img src="' . $base . 'wp-content/uploads/2023/12/monero.jpg" title="' . $moneroTitle . '" width="150px"/>
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
