#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/directory/extract.php';
require_once __DIR__ . '/../src/templates/directory-page.php';

if (isset($_SERVER['SCRIPT_FILENAME']) && realpath((string) $_SERVER['SCRIPT_FILENAME']) === __FILE__) {
    $root = dirname(__DIR__);
    $data = directory_extract_all($root);

    rewrite_homepage_layout($root . '/index.html', 'en', $data);
    rewrite_homepage_layout($root . '/ru/index.html', 'ru', $data);

    echo "Homepage directory layout rewritten.\n";
}

function rewrite_homepage_layout(string $path, string $locale, array $data): void
{
    $html = file_get_contents($path);
    if ($html === false) {
        throw new RuntimeException("Unable to read {$path}");
    }

    $html = homepage_ensure_styles($html);
    $html = homepage_ensure_filter_script($html);
    $html = homepage_ensure_header_meta_nav($html, $locale, $data['categories']);
    [$before, $oldContent, $after] = homepage_split_content($html);
    $notes = homepage_extract_notes($oldContent, $locale, $data['categories']);
    $newContent = "\n" . homepage_render_intro($notes['intro'], $locale) . "\n" . homepage_render_directory($data, $locale, $notes['sections']) . "\n" . $notes['global'] . "\n";

    file_put_contents($path, homepage_normalize_directory_table_cells(directory_version_cacheable_head_urls($before . '<article class="page-content directory-detail homepage-directory">' . $newContent . '</article>' . $after)));
}

function homepage_normalize_directory_table_cells(string $html): string
{
    return preg_replace_callback(
        '~(<td\b[^>]*\bdata-label="[^"]*"[^>]*>)(.*?)(</td>)~su',
        static function (array $match): string {
            return $match[1] . '<span class="directory-cell-value">' . directory_unwrap_table_cell_value($match[2]) . '</span>' . $match[3];
        },
        $html
    ) ?? $html;
}

function homepage_split_content(string $html): array
{
    $mainStart = strpos($html, '<main ');
    if ($mainStart === false) {
        throw new RuntimeException('Unable to locate <main>');
    }

    $divStart = strpos($html, '<div class="page-content">', $mainStart);
    $articleStart = strpos($html, '<article class="page-content', $mainStart);
    if ($divStart === false && $articleStart === false) {
        throw new RuntimeException('Unable to locate page content container');
    }

    $contentStart = $divStart === false ? $articleStart : ($articleStart === false ? $divStart : min($divStart, $articleStart));
    $tag = $contentStart === $divStart ? 'div' : 'article';
    $openEnd = strpos($html, '>', $contentStart);
    $mainEnd = strpos($html, '</main>', $contentStart);
    if ($openEnd === false || $mainEnd === false) {
        throw new RuntimeException('Unable to locate page content bounds');
    }

    $beforeMain = substr($html, 0, $mainEnd);
    $closeStart = strrpos($beforeMain, '</' . $tag . '>');
    if ($closeStart === false || $closeStart < $openEnd) {
        throw new RuntimeException('Unable to locate page content closing tag');
    }

    return [
        substr($html, 0, $contentStart),
        substr($html, $openEnd + 1, $closeStart - $openEnd - 1),
        substr($html, $mainEnd),
    ];
}

function homepage_ensure_styles(string $html): string
{
    $html = homepage_remove_legacy_alert_warning_styles($html);
    $html = homepage_remove_legacy_config_styles($html);
    $html = directory_normalize_table_wrap_style_rules($html);

    if (str_contains($html, '.homepage-directory')) {
        $html = homepage_ensure_comparison_styles($html);
        $html = homepage_ensure_card_logo_styles($html);
        $html = homepage_ensure_coin_styles($html);
        $html = homepage_ensure_status_styles($html);
        $html = homepage_ensure_nowrap_styles($html);
        $html = homepage_ensure_sort_styles($html);
        $html = homepage_ensure_tool_registry_styles($html);
        $html = homepage_ensure_icon_button_styles($html);
        $html = homepage_ensure_directory_icon_styles($html);
        $html = homepage_ensure_section_note_styles($html);
        $html = homepage_ensure_intro_styles($html);
        $html = homepage_ensure_filter_styles($html);
        $html = homepage_ensure_title_style($html);
        return homepage_ensure_header_nav_styles($html);
    }

    $needle = '          .directory-link { font-weight: 700; color: #f6f2ff; }' . "\n";
    $insert = $needle
        . '          .home .page-header .entry-title { text-align: center; }' . "\n"
        . homepage_header_nav_styles()
        . '          .homepage-directory { max-width: 1080px; margin: 0 auto; padding: 28px 20px 44px; }' . "\n"
        . '          .homepage-intro { max-width: 920px; margin: 0 auto 28px; color: #e8e1f5; line-height: 1.6; }' . "\n"
        . '          .homepage-intro p { margin: 0 0 0.85rem; }' . "\n"
        . '          .homepage-intro p:last-child { margin-bottom: 0; }' . "\n"
        . '          .directory-filter { margin: 22px 0 0; max-width: 560px; }' . "\n"
        . '          .directory-filter-label { display: block; margin: 0 0 6px; color: #f6f2ff; font-size: 0.95rem; font-weight: 650; }' . "\n"
        . '          .directory-filter-input { width: 100%; min-height: 40px; box-sizing: border-box; border: 1px solid #4a3a70; border-radius: 7px; background: #121018; color: #f6f2ff; padding: 0 12px; font: inherit; }' . "\n"
        . '          .directory-filter-input:focus { border-color: #8a6cff; outline: 2px solid rgba(138, 108, 255, 0.24); outline-offset: 2px; }' . "\n"
        . '          .directory-filter-empty { margin: 10px 0 0; color: #c9c3d8; }' . "\n"
        . '          .homepage-directory .directory-section { margin-top: 28px; }' . "\n"
        . '          .homepage-directory .directory-section h2 { margin: 0 0 12px; font-size: 1.35rem; letter-spacing: 0; }' . "\n"
        . '          .homepage-directory .directory-section h3 { margin: 22px 0 10px; font-size: 1.08rem; letter-spacing: 0; }' . "\n"
        . '          .directory-section-heading { display: flex; align-items: baseline; justify-content: space-between; gap: 12px; }' . "\n"
        . '          .directory-section-link { flex: 0 0 auto; color: #d8ccff; font-size: 0.92rem; text-decoration: underline; text-underline-offset: 0.18em; }' . "\n"
        . directory_icon_styles('          ')
        . '          .homepage-directory .directory-list { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 12px; margin: 0; padding: 0; list-style: none; justify-content: stretch; }' . "\n"
        . '          .homepage-directory .directory-list-card { display: grid; grid-template-columns: 128px minmax(0, 1fr); gap: 14px; align-items: start; min-width: 0; width: auto; padding: 14px; border: 1px solid #3a2e55; border-radius: 8px; background: #181222; box-shadow: none; }' . "\n"
        . '          .homepage-directory .directory-list-card .directory-logo { width: 128px; height: 128px; border-radius: 10px; object-fit: contain; }' . "\n"
        . '          .homepage-directory .directory-list-card .directory-logo--text { font-size: 1.8rem; }' . "\n"
        . '          .homepage-directory .directory-list-title { margin: 0 0 4px; font-size: 1.05rem; line-height: 1.25; font-weight: 700; }' . "\n"
        . '          .homepage-directory .directory-list-title a { color: #f6f2ff; text-decoration: none; }' . "\n"
        . '          .homepage-directory .directory-list-title a:hover, .homepage-directory .directory-list-title a:focus { text-decoration: underline; }' . "\n"
        . '          .homepage-directory .directory-list-summary { margin: 0; color: #d8d0e8; font-size: 0.92rem; line-height: 1.45; }' . "\n"
        . directory_coin_styles('          ')
        . directory_status_styles('          ')
        . '          .homepage-directory .directory-list-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-top: 10px; }' . "\n"
        . '          .homepage-directory .directory-button { display: inline-flex; align-items: center; justify-content: center; min-height: 34px; margin-top: 0; padding: 0 10px; border: 1px solid #7a61f6; border-radius: 7px; background: #1a1234; color: #f2ecff; text-decoration: none; font-size: 0.9rem; font-weight: 650; line-height: 1.2; }' . "\n"
        . '          .homepage-directory .directory-button:hover, .homepage-directory .directory-button:focus { background: #27184d; color: #fff; text-decoration: none; }' . "\n"
        . '          .homepage-directory .directory-icon-button { display: inline-flex; align-items: center; justify-content: center; flex: 0 0 auto; width: 34px; height: 34px; min-height: 34px; margin-top: 0; margin-left: auto; padding: 0; border: 1px solid #7a61f6; border-radius: 7px; background: #1a1234; color: #f2ecff; text-decoration: none !important; box-sizing: border-box; }' . "\n"
        . '          .homepage-directory .directory-icon-button:hover, .homepage-directory .directory-icon-button:focus { background: #27184d; color: #fff; text-decoration: none !important; }' . "\n"
        . '          .homepage-directory .directory-icon-button svg { width: 17px; height: 17px; }' . "\n"
        . '          .homepage-directory .directory-table-wrap { margin: 0; overflow-x: auto; }' . "\n"
        . '          .homepage-directory .directory-facts { width: 100%; border-collapse: collapse; table-layout: fixed; }' . "\n"
        . '          .homepage-directory .homepage-comparison-table { table-layout: auto; min-width: 1040px; }' . "\n"
        . '          .homepage-directory-section[data-category="mixers"] .homepage-comparison-table { min-width: 1380px; }' . "\n"
        . '          .homepage-directory-section[data-category="mixers"] .homepage-comparison-table th.directory-coins-cell, .homepage-directory-section[data-category="mixers"] .homepage-comparison-table td.directory-coins-cell { width: 12rem; max-width: 12rem; }' . "\n"
        . '          .homepage-directory-section[data-category="mixers"] .homepage-comparison-table td.directory-coins-cell .coin-list { max-width: 12rem; }' . "\n"
        . '          .homepage-directory-section[data-category="coordinators"] .homepage-comparison-table { min-width: 960px; }' . "\n"
        . '          .homepage-directory .directory-facts th, .homepage-directory .directory-facts td { vertical-align: top; padding: 12px; overflow-wrap: normal; word-break: normal; hyphens: none; }' . "\n"
        . '          .homepage-directory .directory-facts th { color: #f6f2ff; text-align: left; background: #282238; }' . "\n"
        . '          .homepage-directory .directory-facts td { background: #1c1728; }' . "\n"
        . '          .homepage-directory .directory-facts .directory-nowrap { white-space: nowrap; overflow-wrap: normal; word-break: normal; }' . "\n"
        . directory_sort_styles('          ')
        . '          .homepage-directory .tool-registry { max-width: none; margin: 28px 0 0; padding: 0; }' . "\n"
        . '          .homepage-directory .tool-registry .tool-registry__header h2 { margin: 0 0 10px; font-size: 2rem; letter-spacing: 0; }' . "\n"
        . '          .homepage-directory .tool-registry .tool-list { list-style: none; padding: 0; margin: 0; display: grid; gap: 12px; }' . "\n"
        . '          .homepage-directory .tool-registry .tool { border: 1px solid rgba(255, 255, 255, 0.1); background: rgba(255, 255, 255, 0.03); border-radius: 16px; padding: 14px 14px 12px; }' . "\n"
        . '          .homepage-directory .tool-registry .tool__header { display: flex; align-items: center; gap: 12px; }' . "\n"
        . '          .homepage-directory .tool-registry .tool__header .tool__name { flex: 1 1 auto; min-width: 0; }' . "\n"
        . '          .homepage-directory .tool-registry .tool__meta { margin-top: 0; }' . "\n"
        . '          .homepage-section-notes { margin-top: 18px; padding-top: 14px; border-top: 1px solid #3a2e55; }' . "\n"
        . '          .homepage-section-notes > h3 { margin-top: 0; }' . "\n"
        . '          .homepage-notes { border-top: 1px solid #3a2e55; padding-top: 24px; }' . "\n"
        . homepage_mobile_media_styles();

    if (!str_contains($html, $needle)) {
        throw new RuntimeException('Unable to locate index style insertion point');
    }

    return str_replace($needle, $insert, $html);
}

function homepage_ensure_intro_styles(string $html): string
{
    if (str_contains($html, '.homepage-intro {')) {
        return $html;
    }

    $needle = '          .homepage-directory { max-width: 1080px; margin: 0 auto; padding: 28px 20px 44px; }' . "\n";
    $insert = $needle
        . '          .homepage-intro { max-width: 920px; margin: 0 auto 28px; color: #e8e1f5; line-height: 1.6; }' . "\n"
        . '          .homepage-intro p { margin: 0 0 0.85rem; }' . "\n"
        . '          .homepage-intro p:last-child { margin-bottom: 0; }' . "\n";

    if (!str_contains($html, $needle)) {
        throw new RuntimeException('Unable to locate homepage intro style insertion point');
    }

    return str_replace($needle, $insert, $html);
}

function homepage_ensure_title_style(string $html): string
{
    if (str_contains($html, '.home .page-header .entry-title')) {
        return $html;
    }

    $needle = '          .directory-link { font-weight: 700; color: #f6f2ff; }' . "\n";
    if (!str_contains($html, $needle)) {
        throw new RuntimeException('Unable to locate homepage title style insertion point');
    }

    return str_replace(
        $needle,
        $needle . '          .home .page-header .entry-title { text-align: center; }' . "\n",
        $html
    );
}

function homepage_header_nav_styles(): string
{
    return '          .home .site-header { display: block; min-height: 108px; padding-top: 1rem; padding-bottom: 0.95rem; }' . "\n"
        . '          .home .site-content-wrapper { padding-top: 124px; }' . "\n"
        . '          .home .header-inner { gap: 12px; min-height: 42px; align-items: center; position: relative; padding-right: 8rem; }' . "\n"
        . '          .home .header-inner h4 { flex: 1 1 0; min-width: 0; line-height: 1.2; font-weight: 650; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }' . "\n"
        . '          .home .mobile-menu-toggle { flex: 0 0 auto; }' . "\n"
        . '          .home .lang-switcher { position: absolute; top: 50%; right: 2rem; transform: translateY(-50%); margin-left: 0; flex: 0 0 auto; }' . "\n"
        . '          .home .sylvester-top, .home .sylvester-top-mobile { margin-top: 1rem !important; }' . "\n"
        . '          .directory-meta-nav { display: flex; justify-content: center; gap: 22px; width: 100%; margin: 0.45rem 0 0; padding: 0 1.5rem 0.15rem; overflow-x: auto; scrollbar-width: thin; box-sizing: border-box; }' . "\n"
        . '          .directory-meta-link { flex: 0 0 auto; padding: 0 0 3px; border: 0; border-bottom: 2px solid transparent; border-radius: 0; background: transparent; color: #d7d0e6; font-size: 0.9rem; line-height: 1.25; text-decoration: none; white-space: nowrap; }' . "\n"
        . '          .directory-meta-link:hover, .directory-meta-link:focus { border-bottom-color: rgba(187, 134, 252, 0.6); background: transparent; color: #fff; text-decoration: none; }' . "\n"
        . '          .directory-meta-link.is-active { border-bottom-color: #bb86fc; background: transparent; color: #fff; }' . "\n"
        . '          @media (max-width: 700px) { .home .site-header { min-height: 96px; padding-top: 0.65rem; padding-bottom: 0.55rem; } .home .site-content-wrapper { padding-top: 104px; } .home .header-inner { gap: 8px; min-height: 38px; padding-left: 0.5rem; padding-right: 4.25rem; } .home .lang-switcher { right: 0.5rem; gap: 4px; } .home .lang-link { gap: 0; padding: 3px 5px; } .home .lang-link span { display: none; } .directory-meta-nav { justify-content: flex-start; gap: 16px; margin-top: 0.35rem; padding-left: 0.75rem; padding-right: 0.75rem; } .directory-meta-link { font-size: 0.84rem; } }' . "\n";
}

function homepage_ensure_header_nav_styles(string $html): string
{
    if (str_contains($html, '.home .site-header { display: block;')) {
        return $html;
    }

    $needle = '          .home .page-header .entry-title { text-align: center; }' . "\n";
    if (!str_contains($html, $needle)) {
        throw new RuntimeException('Unable to locate homepage header nav style insertion point');
    }

    return str_replace($needle, $needle . homepage_header_nav_styles(), $html);
}

function homepage_ensure_filter_styles(string $html): string
{
    if (str_contains($html, '.directory-filter-input')) {
        return $html;
    }

    $needle = '          .homepage-intro p:last-child { margin-bottom: 0; }' . "\n";
    $insert = $needle
        . '          .directory-filter { margin: 22px 0 0; max-width: 560px; }' . "\n"
        . '          .directory-filter-label { display: block; margin: 0 0 6px; color: #f6f2ff; font-size: 0.95rem; font-weight: 650; }' . "\n"
        . '          .directory-filter-input { width: 100%; min-height: 40px; box-sizing: border-box; border: 1px solid #4a3a70; border-radius: 7px; background: #121018; color: #f6f2ff; padding: 0 12px; font: inherit; }' . "\n"
        . '          .directory-filter-input:focus { border-color: #8a6cff; outline: 2px solid rgba(138, 108, 255, 0.24); outline-offset: 2px; }' . "\n"
        . '          .directory-filter-empty { margin: 10px 0 0; color: #c9c3d8; }' . "\n";

    if (!str_contains($html, $needle)) {
        throw new RuntimeException('Unable to locate homepage filter style insertion point');
    }

    return str_replace($needle, $insert, $html);
}

function homepage_ensure_header_meta_nav(string $html, string $locale, array $categories): string
{
    $fromPath = $locale === 'ru' ? 'ru/index.html' : 'index.html';
    $nav = directory_render_meta_nav($categories, $locale, '', $fromPath);
    $headerStart = strpos($html, '<header class="site-header"');
    if ($headerStart === false) {
        throw new RuntimeException('Unable to locate homepage header');
    }

    $headerEnd = strpos($html, '</header>', $headerStart);
    if ($headerEnd === false) {
        throw new RuntimeException('Unable to locate homepage header end');
    }

    $headerEnd += strlen('</header>');
    $header = substr($html, $headerStart, $headerEnd - $headerStart);
    $header = preg_replace('~\n<nav class="directory-meta-nav" aria-label="[^"]+">\n.*?\n</nav>(?=\n</header>)~su', '', $header) ?? $header;
    $needle = "</div>\n</header>";
    if (!str_contains($header, $needle)) {
        throw new RuntimeException('Unable to locate homepage header nav insertion point');
    }

    $header = str_replace($needle, "</div>\n" . $nav . "\n</header>", $header);

    return substr($html, 0, $headerStart) . $header . substr($html, $headerEnd);
}

function homepage_ensure_filter_script(string $html): string
{
    if (str_contains($html, 'directory-filter.js')) {
        return $html;
    }

    $needle = '<script src="wp-content/litespeed/js/site-search.js" defer></script>' . "\n";
    $insert = $needle . '<script src="wp-content/litespeed/js/directory-filter.js" defer></script>' . "\n";

    if (str_contains($html, $needle)) {
        return str_replace($needle, $insert, $html);
    }

    $ruNeedle = '<script src="../wp-content/litespeed/js/site-search.js" defer></script>' . "\n";
    $ruInsert = $ruNeedle . '<script src="../wp-content/litespeed/js/directory-filter.js" defer></script>' . "\n";
    if (str_contains($html, $ruNeedle)) {
        return str_replace($ruNeedle, $ruInsert, $html);
    }

    throw new RuntimeException('Unable to locate homepage filter script insertion point');
}

function homepage_ensure_directory_icon_styles(string $html): string
{
    if (str_contains($html, '.directory-heading-icon {')) {
        return $html;
    }

    $needle = '          .directory-section-link { flex: 0 0 auto; color: #d8ccff; font-size: 0.92rem; text-decoration: underline; text-underline-offset: 0.18em; }' . "\n";
    if (!str_contains($html, $needle)) {
        throw new RuntimeException('Unable to locate directory icon style insertion point');
    }

    return str_replace($needle, $needle . directory_icon_styles('          '), $html);
}

function homepage_ensure_icon_button_styles(string $html): string
{
    $html = str_replace(
        '          .homepage-directory .directory-list-actions { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }' . "\n",
        '          .homepage-directory .directory-list-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-top: 10px; }' . "\n",
        $html
    );
    $html = str_replace(
        '          .homepage-directory .directory-icon-button { display: inline-flex; align-items: center; justify-content: center; flex: 0 0 auto; width: 34px; height: 34px; min-height: 34px; margin-top: 0; margin-left: auto; padding: 0; border: 1px solid #7a61f6; border-radius: 7px; background: #1a1234; color: #f2ecff; text-decoration: none; box-sizing: border-box; }' . "\n",
        '          .homepage-directory .directory-icon-button { display: inline-flex; align-items: center; justify-content: center; flex: 0 0 auto; width: 34px; height: 34px; min-height: 34px; margin-top: 0; margin-left: auto; padding: 0; border: 1px solid #7a61f6; border-radius: 7px; background: #1a1234; color: #f2ecff; text-decoration: none !important; box-sizing: border-box; }' . "\n",
        $html
    );
    $html = str_replace(
        '          .homepage-directory .directory-icon-button:hover, .homepage-directory .directory-icon-button:focus { background: #27184d; color: #fff; text-decoration: none; }' . "\n",
        '          .homepage-directory .directory-icon-button:hover, .homepage-directory .directory-icon-button:focus { background: #27184d; color: #fff; text-decoration: none !important; }' . "\n",
        $html
    );

    if (!str_contains($html, '.homepage-directory .directory-icon-button')) {
        $needle = '          .homepage-directory .directory-button:hover, .homepage-directory .directory-button:focus { background: #27184d; color: #fff; text-decoration: none; }' . "\n";
        $insert = $needle
            . '          .homepage-directory .directory-icon-button { display: inline-flex; align-items: center; justify-content: center; flex: 0 0 auto; width: 34px; height: 34px; min-height: 34px; margin-top: 0; margin-left: auto; padding: 0; border: 1px solid #7a61f6; border-radius: 7px; background: #1a1234; color: #f2ecff; text-decoration: none !important; box-sizing: border-box; }' . "\n"
            . '          .homepage-directory .directory-icon-button:hover, .homepage-directory .directory-icon-button:focus { background: #27184d; color: #fff; text-decoration: none !important; }' . "\n"
            . '          .homepage-directory .directory-icon-button svg { width: 17px; height: 17px; }' . "\n";
        if (!str_contains($html, $needle)) {
            throw new RuntimeException('Unable to locate icon button style insertion point');
        }
        $html = str_replace($needle, $insert, $html);
    }

    if (!str_contains($html, '.homepage-directory .tool-registry .tool__header')) {
        $needle = '          .homepage-directory .tool-registry .tool { border: 1px solid rgba(255, 255, 255, 0.1); background: rgba(255, 255, 255, 0.03); border-radius: 16px; padding: 14px 14px 12px; }' . "\n";
        $insert = $needle
            . '          .homepage-directory .tool-registry .tool__header { display: flex; align-items: center; gap: 12px; }' . "\n"
            . '          .homepage-directory .tool-registry .tool__header .tool__name { flex: 1 1 auto; min-width: 0; }' . "\n";
        if (!str_contains($html, $needle)) {
            throw new RuntimeException('Unable to locate tool header style insertion point');
        }
        $html = str_replace($needle, $insert, $html);
    }

    return $html;
}

function homepage_remove_legacy_alert_warning_styles(string $html): string
{
    return str_replace(
        [
            '          .alert { position: relative; padding: 1.25rem 1.5rem; margin: 1rem 0 1.5rem; border: 1px solid transparent; border-radius: 16px; }' . "\n",
            '          .alert-warning { color: #4f3200; background: linear-gradient(180deg, #fff3cd 0%, #ffe28a 100%); border-color: #ffca2c; box-shadow: 0 14px 30px rgba(255, 193, 7, 0.22); }' . "\n",
            '          .alert.prominent-warning { font-size: 1.1rem; line-height: 1.6; }' . "\n",
            '          .alert.prominent-warning .alert-heading { margin: 0 0 0.35rem; font-size: 1.4rem; font-weight: 800; color: #3f2800; }' . "\n",
            '          .alert.prominent-warning p { margin: 0; }' . "\n",
            '          .alert.prominent-warning a { color: #6a3f00; font-weight: 700; text-decoration: underline; }' . "\n",
        ],
        '',
        $html
    );
}

function homepage_remove_legacy_config_styles(string $html): string
{
    return str_replace(
        [
            '          .config-button { margin-top: 8px; padding: 6px 10px; border-radius: 8px; border: 1px solid #7a61f6; background: #1a1234; color: #e8ddff; cursor: pointer; }' . "\n",
            '          .config-button:hover, .config-button:focus { background: #27184d; outline: none; }' . "\n",
            '          .config-popover { padding: 12px; border-radius: 10px; border: 1px solid #7a61f6; background: #0f0b1c; color: #e8ddff; max-width: 360px; width: min(90vw, 360px); position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); }' . "\n",
            '          .config-popover::backdrop { background: rgba(0,0,0,0.55); }' . "\n",
            '          .config-close { position: absolute; top: 6px; right: 8px; background: transparent; padding: 3px; border: 1px solid #7a61f6; color: #fff; border-radius: 50%; width: 28px; height: 28px; font-weight: 700; cursor: pointer; }' . "\n",
            '          .config-close:hover, .config-close:focus { background: #27184d; outline: none; }' . "\n",
            '          .config-popover ul { padding-left: 18px; margin: 8px 0 0; }' . "\n",
            '          .config-popover li { margin-bottom: 4px; }' . "\n",
            "\n" . '          /* Bail out on Chrome/Firefox/Edge < 88 and Safari < 14.1 */' . "\n"
                . '          @supports  not (inset: 0) {' . "\n"
                . '            .config-popover {' . "\n"
                . '              display: none;' . "\n"
                . '            }' . "\n"
                . '          }' . "\n",
        ],
        '',
        $html
    );
}

function homepage_ensure_card_logo_styles(string $html): string
{
    return str_replace(
        [
            '          .homepage-directory .directory-list { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 12px; margin: 0; padding: 0; list-style: none; justify-content: stretch; }' . "\n",
            '          .homepage-directory .directory-list-card { display: grid; grid-template-columns: 64px minmax(0, 1fr); gap: 12px; align-items: start; min-width: 0; width: auto; padding: 14px; border: 1px solid #3a2e55; border-radius: 8px; background: #181222; box-shadow: none; }' . "\n",
            '          .homepage-directory .directory-list-card .directory-logo { width: 64px; height: 64px; border-radius: 8px; object-fit: contain; }' . "\n",
            '          .homepage-directory .directory-list-card .directory-logo--text { font-size: 1.15rem; }' . "\n",
        ],
        [
            '          .homepage-directory .directory-list { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 12px; margin: 0; padding: 0; list-style: none; justify-content: stretch; }' . "\n",
            '          .homepage-directory .directory-list-card { display: grid; grid-template-columns: 128px minmax(0, 1fr); gap: 14px; align-items: start; min-width: 0; width: auto; padding: 14px; border: 1px solid #3a2e55; border-radius: 8px; background: #181222; box-shadow: none; }' . "\n",
            '          .homepage-directory .directory-list-card .directory-logo { width: 128px; height: 128px; border-radius: 10px; object-fit: contain; }' . "\n",
            '          .homepage-directory .directory-list-card .directory-logo--text { font-size: 1.8rem; }' . "\n",
        ],
        $html
    );
}

function homepage_ensure_coin_styles(string $html): string
{
    $html = directory_normalize_coin_style_rules($html);

    if (str_contains($html, '.coin-badge')) {
        return $html;
    }

    $needle = '          .homepage-directory .directory-list-summary { margin: 0; color: #d8d0e8; font-size: 0.92rem; line-height: 1.45; }' . "\n";
    if (!str_contains($html, $needle)) {
        throw new RuntimeException('Unable to locate coin style insertion point');
    }

    return str_replace($needle, $needle . directory_coin_styles('          '), $html);
}

function homepage_ensure_status_styles(string $html): string
{
    $statusStyles = directory_status_styles('          ');
    $existingStatusPattern = '~          \.directory-button--disabled,[\s\S]*?          @media \(max-width: 700px\) \{ \.directory-maintenance-notice \{[^\n]*\n~';
    if (preg_match($existingStatusPattern, $html) === 1) {
        return preg_replace($existingStatusPattern, $statusStyles, $html, 1) ?? $html;
    }

    if (str_contains($html, '.directory-status-badge')) {
        return $html;
    }

    $needle = '          .directory-list-summary .coin-list { display: inline-flex; margin-left: 4px; vertical-align: middle; }' . "\n";
    if (!str_contains($html, $needle)) {
        throw new RuntimeException('Unable to locate status style insertion point');
    }

    return str_replace($needle, $needle . $statusStyles, $html);
}

function homepage_ensure_nowrap_styles(string $html): string
{
    if (str_contains($html, '.homepage-directory .directory-facts .directory-nowrap')) {
        return $html;
    }

    $needle = '          .homepage-directory .directory-facts td { background: #1c1728; }' . "\n";
    if (!str_contains($html, $needle)) {
        throw new RuntimeException('Unable to locate nowrap style insertion point');
    }

    return str_replace(
        $needle,
        $needle . '          .homepage-directory .directory-facts .directory-nowrap { white-space: nowrap; overflow-wrap: normal; word-break: normal; }' . "\n",
        $html
    );
}

function homepage_ensure_sort_styles(string $html): string
{
    $html = directory_normalize_sort_style_rules($html);

    if (str_contains($html, '.directory-sort-button')) {
        return $html;
    }

    $needle = '          .homepage-directory .directory-facts .directory-nowrap { white-space: nowrap; overflow-wrap: normal; word-break: normal; }' . "\n";
    if (!str_contains($html, $needle)) {
        throw new RuntimeException('Unable to locate sort style insertion point');
    }

    return str_replace($needle, $needle . directory_sort_styles('          '), $html);
}

function homepage_ensure_tool_registry_styles(string $html): string
{
    $html = str_replace(
        '          .homepage-directory .tool-registry { max-width: 980px; margin: 28px auto 0; padding: 28px 18px; }' . "\n",
        '          .homepage-directory .tool-registry { max-width: none; margin: 28px 0 0; padding: 0; }' . "\n",
        $html
    );

    if (str_contains($html, '.homepage-directory .tool-registry .tool-registry__header h2')) {
        return $html;
    }

    $old = '          .homepage-directory .tool-registry { max-width: none; margin: 28px 0 0; padding: 0; }' . "\n"
        . '          .homepage-directory .tool { border-radius: 8px; }' . "\n";
    $new = '          .homepage-directory .tool-registry { max-width: none; margin: 28px 0 0; padding: 0; }' . "\n"
        . '          .homepage-directory .tool-registry .tool-registry__header h2 { margin: 0 0 10px; font-size: 2rem; letter-spacing: 0; }' . "\n"
        . '          .homepage-directory .tool-registry .tool-list { list-style: none; padding: 0; margin: 0; display: grid; gap: 12px; }' . "\n"
        . '          .homepage-directory .tool-registry .tool { border: 1px solid rgba(255, 255, 255, 0.1); background: rgba(255, 255, 255, 0.03); border-radius: 16px; padding: 14px 14px 12px; }' . "\n"
        . '          .homepage-directory .tool-registry .tool__meta { margin-top: 0; }' . "\n";

    if (str_contains($html, $old)) {
        return str_replace($old, $new, $html);
    }

    $needle = '          .homepage-directory .directory-facts td { background: #1c1728; }' . "\n";
    if (!str_contains($html, $needle)) {
        throw new RuntimeException('Unable to locate tool-registry style insertion point');
    }

    return str_replace($needle, $needle . $new, $html);
}

function homepage_ensure_comparison_styles(string $html): string
{
    if (str_contains($html, '.homepage-directory .homepage-comparison-table')) {
        return homepage_ensure_mixer_coin_cap_styles($html);
    }

    $needle = '          .homepage-directory .directory-facts { width: 100%; border-collapse: collapse; table-layout: fixed; }' . "\n";
    $insert = $needle
        . '          .homepage-directory .homepage-comparison-table { table-layout: auto; min-width: 1040px; }' . "\n"
        . '          .homepage-directory-section[data-category="mixers"] .homepage-comparison-table { min-width: 1380px; }' . "\n"
        . '          .homepage-directory-section[data-category="mixers"] .homepage-comparison-table th.directory-coins-cell, .homepage-directory-section[data-category="mixers"] .homepage-comparison-table td.directory-coins-cell { width: 12rem; max-width: 12rem; }' . "\n"
        . '          .homepage-directory-section[data-category="mixers"] .homepage-comparison-table td.directory-coins-cell .coin-list { max-width: 12rem; }' . "\n"
        . '          .homepage-directory-section[data-category="coordinators"] .homepage-comparison-table { min-width: 960px; }' . "\n";
    $html = str_replace($needle, $insert, $html);

    $needle = '          @media (max-width: 700px) { .homepage-directory { padding: 22px 14px 36px; } .homepage-directory .directory-list { grid-template-columns: 1fr; } .homepage-directory .directory-section-heading { align-items: flex-start; flex-direction: column; gap: 2px; } .homepage-directory .directory-facts, .homepage-directory .directory-facts tbody, .homepage-directory .directory-facts tr, .homepage-directory .directory-facts th, .homepage-directory .directory-facts td { display: block; width: 100%; box-sizing: border-box; } }' . "\n";
    $insert = homepage_mobile_media_styles();
    return homepage_ensure_mixer_coin_cap_styles(str_replace($needle, $insert, $html));
}

function homepage_ensure_mixer_coin_cap_styles(string $html): string
{
    if (!str_contains($html, '.homepage-directory-section[data-category="mixers"] .homepage-comparison-table th.directory-coins-cell')) {
        $needle = '          .homepage-directory-section[data-category="mixers"] .homepage-comparison-table { min-width: 1380px; }' . "\n";
        $insert = $needle
            . '          .homepage-directory-section[data-category="mixers"] .homepage-comparison-table th.directory-coins-cell, .homepage-directory-section[data-category="mixers"] .homepage-comparison-table td.directory-coins-cell { width: 12rem; max-width: 12rem; }' . "\n"
            . '          .homepage-directory-section[data-category="mixers"] .homepage-comparison-table td.directory-coins-cell .coin-list { max-width: 12rem; }' . "\n";
        $html = str_replace($needle, $insert, $html);
    }

    $html = preg_replace(
        '~          @media \(max-width: 700px\) \{\n            \.homepage-directory \{ padding: 22px 14px 36px; \}\n.*?\n          \}\n~su',
        homepage_mobile_media_styles(),
        $html,
        1
    ) ?? $html;

    return $html;
}

function homepage_mobile_media_styles(): string
{
    return '          @media (max-width: 700px) {' . "\n"
        . '            .homepage-directory { padding: 22px 14px 36px; }' . "\n"
        . '            .homepage-directory .directory-list { grid-template-columns: 1fr; }' . "\n"
        . '            .homepage-directory .directory-section-heading { align-items: flex-start; flex-direction: column; gap: 2px; }' . "\n"
        . directory_homepage_mobile_table_card_styles('            ') . "\n"
        . '          }' . "\n";
}

function homepage_ensure_section_note_styles(string $html): string
{
    if (str_contains($html, '.homepage-section-notes')) {
        return $html;
    }

    $needle = '          .homepage-notes { border-top: 1px solid #3a2e55; padding-top: 24px; }' . "\n";
    $insert = '          .homepage-section-notes { margin-top: 18px; padding-top: 14px; border-top: 1px solid #3a2e55; }' . "\n"
        . '          .homepage-section-notes > h3 { margin-top: 0; }' . "\n"
        . $needle;

    return str_replace($needle, $insert, $html);
}

function homepage_extract_notes(string $oldContent, string $locale, array $categories): array
{
    $dom = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="UTF-8"><body><div id="homepage-fragment">' . $oldContent . '</div></body>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOWARNING | LIBXML_NOERROR);
    libxml_clear_errors();
    $xpath = new DOMXPath($dom);
    $fragment = $dom->getElementById('homepage-fragment');
    if (!$fragment) {
        throw new RuntimeException('Unable to parse homepage content');
    }

    $sectionNotes = homepage_extract_existing_section_notes($xpath, $fragment);
    $intro = homepage_extract_existing_intro($xpath, $fragment, $locale);
    homepage_remove_nodes($xpath, $fragment, './/*[contains(concat(" ", normalize-space(@class), " "), " homepage-intro ")]');

    $existingNotes = homepage_first($xpath, './/*[contains(concat(" ", normalize-space(@class), " "), " homepage-notes ")]', $fragment);
    if ($existingNotes) {
        $notes = homepage_notes_without_heading($xpath, $existingNotes);
    } else {
        homepage_remove_nodes($xpath, $fragment, './/*[contains(concat(" ", normalize-space(@class), " "), " homepage-section-notes ")]');
        homepage_remove_nodes($xpath, $fragment, './/*[contains(concat(" ", normalize-space(@class), " "), " mixer-card-row ")]');
        homepage_remove_nodes($xpath, $fragment, './/figure[contains(concat(" ", normalize-space(@class), " "), " wp-block-table ")]');
        homepage_remove_nodes($xpath, $fragment, './/section[contains(concat(" ", normalize-space(@class), " "), " tool-registry ")]');
        homepage_remove_nodes($xpath, $fragment, './/*[contains(concat(" ", normalize-space(@class), " "), " homepage-directory-section ")]');
        homepage_remove_nodes($xpath, $fragment, './/*[@id="altcoin-exchanges"]');
        homepage_remove_empty_elements($xpath, $fragment);

        foreach ($xpath->query('.//*[@id]', $fragment) ?: [] as $node) {
            if ($node instanceof DOMElement) {
                $node->removeAttribute('id');
            }
        }

        $notes = trim(directory_inner_html($fragment));
    }

    [$extractedIntro, $notes] = homepage_extract_intro_from_notes($notes, $locale);
    $notes = homepage_strip_service_url_details($notes);
    if ($intro === '') {
        $intro = $extractedIntro;
    }

    $splitNotes = homepage_split_notes_by_section($notes, $locale, $categories);
    foreach ($sectionNotes as $slug => $html) {
        if (trim($html) === '') {
            continue;
        }
        $splitNotes['sections'][$slug] = trim(($splitNotes['sections'][$slug] ?? '') . "\n" . $html);
    }

    $heading = $locale === 'ru' ? 'Примечания' : 'Notes';
    return [
        'intro' => $intro,
        'global' => '<section class="directory-section homepage-notes">
<h2>' . directory_escape($heading) . '</h2>
' . trim($splitNotes['global']) . '
</section>',
        'sections' => $splitNotes['sections'],
    ];
}

function homepage_extract_existing_intro(DOMXPath $xpath, DOMNode $fragment, string $locale): string
{
    $intro = homepage_first($xpath, './/*[contains(concat(" ", normalize-space(@class), " "), " homepage-intro ")]', $fragment);
    if (!$intro) {
        return '';
    }

    return trim(homepage_child_html($intro));
}

function homepage_extract_intro_from_notes(string $notes, string $locale): array
{
    if (trim($notes) === '') {
        return ['', $notes];
    }

    $introStarts = [
        'en' => 'Bitcoin mixers play a crucial role',
        'ru' => 'Биткоин-миксеры играют важную роль',
    ];
    $disclaimerStarts = [
        'en' => 'While I have done my best',
        'ru' => 'Несмотря на то что я постарался проверить',
    ];
    $introStart = $introStarts[$locale] ?? '';
    if ($introStart === '') {
        return ['', $notes];
    }

    $dom = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="UTF-8"><body><div id="homepage-intro-source">' . $notes . '</div></body>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOWARNING | LIBXML_NOERROR);
    libxml_clear_errors();
    $fragment = $dom->getElementById('homepage-intro-source');
    if (!$fragment) {
        return ['', $notes];
    }

    $intro = '';
    $remove = [];
    foreach ($fragment->childNodes as $child) {
        if (!$child instanceof DOMElement || strtolower($child->tagName) !== 'p') {
            continue;
        }

        $text = trim(preg_replace('/\s+/u', ' ', html_entity_decode($child->textContent, ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? '');
        if (!str_starts_with($text, $introStart)) {
            continue;
        }

        $intro .= $dom->saveHTML($child) . "\n";
        $remove[] = $child;

        $next = homepage_next_element($child);
        if ($next instanceof DOMElement && strtolower($next->tagName) === 'p') {
            $nextText = trim(preg_replace('/\s+/u', ' ', html_entity_decode($next->textContent, ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? '');
            if (str_starts_with($nextText, $disclaimerStarts[$locale] ?? '')) {
                $intro .= $dom->saveHTML($next) . "\n";
                $remove[] = $next;
            }
        }

        break;
    }

    foreach ($remove as $node) {
        $node->parentNode?->removeChild($node);
    }

    return [trim($intro), homepage_child_html($fragment)];
}

function homepage_next_element(DOMNode $node): ?DOMElement
{
    $next = $node->nextSibling;
    while ($next && !($next instanceof DOMElement)) {
        $next = $next->nextSibling;
    }

    return $next instanceof DOMElement ? $next : null;
}

function homepage_notes_without_heading(DOMXPath $xpath, DOMNode $notesNode): string
{
    $clone = $notesNode->cloneNode(true);
    if (!$clone instanceof DOMElement) {
        return '';
    }

    $cloneXpath = new DOMXPath($clone->ownerDocument);
    $heading = homepage_first($cloneXpath, './*[self::h2 or self::h3]', $clone);
    if ($heading && $heading->parentNode) {
        $heading->parentNode->removeChild($heading);
    }

    return trim(homepage_child_html($clone));
}

function homepage_child_html(DOMNode $node): string
{
    $html = '';
    foreach ($node->childNodes as $child) {
        if (homepage_node_is_empty($child)) {
            continue;
        }

        $html .= $node->ownerDocument->saveHTML($child) . "\n";
    }

    return trim($html);
}

function homepage_extract_existing_section_notes(DOMXPath $xpath, DOMNode $fragment): array
{
    $notes = [];
    foreach ($xpath->query('.//*[contains(concat(" ", normalize-space(@class), " "), " homepage-section-notes ")]', $fragment) ?: [] as $node) {
        $section = $node->parentNode;
        while ($section instanceof DOMElement && !homepage_has_class($section, 'homepage-directory-section')) {
            $section = $section->parentNode;
        }
        if (!$section instanceof DOMElement) {
            continue;
        }

        $slug = trim($section->getAttribute('data-category'));
        if ($slug === '') {
            continue;
        }

        $html = homepage_strip_service_url_details(homepage_strip_caution_blocks(homepage_strip_entry_note_paragraphs(homepage_notes_without_heading($xpath, $node))));
        if (trim($html) !== '') {
            $notes[$slug] = trim(($notes[$slug] ?? '') . "\n" . $html);
        }
    }

    return $notes;
}

function homepage_split_notes_by_section(string $notes, string $locale, array $categories): array
{
    $dom = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="UTF-8"><body><div id="homepage-notes-fragment">' . $notes . '</div></body>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOWARNING | LIBXML_NOERROR);
    libxml_clear_errors();
    $fragment = $dom->getElementById('homepage-notes-fragment');
    if (!$fragment) {
        return ['global' => trim($notes), 'sections' => []];
    }

    $headingMap = homepage_section_heading_map($categories, $locale);
    $global = '';
    $sections = [];
    $currentSlug = null;
    foreach ($fragment->childNodes as $child) {
        if (homepage_node_is_empty($child)) {
            continue;
        }

        if (homepage_is_entry_note_paragraph($child)) {
            continue;
        }

        if ($child instanceof DOMElement && homepage_has_class($child, 'author-card')) {
            continue;
        }

        if ($child instanceof DOMElement && in_array(strtolower($child->tagName), ['h2', 'h3'], true)) {
            $slug = $headingMap[homepage_normalize_heading($child->textContent)] ?? null;
            if ($slug !== null) {
                $currentSlug = $slug;
                continue;
            }

            $currentSlug = null;
            $global .= $dom->saveHTML($child) . "\n";
            continue;
        }

        if ($child instanceof DOMElement && (strtolower($child->tagName) === 'section' || homepage_has_class($child, 'author-card'))) {
            $currentSlug = null;
        }

        if ($currentSlug !== null) {
            $sections[$currentSlug] = ($sections[$currentSlug] ?? '') . $dom->saveHTML($child) . "\n";
        } else {
            $global .= $dom->saveHTML($child) . "\n";
        }
    }

    return [
        'global' => trim($global),
        'sections' => array_map('trim', $sections),
    ];
}

function homepage_strip_entry_note_paragraphs(string $html): string
{
    $dom = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="UTF-8"><body><div id="homepage-note-strip">' . $html . '</div></body>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOWARNING | LIBXML_NOERROR);
    libxml_clear_errors();
    $fragment = $dom->getElementById('homepage-note-strip');
    if (!$fragment) {
        return $html;
    }

    $remove = [];
    foreach ($fragment->getElementsByTagName('p') as $paragraph) {
        if (homepage_is_entry_note_paragraph($paragraph)) {
            $remove[] = $paragraph;
        }
    }
    foreach ($remove as $paragraph) {
        $paragraph->parentNode?->removeChild($paragraph);
    }

    return homepage_child_html($fragment);
}

function homepage_strip_caution_blocks(string $html): string
{
    if (trim($html) === '') {
        return '';
    }

    $dom = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="UTF-8"><body><div id="homepage-caution-strip">' . $html . '</div></body>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOWARNING | LIBXML_NOERROR);
    libxml_clear_errors();
    $fragment = $dom->getElementById('homepage-caution-strip');
    if (!$fragment) {
        return $html;
    }

    $xpath = new DOMXPath($dom);
    homepage_remove_nodes($xpath, $fragment, './/*[contains(concat(" ", normalize-space(@class), " "), " alert-warning ") or contains(concat(" ", normalize-space(@class), " "), " prominent-warning ") or contains(concat(" ", normalize-space(@class), " "), " directory-caution ")]');

    return homepage_child_html($fragment);
}

function homepage_strip_service_url_details(string $html): string
{
    if (trim($html) === '') {
        return '';
    }

    $dom = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="UTF-8"><body><div id="homepage-url-strip">' . $html . '</div></body>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOWARNING | LIBXML_NOERROR);
    libxml_clear_errors();
    $fragment = $dom->getElementById('homepage-url-strip');
    if (!$fragment) {
        return $html;
    }

    $xpath = new DOMXPath($dom);
    $remove = [];
    foreach ($xpath->query('.//details[contains(concat(" ", normalize-space(@class), " "), " mirror-details ")]', $fragment) ?: [] as $details) {
        if ($details instanceof DOMElement && homepage_is_service_url_details($xpath, $details)) {
            $remove[] = $details;
        }
    }

    foreach ($remove as $details) {
        $details->parentNode?->removeChild($details);
    }

    return homepage_child_html($fragment);
}

function homepage_is_service_url_details(DOMXPath $xpath, DOMElement $details): bool
{
    $summary = homepage_first($xpath, './summary', $details);
    $summaryText = $summary ? trim(preg_replace('/\s+/u', ' ', html_entity_decode($summary->textContent, ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? '') : '';

    if (preg_match('/\b(?:urls?|mirrors?) by service\b/i', $summaryText) === 1) {
        return true;
    }
    if (preg_match('/(?:url-?адреса|зеркала)\s+по\s+сервис/u', mb_strtolower($summaryText, 'UTF-8')) === 1) {
        return true;
    }

    if (($xpath->query('.//li[strong]', $details)?->length ?? 0) === 0) {
        return false;
    }

    return preg_match('/mirror|url/i', $summaryText) === 1
        || preg_match('/зеркал|url|адрес/ui', $summaryText) === 1;
}

function homepage_section_heading_map(array $categories, string $locale): array
{
    $map = [];
    foreach ($categories as $slug => $category) {
        foreach (['index_label', 'title', 'nav_label'] as $key) {
            foreach (['en', 'ru'] as $language) {
                $label = $category[$key][$language] ?? '';
                if ($label !== '') {
                    $map[homepage_normalize_heading($label)] = $slug;
                }
            }
        }
    }

    $aliases = [
        'The List Of Bitcoin Mixers' => 'mixers',
        'The List Of Never-KYC Exchanges' => 'neverkyc-exchanges',
        'The List Of Instant Exchanges' => 'instant-exchanges',
        'The List Of Peer-to-Peer Marketplaces' => 'p2p-markets',
        'The List Of Wasabi Coordinators' => 'coordinators',
        'Essential Tools for Crypto Privacy' => 'privacy-tools',
        'Список Биткоин-миксеров' => 'mixers',
        'Список биткоин-миксеров' => 'mixers',
        'Список бирж, которые никогда не требуют KYC' => 'neverkyc-exchanges',
        'Список Never-KYC обменников' => 'neverkyc-exchanges',
        'Список мгновенных обменников' => 'instant-exchanges',
        'Список P2P маркетплейсов' => 'p2p-markets',
        'Список P2P-площадок' => 'p2p-markets',
        'Список Wasabi координаторов' => 'coordinators',
        'Список координаторов Wasabi' => 'coordinators',
        'Основные инструменты приватности криптовалют' => 'privacy-tools',
    ];
    foreach ($aliases as $heading => $slug) {
        $map[homepage_normalize_heading($heading)] = $slug;
    }

    return $map;
}

function homepage_normalize_heading(string $heading): string
{
    $heading = html_entity_decode($heading, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $heading = preg_replace('/\s+/u', ' ', $heading) ?? $heading;
    return mb_strtolower(trim($heading), 'UTF-8');
}

function homepage_node_is_empty(DOMNode $node): bool
{
    if ($node instanceof DOMText) {
        return trim($node->textContent) === '';
    }

    if (!$node instanceof DOMElement) {
        return false;
    }

    if (in_array(strtolower($node->tagName), ['br', 'hr', 'img', 'input', 'source'], true)) {
        return false;
    }

    return trim($node->textContent) === '' && $node->getElementsByTagName('img')->length === 0;
}

function homepage_is_entry_note_paragraph(DOMNode $node): bool
{
    if (!$node instanceof DOMElement || strtolower($node->tagName) !== 'p') {
        return false;
    }

    $text = trim(preg_replace('/\s+/u', ' ', html_entity_decode($node->textContent, ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? '');
    return preg_match('/^\*+/u', $text) === 1;
}

function homepage_has_class(DOMElement $node, string $class): bool
{
    return str_contains(' ' . preg_replace('/\s+/u', ' ', $node->getAttribute('class')) . ' ', ' ' . $class . ' ');
}

function homepage_remove_nodes(DOMXPath $xpath, DOMNode $context, string $query): void
{
    $nodes = [];
    foreach ($xpath->query($query, $context) ?: [] as $node) {
        $nodes[] = $node;
    }
    foreach ($nodes as $node) {
        $node->parentNode?->removeChild($node);
    }
}

function homepage_remove_empty_elements(DOMXPath $xpath, DOMNode $context): void
{
    do {
        $removed = false;
        $nodes = [];
        foreach ($xpath->query('.//*[not(self::br) and not(self::img) and not(self::hr) and not(self::input) and not(self::source)]', $context) ?: [] as $node) {
            $nodes[] = $node;
        }
        foreach (array_reverse($nodes) as $node) {
            if (!$node instanceof DOMElement || $node->attributes->length > 1) {
                continue;
            }
            if (trim($node->textContent) === '' && $node->childNodes->length === 0) {
                $node->parentNode?->removeChild($node);
                $removed = true;
            }
        }
    } while ($removed);
}

function homepage_render_intro(string $intro, string $locale): string
{
    $intro = trim($intro);
    if ($intro === '') {
        return '';
    }

    return '<section class="homepage-intro">
' . directory_normalize_inline_links($intro) . '
</section>';
}

function homepage_render_directory(array $data, string $locale, array $sectionNotes): string
{
    $sections = '';
    $entriesByCategory = directory_entries_by_category($data['entries']);

    foreach ($data['categories'] as $slug => $category) {
        $entries = $entriesByCategory[$slug] ?? [];
        if ($entries === []) {
            continue;
        }

        $sections .= homepage_render_category($slug, $category, $entries, $locale, $sectionNotes[$slug] ?? '') . "\n";
    }

    return $sections;
}

function homepage_render_category(string $slug, array $category, array $entries, string $locale, string $sectionNotes): string
{
    $title = $category['index_label'][$locale] ?? $category['title'][$locale];
    $base = $locale === 'ru' ? '../' : '';
    $sectionHref = $slug . '/';
    $sectionLabel = $locale === 'ru' ? 'Открыть раздел' : 'Open section';
    $entriesLabel = $locale === 'ru' ? 'Записи' : 'Entries';
    $dataSheetLabel = $locale === 'ru' ? 'Таблица данных' : 'Data Sheet';
    $anchor = $category['anchor'] ?? $slug;
    $isTool = ($category['type'] ?? 'service') === 'tool';

    if ($isTool) {
        return homepage_render_tool_registry_category($slug, $category, $entries, $locale, $sectionNotes);
    }

    return '<section class="directory-section homepage-directory-section' . ($isTool ? ' tool-registry' : '') . '" id="' . directory_escape($anchor) . '" data-category="' . directory_escape($slug) . '" data-directory-filter-scope>
	<div class="directory-section-heading">
	<h2>' . directory_section_heading($title, directory_category_icon($slug)) . '</h2>
	<a class="directory-section-link" href="' . directory_escape($sectionHref) . '">' . directory_icon_label('arrow-right', $sectionLabel) . '</a>
	</div>
	' . homepage_render_directory_filter($slug, $category, $locale) . '
	<h3>' . directory_section_heading($entriesLabel, 'entries') . '</h3>
	' . homepage_render_cards($entries, $locale, $base, $isTool) . '
	<section class="homepage-data-sheet" data-directory-filter-scope>
	<h3>' . directory_section_heading($dataSheetLabel, 'data-sheet') . '</h3>
	' . directory_render_data_sheet_filter($slug, $locale) . '
' . homepage_render_table($entries, $locale, $base, $isTool, true, $slug) . '
</section>
' . homepage_render_section_notes($sectionNotes, $locale) . '
</section>';
}

function homepage_render_tool_registry_category(string $slug, array $category, array $entries, string $locale, string $sectionNotes): string
{
    $title = $category['index_label'][$locale] ?? $category['title'][$locale];
    $anchor = $category['anchor'] ?? $slug;
    $intro = $locale === 'ru'
        ? 'Отобранные проекты и сервисы для приватности криптовалют. Обновлено: февраль 2026.'
        : 'Curated crypto privacy projects and services. Updated: Feb 2026.';

    return '<section class="directory-section homepage-directory-section tool-registry" id="' . directory_escape($anchor) . '" data-category="' . directory_escape($slug) . '" data-directory-filter-scope>
	<header class="tool-registry__header">
	<h2>' . directory_section_heading($title, directory_category_icon($slug)) . '</h2>
	<p class="muted">' . directory_escape($intro) . '</p>
</header>
' . homepage_render_directory_filter($slug, $category, $locale) . '
' . homepage_render_tool_list($entries, $locale) . '
' . homepage_render_hidden_tool_table($entries, $locale) . '
' . homepage_render_section_notes($sectionNotes, $locale) . '
</section>';
}

function homepage_render_directory_filter(string $scopeId, array $category, string $locale): string
{
    $isTool = ($category['type'] ?? 'service') === 'tool';
    $label = $locale === 'ru'
        ? ($isTool ? 'Фильтр инструментов' : 'Фильтр сервисов')
        : ($isTool ? 'Filter tools' : 'Filter services');
    $placeholder = $locale === 'ru'
        ? 'Название, домен, параметр...'
        : 'Name, domain, parameter...';
    $empty = $locale === 'ru' ? 'Нет совпадающих записей.' : 'No matching entries.';
    $id = 'directory-filter-' . preg_replace('/[^a-z0-9-]+/i', '-', $scopeId . '-' . $locale);

    return '<div class="directory-filter">
	<label class="directory-filter-label" for="' . directory_escape($id) . '">' . directory_icon_label('filter', $label) . '</label>
	<input autocomplete="off" class="directory-filter-input" data-directory-filter-input id="' . directory_escape($id) . '" placeholder="' . directory_escape($placeholder) . '" type="search"/>
<p class="directory-filter-empty" data-directory-filter-empty hidden>' . directory_escape($empty) . '</p>
</div>';
}

function homepage_render_section_notes(string $notes, string $locale): string
{
    $notes = trim(homepage_strip_caution_blocks($notes));
    if ($notes === '') {
        return '';
    }

    $heading = $locale === 'ru' ? 'Примечания' : 'Notes';
    return '<div class="homepage-section-notes">
	<h3>' . directory_section_heading($heading, 'notes') . '</h3>
	' . $notes . '
</div>';
}

function homepage_render_tool_list(array $entries, string $locale): string
{
    $cards = '';
    foreach ($entries as $entry) {
        $cards .= homepage_render_tool_card($entry, $locale) . "\n";
    }

    return '<ul class="tool-list" id="toolList" role="list">
' . $cards . '</ul>';
}

function homepage_render_tool_card(array $entry, string $locale): string
{
    $content = $entry['content'][$locale];
    $name = $content['name'];
    $summary = trim($content['summary'] ?? '');
    $entryHref = $entry['index_paths'][$locale];
    $external = $entry['links']['clearnet'] ?? '';
    $visitLabel = $locale === 'ru' ? 'Открыть проект' : 'Visit project';
    $tags = homepage_tool_tags($entry, $locale);
    $dataTags = trim(implode(' ', array_merge($tags, [$name])));

    return '<li class="tool" data-directory-filter-item data-directory-filter-text="' . directory_escape(homepage_filter_text_for_entry($entry, $locale, false)) . '" data-tags="' . directory_escape($dataTags) . '">
<div class="tool__header">
<a class="tool__name" href="' . directory_escape($entryHref) . '">' . directory_escape($name) . '</a>
' . ($external !== '' ? directory_external_icon_button($external, $visitLabel, 'tool-visit') : '') . '
</div>
' . ($summary !== '' ? '<p class="tool__desc">' . directory_escape($summary) . '</p>' : '') . '
' . ($tags !== [] ? '<div class="tool__meta">' . homepage_render_pills($tags) . '</div>' : '') . '
</li>';
}

function homepage_render_hidden_tool_table(array $entries, string $locale): string
{
    return str_replace(
        '<figure class="wp-block-table directory-table-wrap">',
        '<figure class="wp-block-table directory-table-wrap homepage-tool-source" hidden aria-hidden="true">',
        homepage_render_table($entries, $locale, $locale === 'ru' ? '../' : '', true, false, 'privacy-tools')
    );
}

function homepage_render_cards(array $entries, string $locale, string $base, bool $isTool): string
{
    $tag = $isTool ? 'li' : 'div';
    $listClass = $isTool ? 'directory-list tool-list' : 'directory-list mixer-card-row';
    $cards = '';

    foreach ($entries as $entry) {
        $cards .= homepage_render_card($entry, $locale, $base, $tag, $isTool) . "\n";
    }

    return '<' . ($isTool ? 'ul' : 'div') . ' class="' . $listClass . '">
' . $cards . '</' . ($isTool ? 'ul' : 'div') . '>';
}

function homepage_render_card(array $entry, string $locale, string $base, string $tag, bool $isTool): string
{
    $content = $entry['content'][$locale];
    $name = $content['name'];
    $summary = trim($content['summary'] ?? '');
    $entryHref = $entry['index_paths'][$locale];
    $external = $entry['links']['clearnet'] ?? '';
    $detailsLabel = $locale === 'ru' ? 'Параметры' : 'Details';
    $visitLabel = $isTool
        ? ($locale === 'ru' ? 'Открыть проект' : 'Visit project')
        : ($locale === 'ru' ? 'Открыть сайт' : 'Visit site');
    $nameClass = $isTool ? 'tool__name' : 'mixer-name';
    $visitClass = $isTool ? 'tool-visit' : 'mixer-visit';
    $summaryClass = $isTool ? 'tool__desc' : 'mixer-fee';
    $tags = $isTool ? homepage_tool_tags($entry, $locale) : [];
    $dataTags = $isTool ? ' data-tags="' . directory_escape(implode(' ', $tags)) . '"' : '';
    $cardClass = $isTool ? 'directory-list-card tool' : 'directory-list-card mixer-card';
    $status = directory_entry_status($entry);
    if ($status !== []) {
        $cardClass .= ' directory-list-card--' . directory_escape((string) ($status['type'] ?? 'status'));
    }

    $summaryMarkup = '';
    if ($summary !== '') {
        $summaryTag = $isTool ? 'p' : 'div';
        $summaryMarkup = '<' . $summaryTag . ' class="directory-list-summary ' . directory_escape($summaryClass) . '">' . directory_render_card_summary($summary, $base) . '</' . $summaryTag . '>';
    }
    $externalAction = directory_render_external_action($entry, $locale, $external, $visitLabel, $isTool, $visitClass);

    return '<' . $tag . ' class="' . $cardClass . '"' . $dataTags . ' data-directory-filter-item data-directory-filter-text="' . directory_escape(homepage_filter_text_for_entry($entry, $locale, false)) . '">
	<div class="directory-card-media"><a class="mixer-logo-link" href="' . directory_escape($entryHref) . '" rel="noopener noreferrer">' . directory_logo_markup($entry, $base, $name) . '</a>' . directory_render_status_sign($entry, $locale) . '</div>
	<div>
	<h3 class="directory-list-title"><a class="' . directory_escape($nameClass) . '" href="' . directory_escape($entryHref) . '" rel="noopener noreferrer">' . directory_escape($name) . '</a></h3>' . directory_render_status_badge_line($entry, $locale, true, "\t\t") . '
		' . $summaryMarkup . '
		<div class="directory-list-actions">
	<a class="directory-button" href="' . directory_escape($entryHref) . '">' . directory_icon_label('details', $detailsLabel) . '</a>
	' . $externalAction . '
</div>
' . ($tags !== [] ? '<div class="tool__meta">' . homepage_render_pills($tags) . '</div>' : '') . '
</div>
</' . $tag . '>';
}

function homepage_tool_tags(array $entry, string $locale): array
{
    foreach ($entry['facts'][$locale] ?? [] as $fact) {
        $label = mb_strtolower($fact['label'] ?? '', 'UTF-8');
        if (str_contains($label, 'tag') || str_contains($label, 'категор')) {
            return array_values(array_filter(array_map('trim', explode(',', $fact['value'] ?? ''))));
        }
    }

    return [];
}

function homepage_render_pills(array $tags): string
{
    return implode('', array_map(
        static fn (string $tag): string => '<span class="pill">' . directory_escape($tag) . '</span>',
        $tags
    ));
}

function homepage_render_table(array $entries, string $locale, string $base, bool $isTool, bool $filterable = true, string $categorySlug = ''): string
{
    $labels = homepage_table_labels($entries, $locale, $isTool, $categorySlug);
    $rows = '';

    foreach ($entries as $entry) {
        $rows .= homepage_render_table_row($entry, $locale, $base, $labels['facts'], $filterable, $labels['status']) . "\n";
    }

    return '<figure class="wp-block-table directory-table-wrap">
		<table class="directory-facts homepage-comparison-table">
		<thead><tr>' . directory_table_header($labels['name']) . directory_render_status_header($labels['status']) . directory_table_header($labels['site']) . directory_table_header($labels['tor']) . homepage_render_fact_headers($labels['facts']) . '</tr></thead>
	<tbody>
	' . $rows . '</tbody>
</table>
</figure>';
}

function homepage_table_labels(array $entries, string $locale, bool $isTool, string $categorySlug): array
{
    $facts = [];
    $seen = [];

    foreach ($entries as $entry) {
        foreach ($entry['facts'][$locale] ?? [] as $fact) {
            $label = trim($fact['label'] ?? '');
            if ($label === '') {
                continue;
            }
            if ($categorySlug === 'mixers' && directory_is_support_channel_header($label)) {
                continue;
            }
            if (homepage_should_hide_fact($label, $categorySlug)) {
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
        'site' => $locale === 'ru' ? 'Веб-сайт' : 'Website',
        'tor' => $locale === 'ru' ? 'Tor-сайт' : 'Tor Site',
        'facts' => $facts,
    ];
}

function homepage_should_hide_fact(string $label, string $categorySlug): bool
{
    if ($categorySlug !== 'mixers') {
        return false;
    }

    return in_array(mb_strtolower(trim($label), 'UTF-8'), ['resells', 'реселл'], true);
}

function homepage_render_fact_headers(array $labels): string
{
    $headers = '';
    foreach ($labels as $label) {
        $headers .= directory_table_header($label);
    }

    return $headers;
}

function homepage_render_table_row(array $entry, string $locale, string $base, array $factLabels, bool $filterable, string $statusLabel = ''): string
{
    $entryHref = $entry['index_paths'][$locale];
    $display = directory_base_name($entry['table_display'][$locale] ?? $entry['content'][$locale]['name']);
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
    $cells .= directory_table_cell(homepage_website_value($external, $display), $siteLabel);
    $cells .= directory_table_cell(directory_table_tor_value($tor), $torLabel);

    foreach ($factLabels as $label) {
        $fact = $factMap[mb_strtolower($label, 'UTF-8')] ?? null;
        $cells .= directory_table_cell($fact ? homepage_render_fact_value($fact, $base) : '', $label);
    }

    $filterAttrs = $filterable
        ? ' data-directory-filter-item data-directory-filter-text="' . directory_escape(homepage_filter_text_for_entry($entry, $locale, false)) . '"'
        : '';

    return '<tr' . $filterAttrs . '>' . $cells . '</tr>';
}

function homepage_render_fact_value(array $fact, string $base): string
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

function homepage_filter_text_for_entry(array $entry, string $locale, bool $includeSupport = true): string
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

    $categorySlug = (string) ($entry['category'] ?? '');
    foreach ($entry['facts'][$locale] ?? [] as $fact) {
        $label = (string) ($fact['label'] ?? '');
        if (homepage_should_hide_fact($label, $categorySlug)) {
            continue;
        }

        $parts[] = $label;
        $parts[] = $fact['value'] ?? '';
    }

    return trim(preg_replace('/\s+/u', ' ', implode(' ', array_filter($parts))) ?? '');
}

function homepage_website_value(string $value, string $entryName = ''): string
{
    if ($value === '' || $value === 'No') {
        return 'No';
    }

    return '<a href="' . directory_escape($value) . '" rel="noopener noreferrer" target="_blank">' . directory_escape(homepage_domain_label($value, $entryName)) . '</a>';
}

function homepage_domain_label(string $value, string $entryName = ''): string
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

function homepage_first(DOMXPath $xpath, string $query, ?DOMNode $context = null): ?DOMNode
{
    $nodes = $xpath->query($query, $context);
    if (!$nodes || $nodes->length === 0) {
        return null;
    }

    return $nodes->item(0);
}
