#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/directory/extract.php';
require_once __DIR__ . '/../src/templates/directory-page.php';

$root = dirname(__DIR__);
$data = directory_extract_all($root);

rewrite_homepage_layout($root . '/index.html', 'en', $data);
rewrite_homepage_layout($root . '/ru/index.html', 'ru', $data);

echo "Homepage directory layout rewritten.\n";

function rewrite_homepage_layout(string $path, string $locale, array $data): void
{
    $html = file_get_contents($path);
    if ($html === false) {
        throw new RuntimeException("Unable to read {$path}");
    }

    $html = homepage_ensure_styles($html);
    [$before, $oldContent, $after] = homepage_split_content($html);
    $notes = homepage_extract_notes($oldContent, $locale);
    $newContent = "\n" . homepage_render_directory($data, $locale) . "\n" . $notes . "\n";

    file_put_contents($path, $before . '<article class="page-content directory-detail homepage-directory">' . $newContent . '</article>' . $after);
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
    if (str_contains($html, '.homepage-directory')) {
        return homepage_ensure_comparison_styles($html);
    }

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

    if (!str_contains($html, $needle)) {
        throw new RuntimeException('Unable to locate index style insertion point');
    }

    return str_replace($needle, $insert, $html);
}

function homepage_ensure_comparison_styles(string $html): string
{
    if (str_contains($html, '.homepage-directory .homepage-comparison-table')) {
        return $html;
    }

    $needle = '          .homepage-directory .directory-facts { width: 100%; border-collapse: collapse; table-layout: fixed; }' . "\n";
    $insert = $needle
        . '          .homepage-directory .homepage-comparison-table { table-layout: auto; min-width: 1040px; }' . "\n"
        . '          .homepage-directory-section[data-category="mixers"] .homepage-comparison-table { min-width: 1380px; }' . "\n"
        . '          .homepage-directory-section[data-category="coordinators"] .homepage-comparison-table { min-width: 960px; }' . "\n";
    $html = str_replace($needle, $insert, $html);

    $needle = '          @media (max-width: 700px) { .homepage-directory { padding: 22px 14px 36px; } .homepage-directory .directory-list { grid-template-columns: 1fr; } .homepage-directory .directory-section-heading { align-items: flex-start; flex-direction: column; gap: 2px; } .homepage-directory .directory-facts, .homepage-directory .directory-facts tbody, .homepage-directory .directory-facts tr, .homepage-directory .directory-facts th, .homepage-directory .directory-facts td { display: block; width: 100%; box-sizing: border-box; } }' . "\n";
    $insert = '          @media (max-width: 700px) { .homepage-directory { padding: 22px 14px 36px; } .homepage-directory .directory-list { grid-template-columns: 1fr; } .homepage-directory .directory-section-heading { align-items: flex-start; flex-direction: column; gap: 2px; } .homepage-directory .homepage-comparison-table { min-width: 0; } .homepage-directory .directory-facts, .homepage-directory .directory-facts tbody, .homepage-directory .directory-facts tr, .homepage-directory .directory-facts th, .homepage-directory .directory-facts td { display: block; width: 100%; box-sizing: border-box; } }' . "\n";
    return str_replace($needle, $insert, $html);
}

function homepage_extract_notes(string $oldContent, string $locale): string
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

    $existingNotes = homepage_first($xpath, './/*[contains(concat(" ", normalize-space(@class), " "), " homepage-notes ")]', $fragment);
    if ($existingNotes) {
        $notes = homepage_notes_without_heading($xpath, $existingNotes);
    } else {
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

    $heading = $locale === 'ru' ? 'Примечания' : 'Notes';
    return '<section class="directory-section homepage-notes">
<h2>' . directory_escape($heading) . '</h2>
' . $notes . '
</section>';
}

function homepage_notes_without_heading(DOMXPath $xpath, DOMNode $notesNode): string
{
    $clone = $notesNode->cloneNode(true);
    if (!$clone instanceof DOMElement) {
        return '';
    }

    $cloneXpath = new DOMXPath($clone->ownerDocument);
    $heading = homepage_first($cloneXpath, './h2', $clone);
    if ($heading && $heading->parentNode) {
        $heading->parentNode->removeChild($heading);
    }

    return trim(directory_inner_html($clone));
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

function homepage_render_directory(array $data, string $locale): string
{
    $sections = '';
    $entriesByCategory = directory_entries_by_category($data['entries']);

    foreach ($data['categories'] as $slug => $category) {
        $entries = $entriesByCategory[$slug] ?? [];
        if ($entries === []) {
            continue;
        }

        $sections .= homepage_render_category($slug, $category, $entries, $locale) . "\n";
    }

    return $sections;
}

function homepage_render_category(string $slug, array $category, array $entries, string $locale): string
{
    $title = $category['index_label'][$locale] ?? $category['title'][$locale];
    $base = $locale === 'ru' ? '../' : '';
    $sectionHref = $slug . '/';
    $sectionLabel = $locale === 'ru' ? 'Открыть раздел' : 'Open section';
    $entriesLabel = $locale === 'ru' ? 'Записи' : 'Entries';
    $comparisonLabel = $locale === 'ru' ? 'Сравнение' : 'Comparison';
    $anchor = $category['anchor'] ?? $slug;
    $isTool = ($category['type'] ?? 'service') === 'tool';

    return '<section class="directory-section homepage-directory-section' . ($isTool ? ' tool-registry' : '') . '" id="' . directory_escape($anchor) . '" data-category="' . directory_escape($slug) . '">
<div class="directory-section-heading">
<h2>' . directory_escape($title) . '</h2>
<a class="directory-section-link" href="' . directory_escape($sectionHref) . '">' . directory_escape($sectionLabel) . '</a>
</div>
<h3>' . directory_escape($entriesLabel) . '</h3>
' . homepage_render_cards($entries, $locale, $base, $isTool) . '
<h3>' . directory_escape($comparisonLabel) . '</h3>
' . homepage_render_table($entries, $locale, $isTool) . '
</section>';
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

    $summaryMarkup = '';
    if ($summary !== '') {
        $summaryTag = $isTool ? 'p' : 'div';
        $summaryMarkup = '<' . $summaryTag . ' class="directory-list-summary ' . directory_escape($summaryClass) . '">' . directory_escape($summary) . '</' . $summaryTag . '>';
    }

    return '<' . $tag . ' class="' . $cardClass . '"' . $dataTags . '>
<div><a class="mixer-logo-link" href="' . directory_escape($entryHref) . '" rel="noopener noreferrer">' . directory_logo_markup($entry, $base, $name) . '</a></div>
<div>
<h3 class="directory-list-title"><a class="' . directory_escape($nameClass) . '" href="' . directory_escape($entryHref) . '" rel="noopener noreferrer">' . directory_escape($name) . '</a></h3>
' . $summaryMarkup . '
<div class="directory-list-actions">
<a class="directory-button" href="' . directory_escape($entryHref) . '">' . directory_escape($detailsLabel) . '</a>
' . ($external !== '' ? '<a class="directory-button ' . directory_escape($visitClass) . '" href="' . directory_escape($external) . '" rel="noopener noreferrer" target="_blank">' . directory_escape($visitLabel) . '</a>' : '') . '
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

function homepage_render_table(array $entries, string $locale, bool $isTool): string
{
    $labels = homepage_table_labels($entries, $locale, $isTool);
    $rows = '';

    foreach ($entries as $entry) {
        $rows .= homepage_render_table_row($entry, $locale, $labels['facts'], $labels['includeSupport']) . "\n";
    }

    $supportHeader = $labels['includeSupport'] ? '<th>' . directory_escape($labels['support']) . '</th>' : '';

    return '<figure class="wp-block-table directory-table-wrap">
<table class="directory-facts homepage-comparison-table">
<thead><tr><th>' . directory_escape($labels['name']) . '</th><th>' . directory_escape($labels['site']) . '</th><th>' . directory_escape($labels['tor']) . '</th>' . homepage_render_fact_headers($labels['facts']) . $supportHeader . '</tr></thead>
<tbody>
' . $rows . '</tbody>
</table>
</figure>';
}

function homepage_table_labels(array $entries, string $locale, bool $isTool): array
{
    $facts = [];
    $seen = [];
    $includeSupport = false;

    foreach ($entries as $entry) {
        if (trim($entry['links']['support'] ?? '') !== '') {
            $includeSupport = true;
        }
        foreach ($entry['facts'][$locale] ?? [] as $fact) {
            $label = trim($fact['label'] ?? '');
            if ($label === '') {
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
        'site' => $locale === 'ru' ? 'Веб-сайт' : 'Website',
        'tor' => $locale === 'ru' ? 'Tor-сайт' : 'Tor Site',
        'support' => $locale === 'ru' ? 'Поддержка' : 'Support',
        'facts' => $facts,
        'includeSupport' => $includeSupport,
    ];
}

function homepage_render_fact_headers(array $labels): string
{
    $headers = '';
    foreach ($labels as $label) {
        $headers .= '<th>' . directory_escape($label) . '</th>';
    }

    return $headers;
}

function homepage_render_table_row(array $entry, string $locale, array $factLabels, bool $includeSupport): string
{
    $entryHref = $entry['index_paths'][$locale];
    $display = $entry['table_display'][$locale] ?? $entry['content'][$locale]['name'];
    $external = $entry['links']['clearnet'] ?? '';
    $tor = $entry['links']['tor'] ?? 'No';
    $factMap = [];

    foreach ($entry['facts'][$locale] ?? [] as $fact) {
        $factMap[mb_strtolower($fact['label'] ?? '', 'UTF-8')] = $fact;
    }

    $cells = '<td><a class="directory-link" href="' . directory_escape($entryHref) . '">' . directory_escape($display) . '</a></td>';
    $cells .= '<td>' . directory_external_value($external) . '</td>';
    $cells .= '<td>' . directory_external_value($tor) . '</td>';

    foreach ($factLabels as $label) {
        $fact = $factMap[mb_strtolower($label, 'UTF-8')] ?? null;
        $cells .= '<td>' . ($fact ? homepage_render_fact_value($fact) : '') . '</td>';
    }

    if ($includeSupport) {
        $cells .= '<td>' . directory_render_support_value($entry['links']['support'] ?? '', $entry['links']['support_html'] ?? '') . '</td>';
    }

    return '<tr>' . $cells . '</tr>';
}

function homepage_render_fact_value(array $fact): string
{
    $html = trim($fact['html'] ?? '');
    if ($html !== '') {
        return directory_normalize_inline_links($html);
    }

    return directory_escape($fact['value'] ?? '');
}

function homepage_first(DOMXPath $xpath, string $query, ?DOMNode $context = null): ?DOMNode
{
    $nodes = $xpath->query($query, $context);
    if (!$nodes || $nodes->length === 0) {
        return null;
    }

    return $nodes->item(0);
}
