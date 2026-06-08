<?php

declare(strict_types=1);

function directory_repo_root(): string
{
    return dirname(__DIR__, 2);
}

function directory_categories(): array
{
    return require __DIR__ . '/categories.php';
}

function directory_load_dom(string $path): DOMXPath
{
    $html = file_get_contents($path);
    if ($html === false) {
        throw new RuntimeException("Unable to read {$path}");
    }

    $dom = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_NOWARNING | LIBXML_NOERROR);
    libxml_clear_errors();

    return new DOMXPath($dom);
}

function directory_xpath_class(string $class): string
{
    return 'contains(concat(" ", normalize-space(@class), " "), " ' . $class . ' ")';
}

function directory_text(?DOMNode $node): string
{
    if (!$node) {
        return '';
    }

    $text = trim(preg_replace('/\s+/u', ' ', html_entity_decode($node->textContent, ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? '');
    if ($node instanceof DOMElement && strtolower($node->tagName) === 'th') {
        $text = trim(preg_replace('/\s*[↕↑↓]\s*/u', ' ', $text) ?? $text);
    }

    return $text;
}

function directory_inner_html(DOMNode $node): string
{
    $html = '';
    foreach ($node->childNodes as $child) {
        $html .= $node->ownerDocument->saveHTML($child);
    }

    return trim($html);
}

function directory_first(DOMXPath $xpath, string $query, ?DOMNode $context = null): ?DOMNode
{
    $nodes = $xpath->query($query, $context);
    if (!$nodes || $nodes->length === 0) {
        return null;
    }

    return $nodes->item(0);
}

function directory_slugify(string $name): string
{
    $name = directory_base_name($name);
    $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name);
    if ($ascii === false || $ascii === '') {
        $ascii = $name;
    }

    $slug = strtolower($ascii);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';
    $slug = trim($slug, '-');

    if ($slug === '') {
        throw new RuntimeException("Unable to create slug for {$name}");
    }

    return $slug;
}

function directory_base_name(string $name): string
{
    $name = html_entity_decode($name, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $name = preg_replace('/[*]+$/u', '', $name) ?? $name;
    return trim($name);
}

function directory_key(string $name): string
{
    return strtolower(directory_slugify($name));
}

const DIRECTORY_WABISATOR_CONFIG_URL = 'https://wabisator.com/config.json';
const DIRECTORY_WABISATOR_VOLUME_HISTORY_URL = 'https://wabisator.com/volumes_history.json';

function directory_extract_all(string $root, array $options = []): array
{
    $categories = directory_categories();
    $wabisatorConfig = is_array($options['wabisator_config'] ?? null) ? $options['wabisator_config'] : [];
    $wabisatorVolumeHistory = is_array($options['wabisator_volume_history'] ?? null) ? $options['wabisator_volume_history'] : [];
    $locales = [
        'en' => $root . '/index.html',
        'ru' => $root . '/ru/index.html',
    ];

    $localized = [];
    $sectionCautions = [];
    foreach ($locales as $locale => $path) {
        $localized[$locale] = directory_extract_locale($path, $locale, $categories);
        $sectionCautions[$locale] = directory_extract_section_cautions($path, $categories, $locale);
    }

    $entries = [];
    foreach ($categories as $categorySlug => $category) {
        $enEntries = $localized['en'][$categorySlug] ?? [];
        $ruEntries = $localized['ru'][$categorySlug] ?? [];
        $ruBySlug = [];

        foreach ($ruEntries as $entry) {
            $ruBySlug[$entry['slug']] = $entry;
        }

        foreach ($enEntries as $entry) {
            $slug = $entry['slug'];
            if (!isset($ruBySlug[$slug])) {
                throw new RuntimeException("Missing RU entry for {$categorySlug}/{$slug}");
            }

            $ru = $ruBySlug[$slug];
            $outputPaths = [
                'en' => $categorySlug . '/' . $slug . '.html',
                'ru' => 'ru/' . $categorySlug . '/' . $slug . '.html',
            ];
            $enNotes = trim($entry['notes'] ?? '');
            $ruNotes = trim($ru['notes'] ?? '');
            if ($enNotes === '') {
                $enNotes = directory_extract_existing_entry_notes($root . '/' . $outputPaths['en']);
            }
            if ($ruNotes === '') {
                $ruNotes = directory_extract_existing_entry_notes($root . '/' . $outputPaths['ru']);
            }
            $enConfig = trim($entry['config'] ?? '');
            $ruConfig = trim($ru['config'] ?? '');
            if ($enConfig === '') {
                $enConfig = directory_extract_existing_entry_config($root . '/' . $outputPaths['en']);
            }
            if ($ruConfig === '') {
                $ruConfig = directory_extract_existing_entry_config($root . '/' . $outputPaths['ru']);
            }
            if ($categorySlug === 'coordinators') {
                $liveConfig = directory_wabisator_entry_config_json($slug, $wabisatorConfig);
                if ($liveConfig !== '') {
                    $enConfig = $liveConfig;
                    $ruConfig = $liveConfig;
                }
            }
            $volumeHistory = $categorySlug === 'coordinators'
                ? directory_wabisator_entry_volume_history($slug, $wabisatorVolumeHistory)
                : [];
            $links = $entry['links'];
            if (($links['mirrors'] ?? []) === []) {
                $existingMirrors = directory_extract_existing_entry_mirrors($root . '/' . $outputPaths['en']);
                if ($existingMirrors === []) {
                    $existingMirrors = directory_extract_existing_entry_mirrors($root . '/' . $outputPaths['ru']);
                }
                if ($existingMirrors !== []) {
                    $links['mirrors'] = $existingMirrors;
                }
            }
            if (trim($links['support'] ?? '') === '') {
                $existingSupport = directory_extract_existing_entry_support($root . '/' . $outputPaths['en']);
                if (($existingSupport['support'] ?? '') === '') {
                    $existingSupport = directory_extract_existing_entry_support($root . '/' . $outputPaths['ru']);
                }
                if (($existingSupport['support'] ?? '') !== '') {
                    $links['support'] = $existingSupport['support'];
                    $links['support_html'] = $existingSupport['support_html'];
                }
            }
            if ($categorySlug === 'mixers') {
                $supportOverride = directory_mixer_support_override($slug);
                if ($supportOverride !== [] && (trim($links['support'] ?? '') === '' || directory_mixer_support_override_is_authoritative($slug))) {
                    $links['support'] = $supportOverride['support'];
                    $links['support_html'] = $supportOverride['support_html'];
                }
                foreach (directory_mixer_link_override($slug) as $key => $value) {
                    $links[$key] = $value;
                }
            }
            $links = directory_expand_tox_support($links);

            $entries[] = [
                'id' => $categorySlug . ':' . $slug,
                'slug' => $slug,
                'category' => $categorySlug,
                'type' => $category['type'],
                'assets' => $entry['assets'],
                'links' => $links,
                'status' => directory_entry_status_override($categorySlug, $slug),
                'content' => [
                    'en' => $entry['content'],
                    'ru' => $ru['content'],
                ],
                'facts' => [
                    'en' => $entry['facts'],
                    'ru' => $ru['facts'],
                ],
                'table_display' => [
                    'en' => $entry['table_display'] ?? $entry['content']['name'],
                    'ru' => $ru['table_display'] ?? $ru['content']['name'],
                ],
                'notes' => [
                    'en' => $enNotes,
                    'ru' => $ruNotes,
                ],
                'config' => [
                    'en' => $enConfig,
                    'ru' => $ruConfig,
                ],
                'volume_history' => $volumeHistory,
                'index_paths' => [
                    'en' => $categorySlug . '/' . $slug . '.html',
                    'ru' => $categorySlug . '/' . $slug . '.html',
                ],
                'output_paths' => $outputPaths,
            ];
        }
    }

    foreach (array_keys($categories) as $categorySlug) {
        foreach (array_keys($locales) as $locale) {
            if (trim($sectionCautions[$locale][$categorySlug] ?? '') !== '') {
                continue;
            }

            $sectionPath = $root . '/' . ($locale === 'ru' ? 'ru/' : '') . $categorySlug . '/index.html';
            $existingCaution = directory_extract_existing_section_cautions($sectionPath);
            if ($existingCaution !== '') {
                $sectionCautions[$locale][$categorySlug] = $existingCaution;
            }
        }
    }

    return [
        'categories' => $categories,
        'entries' => $entries,
        'section_cautions' => $sectionCautions,
    ];
}

function directory_fetch_wabisator_config(string $url = DIRECTORY_WABISATOR_CONFIG_URL): array
{
    return directory_fetch_wabisator_json($url, 'coordinator config');
}

function directory_fetch_wabisator_volume_history(string $url = DIRECTORY_WABISATOR_VOLUME_HISTORY_URL): array
{
    return directory_fetch_wabisator_json($url, 'coordinator volume history');
}

function directory_fetch_wabisator_json(string $url, string $label): array
{
    $context = stream_context_create([
        'http' => [
            'timeout' => 20,
            'ignore_errors' => true,
            'header' => "User-Agent: BitMixList directory build\r\nAccept: application/json\r\n",
        ],
    ]);
    $json = @file_get_contents($url, false, $context);
    if (!is_string($json) || trim($json) === '') {
        throw new RuntimeException("Unable to fetch Wabisator {$label} from {$url}");
    }

    $data = json_decode($json, true);
    if (!is_array($data)) {
        throw new RuntimeException("Invalid JSON returned by {$url}: " . json_last_error_msg());
    }

    return $data;
}

function directory_wabisator_key(string $slug): string
{
    $keyMap = [
        'kruw' => 'kruw',
        'ginger-wallet' => 'gingerwallet',
        'opencoordinator' => 'opencoordinator',
    ];

    return $keyMap[$slug] ?? str_replace('-', '_', $slug);
}

function directory_wabisator_entry_config_json(string $slug, array $wabisatorConfig): string
{
    $key = directory_wabisator_key($slug);
    $config = $wabisatorConfig[$key]['config'] ?? null;
    if (!is_array($config) || $config === []) {
        return '';
    }

    $json = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    return is_string($json) ? $json : '';
}

function directory_wabisator_entry_volume_history(string $slug, array $wabisatorVolumeHistory): array
{
    $key = directory_wabisator_key($slug);
    $coordinator = $wabisatorVolumeHistory['coordinators'][$key] ?? null;
    if (!is_array($coordinator)) {
        return [];
    }

    $daily = [];
    foreach (($coordinator['daily'] ?? []) as $row) {
        if (!is_array($row)) {
            continue;
        }
        $date = (string)($row['date'] ?? '');
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) !== 1 || !is_numeric($row['volume'] ?? null)) {
            continue;
        }
        $daily[] = [
            'date' => $date,
            'volume' => (float)$row['volume'],
        ];
    }

    if ($daily === []) {
        return [];
    }

    usort($daily, static fn (array $a, array $b): int => strcmp($a['date'], $b['date']));

    $total = is_numeric($coordinator['total_volume'] ?? null)
        ? (float)$coordinator['total_volume']
        : array_sum(array_column($daily, 'volume'));

    $ath = is_array($coordinator['ath'] ?? null) ? $coordinator['ath'] : [];
    $athDate = (string)($ath['date'] ?? '');
    $athVolume = is_numeric($ath['volume'] ?? null) ? (float)$ath['volume'] : 0.0;
    if ($athDate === '' || $athVolume <= 0) {
        foreach ($daily as $row) {
            if ($row['volume'] > $athVolume) {
                $athDate = $row['date'];
                $athVolume = $row['volume'];
            }
        }
    }

    return [
        'source_url' => DIRECTORY_WABISATOR_VOLUME_HISTORY_URL,
        'updated_at' => is_scalar($wabisatorVolumeHistory['updated_at'] ?? null) ? (string)$wabisatorVolumeHistory['updated_at'] : '',
        'daily' => $daily,
        'total_volume' => $total,
        'ath' => [
            'date' => $athDate,
            'volume' => $athVolume,
        ],
    ];
}

function directory_extract_locale(string $path, string $locale, array $categories): array
{
    $xpath = directory_load_dom($path);
    $cardRows = $xpath->query('//div[' . directory_xpath_class('mixer-card-row') . ']');
    $tables = $xpath->query('//figure[' . directory_xpath_class('wp-block-table') . ']//table');
    $mirrorMap = directory_extract_mirrors($xpath);
    $entryNotes = directory_extract_entry_notes($xpath, $categories, $tables);

    $result = [];
    foreach ($categories as $categorySlug => $category) {
        if ($category['type'] === 'tool') {
            $result[$categorySlug] = directory_extract_tools($xpath, $categorySlug, $locale);
            continue;
        }

        $cardRow = $cardRows?->item((int) $category['card_row']);
        $table = $tables?->item((int) $category['table']);
        if (!$cardRow || !$table) {
            throw new RuntimeException("Unable to locate {$locale} source nodes for {$categorySlug}");
        }

        $result[$categorySlug] = directory_extract_service_category($xpath, $categorySlug, $locale, $cardRow, $table, $mirrorMap, $entryNotes[$categorySlug] ?? []);
    }

    return directory_merge_section_page_locale_entries($result, directory_extract_locale_section_pages($path, $locale, $categories));
}

function directory_extract_locale_section_pages(string $homepagePath, string $locale, array $categories): array
{
    $baseDir = dirname($homepagePath);
    $result = [];

    foreach ($categories as $categorySlug => $category) {
        $sectionPath = $baseDir . '/' . $categorySlug . '/index.html';
        if (!is_file($sectionPath)) {
            continue;
        }

        $xpath = directory_load_dom($sectionPath);
        if ($category['type'] === 'tool') {
            $result[$categorySlug] = directory_extract_tools($xpath, $categorySlug, $locale);
            continue;
        }

        $result[$categorySlug] = directory_extract_service_section_page($xpath, $categorySlug, $locale);
    }

    return $result;
}

function directory_merge_section_page_locale_entries(array $homepageEntries, array $sectionEntries): array
{
    foreach ($sectionEntries as $categorySlug => $entries) {
        if (!isset($homepageEntries[$categorySlug]) || $homepageEntries[$categorySlug] === []) {
            $homepageEntries[$categorySlug] = $entries;
            continue;
        }

        $bySlug = [];
        foreach ($homepageEntries[$categorySlug] as $index => $entry) {
            $bySlug[$entry['slug']] = $index;
        }

        foreach ($entries as $entry) {
            $slug = $entry['slug'];
            if (!isset($bySlug[$slug])) {
                $homepageEntries[$categorySlug][] = $entry;
                continue;
            }

            $index = $bySlug[$slug];
            $homepageEntries[$categorySlug][$index] = directory_merge_section_entry(
                $homepageEntries[$categorySlug][$index],
                $entry
            );
        }
    }

    return $homepageEntries;
}

function directory_merge_section_entry(array $homepageEntry, array $sectionEntry): array
{
    foreach (['assets', 'links'] as $group) {
        foreach (($sectionEntry[$group] ?? []) as $key => $value) {
            if (is_array($value)) {
                if ($value !== []) {
                    $homepageEntry[$group][$key] = $value;
                }
                continue;
            }

            if ($group === 'links' && $key === 'tor') {
                $homepageEntry[$group][$key] = $value;
                continue;
            }

            if (trim((string) $value) !== '') {
                $homepageEntry[$group][$key] = $value;
            }
        }
    }

    foreach (($sectionEntry['content'] ?? []) as $key => $value) {
        if (trim((string) $value) !== '') {
            $homepageEntry['content'][$key] = $value;
        }
    }

    if (($sectionEntry['facts'] ?? []) !== []) {
        $homepageEntry['facts'] = $sectionEntry['facts'];
    }

    foreach (['table_display', 'notes', 'config'] as $key) {
        if (trim((string) ($sectionEntry[$key] ?? '')) !== '') {
            $homepageEntry[$key] = $sectionEntry[$key];
        }
    }

    return $homepageEntry;
}

function directory_extract_service_section_page(DOMXPath $xpath, string $categorySlug, string $locale): array
{
    $table = directory_first($xpath, '//table[' . directory_xpath_class('directory-comparison-table') . ']');
    if (!$table) {
        return [];
    }

    $headers = [];
    foreach ($xpath->query('.//thead/tr/th', $table) ?: [] as $th) {
        $headers[] = directory_text($th);
    }

    $rowMap = [];
    foreach ($xpath->query('.//tbody/tr', $table) ?: [] as $tr) {
        $cells = [];
        foreach ($xpath->query('./td', $tr) ?: [] as $td) {
            $cells[] = $td;
        }
        if ($cells === []) {
            continue;
        }

        $displayName = directory_text($cells[0]);
        if ($displayName === '') {
            continue;
        }

        $rowMap[directory_key($displayName)] = [
            'display_name' => $displayName,
            'cells' => $cells,
        ];
    }

    $entries = [];
    foreach ($xpath->query('//article[' . directory_xpath_class('directory-list-card') . ']') ?: [] as $card) {
        $nameNode = directory_first($xpath, './/h3[' . directory_xpath_class('directory-list-title') . ']//a', $card);
        $name = directory_text($nameNode);
        if ($name === '') {
            continue;
        }

        $slug = directory_slugify($name);
        $row = $rowMap[directory_key($name)] ?? null;
        if (!$row) {
            continue;
        }

        $sourceNode = directory_first($xpath, './/source', $card);
        $imgNode = directory_first($xpath, './/img', $card);
        $summaryNode = directory_first($xpath, './/*[' . directory_xpath_class('directory-list-summary') . ']', $card);
        $facts = directory_table_facts($xpath, $headers, $row['cells'], $categorySlug);
        $facts = directory_apply_mixer_fact_overrides($facts, $slug, $locale, $categorySlug);
        $summary = directory_mixer_summary_with_merged_fee(directory_text($summaryNode), $facts, $locale, $categorySlug);

        $entries[] = [
            'slug' => $slug,
            'category' => $categorySlug,
            'assets' => [
                'webp' => $sourceNode instanceof DOMElement ? directory_section_asset_path_for_homepage($sourceNode->getAttribute('srcset')) : '',
                'image' => $imgNode instanceof DOMElement ? directory_section_asset_path_for_homepage($imgNode->getAttribute('src')) : '',
                'alt' => $imgNode instanceof DOMElement ? $imgNode->getAttribute('alt') : $name . ' logo',
            ],
            'links' => directory_service_links($xpath, $headers, $row['cells'], $nameNode),
            'content' => [
                'name' => $name,
                'summary' => $summary,
                'description' => '',
            ],
            'facts' => $facts,
            'table_display' => $row['display_name'],
            'notes' => '',
            'config' => directory_table_config($xpath, $headers, $row['cells']),
        ];
    }

    return $entries;
}

function directory_section_asset_path_for_homepage(string $path): string
{
    if (str_starts_with($path, '../')) {
        return substr($path, 3);
    }

    return $path;
}

function directory_extract_service_category(DOMXPath $xpath, string $categorySlug, string $locale, DOMNode $cardRow, DOMNode $table, array $mirrorMap, array $entryNotes): array
{
    $headers = [];
    foreach ($xpath->query('.//thead/tr/th', $table) ?: [] as $th) {
        $headers[] = directory_text($th);
    }

    $rowMap = [];
    foreach ($xpath->query('.//tbody/tr', $table) ?: [] as $tr) {
        $cells = [];
        foreach ($xpath->query('./td', $tr) ?: [] as $td) {
            $cells[] = $td;
        }
        if ($cells === []) {
            continue;
        }

        $displayName = directory_text($cells[0]);
        $key = directory_key($displayName);
        $rowMap[$key] = [
            'display_name' => $displayName,
            'cells' => $cells,
        ];
    }

    $entries = [];
    foreach ($xpath->query('./div[' . directory_xpath_class('mixer-card') . ']', $cardRow) ?: [] as $card) {
        $nameNode = directory_first($xpath, './/a[' . directory_xpath_class('mixer-name') . ']', $card);
        $name = directory_text($nameNode);
        if ($name === '') {
            continue;
        }

        $slug = directory_slugify($name);
        $row = $rowMap[directory_key($name)] ?? null;
        if (!$row) {
            throw new RuntimeException("Missing table row for {$locale} {$categorySlug}/{$name}");
        }

        $sourceNode = directory_first($xpath, './/source', $card);
        $imgNode = directory_first($xpath, './/img', $card);
        $feeNode = directory_first($xpath, './/div[' . directory_xpath_class('mixer-fee') . ']', $card);

        $facts = directory_table_facts($xpath, $headers, $row['cells'], $categorySlug);
        $facts = directory_apply_mixer_fact_overrides($facts, $slug, $locale, $categorySlug);
        $links = directory_service_links($xpath, $headers, $row['cells'], $nameNode);
        $links['mirrors'] = $mirrorMap[directory_key($name)] ?? [];
        $config = directory_table_config($xpath, $headers, $row['cells']);
        $summary = directory_mixer_summary_with_merged_fee(directory_text($feeNode), $facts, $locale, $categorySlug);

        $entries[] = [
            'slug' => $slug,
            'category' => $categorySlug,
            'assets' => [
                'webp' => $sourceNode instanceof DOMElement ? $sourceNode->getAttribute('srcset') : '',
                'image' => $imgNode instanceof DOMElement ? $imgNode->getAttribute('src') : '',
                'alt' => $imgNode instanceof DOMElement ? $imgNode->getAttribute('alt') : $name . ' logo',
            ],
            'links' => $links,
            'content' => [
                'name' => $name,
                'summary' => $summary,
                'description' => '',
            ],
            'facts' => $facts,
            'table_display' => $row['display_name'],
            'notes' => $entryNotes[directory_key($name)] ?? '',
            'config' => $config,
        ];
    }

    return $entries;
}

function directory_extract_tools(DOMXPath $xpath, string $categorySlug, string $locale): array
{
    $entries = [];
    $section = directory_first($xpath, '//section[' . directory_xpath_class('tool-registry') . ']');
    if (!$section) {
        return $entries;
    }

    $tableRows = directory_extract_tool_table_rows($xpath, $section, $categorySlug);
    $query = './/li[' . directory_xpath_class('tool') . ']';

    foreach ($xpath->query($query, $section) ?: [] as $tool) {
        $nameNode = directory_first($xpath, './/a[' . directory_xpath_class('tool__name') . ']', $tool);
        $visitNode = directory_first($xpath, './/a[' . directory_xpath_class('tool-visit') . ']', $tool);
        $descNode = directory_first($xpath, './/*[' . directory_xpath_class('tool__desc') . ']', $tool);
        $name = directory_text($nameNode);
        if ($name === '') {
            continue;
        }

        $pills = [];
        foreach ($xpath->query('.//span[' . directory_xpath_class('pill') . ']', $tool) ?: [] as $pill) {
            $pills[] = directory_text($pill);
        }

        $external = '';
        if ($visitNode instanceof DOMElement) {
            $external = html_entity_decode($visitNode->getAttribute('href'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        } elseif ($nameNode instanceof DOMElement) {
            $external = html_entity_decode($nameNode->getAttribute('href'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        $links = $tableRows[directory_key($name)]['links'] ?? [
            'clearnet' => $external,
            'tor' => 'No',
            'mirrors' => [],
            'support' => '',
            'support_html' => '',
        ];
        if (($links['clearnet'] ?? '') === '') {
            $links['clearnet'] = $external;
        }
        if (($links['tor'] ?? '') === '') {
            $links['tor'] = 'No';
        }

        $entries[] = [
            'slug' => directory_slugify($name),
            'category' => $categorySlug,
            'assets' => [
                'webp' => '',
                'image' => '',
                'alt' => $name . ' logo',
            ],
            'links' => $links,
            'content' => [
                'name' => $name,
                'summary' => directory_text($descNode),
                'description' => directory_text($descNode),
            ],
            'facts' => [
                ['label' => $locale === 'ru' ? 'Категории' : 'Tags', 'value' => implode(', ', $pills), 'html' => ''],
                ['label' => $locale === 'ru' ? 'Ссылка проекта' : 'Project link', 'value' => $external, 'html' => ''],
            ],
            'table_display' => $name,
            'notes' => '',
            'config' => '',
        ];
    }

    return $entries;
}

function directory_apply_mixer_fact_overrides(array $facts, string $slug, string $locale, string $categorySlug): array
{
    if ($categorySlug !== 'mixers') {
        return $facts;
    }

    $facts = directory_apply_mixer_telegram_bot_override($facts, $slug, $locale);
    $facts = directory_apply_mixer_parameter_overrides($facts, $slug, $locale);
    $resells = directory_mixer_resells_override($slug);
    if ($resells !== '') {
        $facts = directory_upsert_resells_fact($facts, $locale === 'ru' ? 'Реселл' : 'Resells', $resells);
    }

    $facts = directory_apply_mixer_amount_overrides($facts, $slug, $locale);
    $facts = directory_strip_mixer_not_stated_fee_suffix($facts);

    return directory_merge_mixer_withdraw_fee_fact($facts);
}

function directory_apply_mixer_parameter_overrides(array $facts, string $slug, string $locale): array
{
    if ($slug === 'jokermix') {
        $facts = directory_replace_or_append_fact(
            $facts,
            'directory_is_coin_fact_label_for_extract',
            $locale === 'ru' ? 'Монеты' : 'Coins',
            'BTC, ETH, LTC'
        );
        return directory_replace_or_append_fact(
            $facts,
            'directory_is_mixing_fee_fact_label_for_extract',
            $locale === 'ru' ? 'Плата за миксинг' : 'Mixing Fee',
            $locale === 'ru' ? 'BTC: 1-150 сат/вБ; ETH/LTC: 2-7%' : 'BTC: 1-150 sats/vB; ETH/LTC: 2-7%'
        );
    }

    if ($slug === 'zeusmix') {
        $facts = directory_replace_or_append_fact(
            $facts,
            'directory_is_coin_fact_label_for_extract',
            $locale === 'ru' ? 'Монеты' : 'Coins',
            'LTC, ETH, SOL, TRX, USDT-TRC20'
        );
        return directory_replace_or_append_fact(
            $facts,
            'directory_is_mixing_fee_fact_label_for_extract',
            $locale === 'ru' ? 'Плата за миксинг' : 'Mixing Fee',
            '1-6%'
        );
    }

    return $facts;
}

function directory_replace_or_append_fact(array $facts, callable $matchesLabel, string $label, string $value): array
{
    $fact = [
        'label' => $label,
        'value' => $value,
        'html' => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
    ];

    foreach ($facts as $index => $existing) {
        if ($matchesLabel((string) ($existing['label'] ?? ''))) {
            $facts[$index] = $fact;
            return $facts;
        }
    }

    $facts[] = $fact;
    return $facts;
}

function directory_merge_mixer_withdraw_fee_fact(array $facts): array
{
    $mixingIndex = null;
    $withdrawIndex = null;

    foreach ($facts as $index => $fact) {
        $label = (string) ($fact['label'] ?? '');
        if ($mixingIndex === null && directory_is_mixing_fee_fact_label_for_extract($label)) {
            $mixingIndex = $index;
            continue;
        }

        if ($withdrawIndex === null && directory_is_withdraw_fee_fact_label_for_extract($label)) {
            $withdrawIndex = $index;
        }
    }

    if ($mixingIndex === null || $withdrawIndex === null) {
        return $facts;
    }

    $mixingValue = trim((string) ($facts[$mixingIndex]['value'] ?? ''));
    $withdrawValue = trim((string) ($facts[$withdrawIndex]['value'] ?? ''));
    if ($mixingValue !== '' && $withdrawValue !== '' && !directory_withdraw_fee_value_is_none($withdrawValue) && !str_contains($mixingValue, '+')) {
        $combined = $mixingValue . ' + ' . $withdrawValue;
        $facts[$mixingIndex]['value'] = $combined;
        $facts[$mixingIndex]['html'] = htmlspecialchars($combined, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    unset($facts[$withdrawIndex]);
    return array_values($facts);
}

function directory_mixer_summary_with_merged_fee(string $summary, array $facts, string $locale, string $categorySlug): string
{
    if ($categorySlug !== 'mixers') {
        return $summary;
    }

    $fee = '';
    foreach ($facts as $fact) {
        if (directory_is_mixing_fee_fact_label_for_extract((string) ($fact['label'] ?? ''))) {
            $fee = trim((string) ($fact['value'] ?? ''));
            break;
        }
    }

    if ($fee === '') {
        return $summary;
    }

    if ($summary === '') {
        return ($locale === 'ru' ? 'Комиссия за смешивание: ' : 'Mixing fee: ') . $fee;
    }

    if (preg_match('/^([^:]+:\s*).+$/u', $summary) !== 1) {
        return ($locale === 'ru' ? 'Комиссия за смешивание: ' : 'Mixing fee: ') . $fee;
    }

    return preg_replace_callback(
        '/^([^:]+:\s*).+$/u',
        static fn (array $match): string => $match[1] . $fee,
        $summary,
        1
    ) ?? $summary;
}

function directory_apply_mixer_telegram_bot_override(array $facts, string $slug, string $locale): array
{
    $url = directory_mixer_telegram_bot_url($slug);
    if ($url === '') {
        return $facts;
    }

    $label = $locale === 'ru' ? 'Телеграм-бот' : 'Telegram Bot';
    $value = $locale === 'ru' ? 'Да' : 'Yes';
    $fact = [
        'label' => $label,
        'value' => $value,
        'html' => directory_telegram_bot_anchor_html($url, $value),
    ];

    foreach ($facts as $index => $existing) {
        if (directory_is_telegram_bot_fact_label_for_extract((string) ($existing['label'] ?? ''))) {
            $facts[$index] = $fact;
            return $facts;
        }
    }

    $facts[] = $fact;
    return $facts;
}

function directory_apply_mixer_amount_overrides(array $facts, string $slug, string $locale): array
{
    if ($slug === 'coinomize') {
        return directory_upsert_maximum_fact(
            $facts,
            $locale === 'ru' ? 'Максимум' : 'Maximum',
            $locale === 'ru' ? 'Не указано' : 'Not stated'
        );
    }

    if ($slug === 'zeusmix') {
        $facts = directory_replace_or_append_fact(
            $facts,
            'directory_is_minimum_fact_label_for_extract',
            $locale === 'ru' ? 'Минимум' : 'Minimum',
            '$400'
        );
        return directory_replace_or_append_fact(
            $facts,
            'directory_is_maximum_fact_label_for_extract',
            $locale === 'ru' ? 'Максимум' : 'Maximum',
            '$10,000'
        );
    }

    return $facts;
}

function directory_upsert_minimum_fact(array $facts, string $label, string $value): array
{
    foreach ($facts as $fact) {
        if (directory_is_minimum_fact_label_for_extract((string) ($fact['label'] ?? ''))) {
            return $facts;
        }
    }

    $insertAt = count($facts);
    foreach ($facts as $index => $fact) {
        if (directory_is_telegram_bot_fact_label_for_extract((string) ($fact['label'] ?? ''))) {
            $insertAt = $index;
            break;
        }
    }
    foreach ($facts as $index => $fact) {
        if (directory_is_maximum_fact_label_for_extract((string) ($fact['label'] ?? ''))) {
            $insertAt = $index;
            break;
        }
    }

    array_splice($facts, $insertAt, 0, [[
        'label' => $label,
        'value' => $value,
        'html' => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
    ]]);

    return $facts;
}

function directory_upsert_maximum_fact(array $facts, string $label, string $value): array
{
    foreach ($facts as $fact) {
        if (directory_is_maximum_fact_label_for_extract((string) ($fact['label'] ?? ''))) {
            return $facts;
        }
    }

    $insertAt = count($facts);
    foreach ($facts as $index => $fact) {
        if (directory_is_telegram_bot_fact_label_for_extract((string) ($fact['label'] ?? ''))) {
            $insertAt = $index;
            break;
        }
    }
    foreach ($facts as $index => $fact) {
        if (directory_is_minimum_fact_label_for_extract((string) ($fact['label'] ?? ''))) {
            $insertAt = $index + 1;
            break;
        }
    }

    array_splice($facts, $insertAt, 0, [[
        'label' => $label,
        'value' => $value,
        'html' => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
    ]]);

    return $facts;
}

function directory_strip_mixer_not_stated_fee_suffix(array $facts): array
{
    foreach ($facts as $index => $fact) {
        if (!directory_is_mixing_fee_fact_label_for_extract((string) ($fact['label'] ?? ''))) {
            continue;
        }

        $value = trim((string) ($fact['value'] ?? ''));
        $clean = preg_replace('/\s*\+\s*(?:not stated|не указано)\s*$/iu', '', $value) ?? $value;
        if ($clean === $value) {
            continue;
        }

        $facts[$index]['value'] = $clean;
        $facts[$index]['html'] = htmlspecialchars($clean, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    return $facts;
}

function directory_mixer_telegram_bot_url(string $slug): string
{
    static $urls = [
        'mixer-money' => 'https://t.me/mm5btc_bot',
        'mixtum' => 'https://t.me/mixtum_bot',
        'webmixer' => 'https://t.me/webm1xer_bot',
        'okmix' => 'https://t.me/Okmixer_bot',
        'bmix' => 'https://t.me/bMixIoBot?start=s-0',
        'bitxer' => 'https://t.me/bitxerbot',
        'jokermix' => 'https://t.me/JokerMixBOT',
    ];

    return $urls[$slug] ?? '';
}

function directory_telegram_bot_anchor_html(string $url, string $label): string
{
    $url = htmlspecialchars($url, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $label = htmlspecialchars($label, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

    return '<a href="' . $url . '" rel="noopener noreferrer" title="' . $label . '" target="_blank">' . $label . '</a>';
}

function directory_mixer_resells_override(string $slug): string
{
    static $resellers = [
        'mixer-money' => 'Jambler',
        'mixtum' => 'Jambler',
        'webmixer' => 'Jambler',
        'mixtura-money' => 'Jambler',
        'mixy-money' => 'Jambler',
        'dreammixer' => 'Jambler',
        'thormixer' => 'Jambler',
        'okmix' => 'Jambler',
        'trustmixer' => 'Jambler',
        'bmix' => 'Jambler',
        'dreadpirate' => 'Jambler',
    ];

    return $resellers[$slug] ?? '';
}

function directory_upsert_resells_fact(array $facts, string $label, string $value): array
{
    foreach ($facts as $index => $fact) {
        if (directory_is_resells_fact_label((string) ($fact['label'] ?? ''))) {
            return $facts;
        }
    }

    $insertAt = count($facts);
    foreach ($facts as $index => $fact) {
        if (directory_is_coin_fact_label_for_extract((string) ($fact['label'] ?? ''))) {
            $insertAt = $index + 1;
            break;
        }
    }

    array_splice($facts, $insertAt, 0, [[
        'label' => $label,
        'value' => $value,
        'html' => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
    ]]);

    return $facts;
}

function directory_is_resells_fact_label(string $label): bool
{
    return in_array(mb_strtolower(trim($label), 'UTF-8'), ['resells', 'реселл'], true);
}

function directory_is_mixing_fee_fact_label_for_extract(string $label): bool
{
    $normalized = directory_normalize_extract_label($label);
    return in_array($normalized, ['mixing fee', 'плата за миксинг', 'комиссия за смешивание'], true);
}

function directory_is_withdraw_fee_fact_label_for_extract(string $label): bool
{
    $normalized = directory_normalize_extract_label($label);
    return $normalized === 'withdraw fee'
        || str_contains($normalized, 'withdraw fee')
        || (str_contains($normalized, 'комисс') && (str_contains($normalized, 'снят') || str_contains($normalized, 'вывод')));
}

function directory_withdraw_fee_value_is_none(string $value): bool
{
    return in_array(directory_normalize_extract_label($value), [
        'none',
        'no',
        'нет',
        '0',
        '0₿',
        '0 btc',
        'not stated',
        'not specified',
        'n/a',
        'не указано',
        'не указан',
        'не указана',
        'н/д',
    ], true);
}

function directory_normalize_extract_label(string $label): string
{
    $label = html_entity_decode($label, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $label = preg_replace('/\s+/u', ' ', $label) ?? $label;
    return mb_strtolower(trim($label), 'UTF-8');
}

function directory_is_coin_fact_label_for_extract(string $label): bool
{
    $normalized = directory_normalize_extract_label($label);
    return $normalized === 'coins' || $normalized === 'монеты' || str_contains($normalized, 'монет');
}

function directory_is_minimum_fact_label_for_extract(string $label): bool
{
    $normalized = directory_normalize_extract_label($label);
    return in_array($normalized, ['minimum', 'минимум'], true);
}

function directory_is_maximum_fact_label_for_extract(string $label): bool
{
    $normalized = directory_normalize_extract_label($label);
    return in_array($normalized, ['maximum', 'максимум'], true);
}

function directory_is_telegram_bot_fact_label_for_extract(string $label): bool
{
    $normalized = directory_normalize_extract_label($label);
    return in_array($normalized, ['telegram bot', 'телеграм-бот', 'telegram-бот', 'telegram бот'], true);
}

function directory_extract_tool_table_rows(DOMXPath $xpath, DOMNode $section, string $categorySlug): array
{
    $table = directory_first($xpath, './/table', $section);
    if (!$table) {
        $table = directory_first($xpath, '//table[' . directory_xpath_class('directory-comparison-table--' . $categorySlug) . ']');
    }
    if (!$table) {
        return [];
    }

    $headers = [];
    foreach ($xpath->query('.//thead/tr/th', $table) ?: [] as $th) {
        $headers[] = directory_text($th);
    }

    $rows = [];
    foreach ($xpath->query('.//tbody/tr', $table) ?: [] as $tr) {
        $cells = [];
        foreach ($xpath->query('./td', $tr) ?: [] as $td) {
            $cells[] = $td;
        }
        if ($cells === []) {
            continue;
        }

        $name = directory_text($cells[0]);
        if ($name === '') {
            continue;
        }

        $rows[directory_key($name)] = [
            'links' => directory_service_links($xpath, $headers, $cells, null),
        ];
    }

    return $rows;
}

function directory_extract_entry_notes(DOMXPath $xpath, array $categories, ?DOMNodeList $tables): array
{
    $sections = directory_extract_note_sections($xpath, $categories);
    $result = [];

    foreach ($categories as $categorySlug => $category) {
        if (($category['type'] ?? 'service') !== 'service') {
            continue;
        }

        $table = $tables?->item((int) $category['table']);
        if (!$table) {
            continue;
        }

        $markerToEntry = directory_table_note_markers($xpath, $table);
        foreach ($sections[$categorySlug] ?? [] as $noteHtml) {
            $marker = directory_entry_note_marker($noteHtml);
            if ($marker === 0 || !isset($markerToEntry[$marker])) {
                continue;
            }

            $key = $markerToEntry[$marker];
            $result[$categorySlug][$key] = trim(($result[$categorySlug][$key] ?? '') . "\n" . directory_strip_entry_note_marker($noteHtml));
        }
    }

    return $result;
}

function directory_extract_section_cautions(string $path, array $categories, string $locale): array
{
    $xpath = directory_load_dom($path);
    $result = [];

    foreach ($xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " homepage-directory-section ")]') ?: [] as $section) {
        if (!$section instanceof DOMElement) {
            continue;
        }

        $slug = trim($section->getAttribute('data-category'));
        if ($slug === '' || !isset($categories[$slug])) {
            continue;
        }

        $notes = directory_first($xpath, './/*[contains(concat(" ", normalize-space(@class), " "), " homepage-section-notes ")]', $section);
        if (!$notes) {
            continue;
        }

        foreach ($xpath->query('.//*[' . directory_xpath_class('alert-warning') . ' or ' . directory_xpath_class('prominent-warning') . ' or ' . directory_xpath_class('directory-caution') . ']', $notes) ?: [] as $node) {
            if (!$node instanceof DOMElement) {
                continue;
            }

            $html = directory_caution_block_from_node($node, $locale);
            if ($html !== '') {
                $result[$slug] = trim(($result[$slug] ?? '') . "\n" . $html);
            }
        }
    }

    return $result;
}

function directory_caution_block_from_node(DOMElement $node, string $locale): string
{
    $clone = $node->cloneNode(true);
    if (!$clone instanceof DOMElement) {
        return '';
    }

    $cloneXpath = new DOMXPath($clone->ownerDocument);
    $heading = directory_first($cloneXpath, './/*[self::h1 or self::h2 or self::h3 or self::h4 or self::h5 or self::h6]', $clone);
    $headingText = directory_text($heading);
    if ($heading && $heading->parentNode) {
        $heading->parentNode->removeChild($heading);
    }

    if ($headingText === '') {
        $headingText = $locale === 'ru' ? 'Осторожно' : 'Caution';
    }

    $body = trim(directory_inner_html($clone));
    if ($body === '') {
        $body = '<p>' . htmlspecialchars(directory_text($node), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8') . '</p>';
    }

    return '<div class="directory-caution" role="note">
<h3>' . htmlspecialchars($headingText, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8') . '</h3>
' . $body . '
</div>';
}

function directory_extract_note_sections(DOMXPath $xpath, array $categories): array
{
    $sections = [];
    foreach ($xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " homepage-section-notes ")]') ?: [] as $notesNode) {
        $section = $notesNode->parentNode;
        while ($section instanceof DOMElement && !directory_node_has_class($section, 'homepage-directory-section')) {
            $section = $section->parentNode;
        }
        if (!$section instanceof DOMElement) {
            continue;
        }

        $slug = trim($section->getAttribute('data-category'));
        if ($slug === '') {
            continue;
        }

        foreach ($xpath->query('.//p', $notesNode) ?: [] as $paragraph) {
            $html = $paragraph->ownerDocument->saveHTML($paragraph);
            if (directory_entry_note_marker($html) > 0) {
                $sections[$slug][] = $html;
            }
        }
    }

    $notesNode = directory_first($xpath, '//*[contains(concat(" ", normalize-space(@class), " "), " homepage-notes ")]');
    if (!$notesNode) {
        return $sections;
    }

    $headingMap = directory_section_heading_map($categories);
    $currentSlug = null;
    foreach ($notesNode->childNodes as $child) {
        if ($child instanceof DOMElement && in_array(strtolower($child->tagName), ['h2', 'h3'], true)) {
            $currentSlug = $headingMap[directory_normalize_heading($child->textContent)] ?? null;
            continue;
        }

        if ($currentSlug === null || !$child instanceof DOMElement || strtolower($child->tagName) !== 'p') {
            continue;
        }

        $html = $child->ownerDocument->saveHTML($child);
        if (directory_entry_note_marker($html) > 0) {
            $sections[$currentSlug][] = $html;
        }
    }

    return $sections;
}

function directory_table_note_markers(DOMXPath $xpath, DOMNode $table): array
{
    $markers = [];
    foreach ($xpath->query('.//tbody/tr', $table) ?: [] as $tr) {
        $cell = directory_first($xpath, './td[1]', $tr);
        $display = directory_text($cell);
        if ($display === '' || preg_match('/(\*+)$/u', $display, $match) !== 1) {
            continue;
        }

        $markers[strlen($match[1])] = directory_key($display);
    }

    return $markers;
}

function directory_entry_note_marker(string $html): int
{
    $text = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? '');
    if (preg_match('/^(\*+)/u', $text, $match) !== 1) {
        return 0;
    }

    return strlen($match[1]);
}

function directory_strip_entry_note_marker(string $html): string
{
    $dom = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="UTF-8"><body>' . $html . '</body>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOWARNING | LIBXML_NOERROR);
    libxml_clear_errors();

    $body = $dom->getElementsByTagName('body')->item(0);
    if (!$body) {
        return preg_replace('/^\s*\*+\s*/u', '', $html) ?? $html;
    }

    $walker = function (DOMNode $node) use (&$walker): ?DOMText {
        if ($node instanceof DOMText && preg_match('/^\s*\*+/u', $node->nodeValue) === 1) {
            return $node;
        }

        foreach ($node->childNodes as $child) {
            $found = $walker($child);
            if ($found) {
                return $found;
            }
        }

        return null;
    };

    $textNode = $walker($body);
    if ($textNode) {
        $textNode->nodeValue = preg_replace('/^\s*\*+\s*/u', '', $textNode->nodeValue, 1) ?? $textNode->nodeValue;
    }

    return trim(directory_inner_html($body));
}

function directory_extract_existing_entry_notes(string $path): string
{
    if (!is_file($path)) {
        return '';
    }

    $xpath = directory_load_dom($path);
    $section = directory_first($xpath, '//*[contains(concat(" ", normalize-space(@class), " "), " directory-entry-notes ")]');
    if (!$section) {
        return '';
    }

    $body = directory_first($xpath, './/*[contains(concat(" ", normalize-space(@class), " "), " directory-note ")]', $section);
    if ($body) {
        $clone = $body->cloneNode(true);
        if (!$clone instanceof DOMElement) {
            return '';
        }

        directory_remove_generated_entry_notes($clone);

        return trim(directory_inner_html($clone));
    }

    $clone = $section->cloneNode(true);
    if (!$clone instanceof DOMElement) {
        return '';
    }

    $cloneXpath = new DOMXPath($clone->ownerDocument);
    $heading = directory_first($cloneXpath, './h2', $clone);
    if ($heading && $heading->parentNode) {
        $heading->parentNode->removeChild($heading);
    }

    directory_remove_generated_entry_notes($clone);

    return trim(directory_inner_html($clone));
}

function directory_remove_generated_entry_notes(DOMElement $container): void
{
    $remove = [];
    $walk = static function (DOMNode $node) use (&$walk, &$remove): void {
        foreach ($node->childNodes as $child) {
            if (!$child instanceof DOMElement) {
                continue;
            }

            $isParagraph = strtolower($child->tagName) === 'p';
            if ($child->hasAttribute('data-generated-entry-note') || ($isParagraph && directory_is_generated_entry_note_text(directory_text($child)))) {
                $remove[] = $child;
                continue;
            }

            $walk($child);
        }
    };

    $walk($container);

    foreach ($remove as $node) {
        if ($node->parentNode) {
            $node->parentNode->removeChild($node);
        }
    }
}

function directory_is_generated_entry_note_text(string $text): bool
{
    $normalized = preg_replace('/\s+/u', ' ', trim($text)) ?? '';
    if ($normalized === '') {
        return false;
    }

    $markers = [
        'Exchanges made through Orangefren links on this page are covered by the Orangefren Guarantee',
        'Trocador is not covered by the Orangefren Guarantee',
        'Обмены, совершенные через ссылки Orangefren на этой странице, покрываются гарантией Orangefren',
        'Trocador не покрывается гарантией Orangefren',
    ];

    foreach ($markers as $marker) {
        if (str_contains($normalized, $marker)) {
            return true;
        }
    }

    return false;
}

function directory_extract_existing_section_cautions(string $path): string
{
    if (!is_file($path)) {
        return '';
    }

    $xpath = directory_load_dom($path);
    $blocks = [];
    foreach ($xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " directory-caution ")]') ?: [] as $node) {
        if (!$node instanceof DOMElement) {
            continue;
        }

        $html = $node->ownerDocument->saveHTML($node);
        if (is_string($html) && trim($html) !== '') {
            $blocks[] = trim($html);
        }
    }

    return trim(implode("\n", $blocks));
}

function directory_extract_existing_entry_config(string $path): string
{
    if (!is_file($path)) {
        return '';
    }

    $xpath = directory_load_dom($path);
    $body = directory_first($xpath, '//*[contains(concat(" ", normalize-space(@class), " "), " directory-entry-config ")]//*[contains(concat(" ", normalize-space(@class), " "), " directory-config ")]');
    if ($body) {
        return trim(directory_inner_html($body));
    }

    foreach ($xpath->query('//tr[th and td]') ?: [] as $row) {
        $header = directory_first($xpath, './th', $row);
        if (!directory_is_config_header(directory_text($header))) {
            continue;
        }

        $cell = directory_first($xpath, './td', $row);
        if (!$cell) {
            return '';
        }

        return directory_flat_config_to_list(directory_text($cell));
    }

    return '';
}

function directory_extract_existing_entry_support(string $path): array
{
    if (!is_file($path)) {
        return ['support' => '', 'support_html' => ''];
    }

    $xpath = directory_load_dom($path);
    foreach ($xpath->query('//tr[th and td]') ?: [] as $row) {
        $header = directory_first($xpath, './th', $row);
        if (!directory_is_support_header(directory_text($header))) {
            continue;
        }

        $cell = directory_first($xpath, './td', $row);
        if (!$cell) {
            return ['support' => '', 'support_html' => ''];
        }

        return [
            'support' => directory_text($cell),
            'support_html' => directory_inner_html($cell),
        ];
    }

    return ['support' => '', 'support_html' => ''];
}

function directory_extract_existing_entry_mirrors(string $path): array
{
    if (!is_file($path)) {
        return [];
    }

    $xpath = directory_load_dom($path);
    foreach ($xpath->query('//tr[th and td]') ?: [] as $row) {
        $header = directory_first($xpath, './th', $row);
        if (!directory_is_mirrors_header(directory_text($header))) {
            continue;
        }

        $cell = directory_first($xpath, './td', $row);
        if (!$cell) {
            return [];
        }

        $mirrors = [];
        foreach ($xpath->query('.//a[@href]', $cell) ?: [] as $anchor) {
            if (!$anchor instanceof DOMElement) {
                continue;
            }

            $url = html_entity_decode($anchor->getAttribute('href'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            if ($url === '') {
                continue;
            }

            $mirrors[] = [
                'label' => directory_text($anchor),
                'url' => $url,
            ];
        }

        return $mirrors;
    }

    return [];
}

function directory_is_mirrors_header(string $text): bool
{
    $text = mb_strtolower(trim($text), 'UTF-8');
    return in_array($text, ['known mirrors', 'зеркала'], true);
}

function directory_mixer_support_override(string $slug): array
{
    $support = [
        'bitxer' => [
            'support' => 'bitxer.io@protonmail.com / PGP / PGP checker',
            'support_html' => '<a href="mailto:bitxer.io@protonmail.com">bitxer.io@protonmail.com</a> / <a href="https://www.bitxer.io/files/pgp/en/pgp-key.txt">PGP</a> / <a href="https://bitlist.co/service/bitxer/verify-pgp">PGP checker</a>',
        ],
        'bmix' => [
            'support' => '@bMixIoSupport / bmixio@proton.me',
            'support_html' => '<a href="https://t.me/bMixIoSupport">@bMixIoSupport</a> / <a href="mailto:bmixio@proton.me">bmixio@proton.me</a>',
        ],
        'dreadpirate' => [
            'support' => 'PGP / Jabber: DreadPirate@exploit.im / TOX: 100ED03114B7DF7AA7FD477FFE97A1AF6D4807A24C9998333157B8EB144B3A4D4BF8E6FB00B1',
            'support_html' => '<a href="https://dreadpirate.io/pgp">PGP</a> / Jabber: DreadPirate@exploit.im / TOX: <a href="tox:100ED03114B7DF7AA7FD477FFE97A1AF6D4807A24C9998333157B8EB144B3A4D4BF8E6FB00B1" title="100ED03114B7DF7AA7FD477FFE97A1AF6D4807A24C9998333157B8EB144B3A4D4BF8E6FB00B1">100ED03114B7DF7AA7FD477FFE97A1AF6D4807A24C9998333157B8EB144B3A4D4BF8E6FB00B1</a>',
        ],
        'jokermix' => [
            'support' => 'jokermix@tuta.io / @JokerMixPrivacy',
            'support_html' => '<a href="mailto:jokermix@tuta.io">jokermix@tuta.io</a> / <a href="https://t.me/JokerMixPrivacy">@JokerMixPrivacy</a>',
        ],
        'mixer-black' => [
            'support' => 'Chat / PGP',
            'support_html' => '<a href="https://mixer.black/en/chat">Chat</a> / <a href="https://mixer.black/en/pgp">PGP</a>',
        ],
        'mixtwix' => [
            'support' => '@mixtwix_service / mixtwix@conversations.im / PGP',
            'support_html' => '<a href="https://t.me/mixtwix_service">@mixtwix_service</a> / <a href="xmpp:mixtwix@conversations.im">mixtwix@conversations.im</a> / <a href="https://mixtwix.io/wp-content/themes/mixtwix/assets/pgp/pgp-key.txt">PGP</a>',
        ],
        'trustmixer' => [
            'support' => 'info@trustmixer.io / t.me/trustmixer',
            'support_html' => '<a href="mailto:info@trustmixer.io">info@trustmixer.io</a> / <a href="https://t.me/trustmixer">t.me/trustmixer</a>',
        ],
        'zeusmix' => [
            'support' => '@ZeusMixTor / zeusmix@mailum.com',
            'support_html' => '<a href="https://t.me/ZeusMixTor">@ZeusMixTor</a> / <a href="mailto:zeusmix@mailum.com">zeusmix@mailum.com</a>',
        ],
    ];

    return $support[$slug] ?? [];
}

function directory_mixer_support_override_is_authoritative(string $slug): bool
{
    return in_array($slug, ['dreadpirate', 'jokermix', 'zeusmix'], true);
}

function directory_mixer_link_override(string $slug): array
{
    $links = [
        'jokermix' => [
            'tor' => 'http://jokerrj25jeuks7rqodsyo2xofsasakra2naaa4axb3cyb4j333ya5yd.onion/',
        ],
        'zeusmix' => [
            'tor' => 'http://zeus4sdq2cs4yd2uo5jqcl3zs74nxktspkikyisxsmlqyojo25pcmiid.onion/',
        ],
    ];

    return $links[$slug] ?? [];
}

function directory_entry_status_override(string $categorySlug, string $slug): array
{
    if ($categorySlug === 'mixers' && $slug === 'dreadpirate') {
        return [
            'type' => 'maintenance',
            'label' => [
                'en' => 'Under maintenance',
                'ru' => 'На обслуживании',
            ],
            'action_label' => [
                'en' => 'Under maintenance',
                'ru' => 'На обслуживании',
            ],
            'title' => [
                'en' => 'DreadPirate is under maintenance',
                'ru' => 'DreadPirate на обслуживании',
            ],
            'lead' => [
                'en' => 'DreadPirate is not accepting new mixes or transactions while it works through reported technical issues.',
                'ru' => 'DreadPirate временно не принимает новые миксы или транзакции из-за заявленных технических проблем.',
            ],
            'items' => [
                'en' => [
                    'DreadPirate says unfinished mixes will be refunded to the sending addresses, not to addresses specified in PGP messages.',
                    'The 0.5 BTC XSS deposit still appears intact, and there is no stated plan to request a deposit refund, so BitMixList is treating this as a technical pause.',
                    'Refund timing for unfinished mixes has not been confirmed yet.',
                ],
                'ru' => [
                    'DreadPirate сообщает, что незавершенные миксы будут возвращены на адреса отправки, а не на адреса, указанные в PGP-сообщениях.',
                    'Депозит 0.5 BTC на XSS, насколько известно BitMixList, остается в порядке; о запросе возврата депозита не сообщалось, поэтому это помечено как техническая пауза.',
                    'Срок возврата незавершенных миксов пока не подтвержден.',
                ],
            ],
            'source' => [
                'label' => [
                    'en' => 'AltcoinsTalks notice',
                    'ru' => 'уведомление на AltcoinsTalks',
                ],
                'url' => 'https://www.altcoinstalks.com/index.php?topic=339855.msg2123130#msg2123130',
            ],
        ];
    }

    if ($categorySlug === 'neverkyc-exchanges' && $slug === 'tomboi-io') {
        return [
            'type' => 'scam-accusation',
            'label' => [
                'en' => 'Scam Accusation',
                'ru' => 'Scam Accusation',
            ],
            'action_label' => [
                'en' => 'Scam Accusation',
                'ru' => 'Scam Accusation',
            ],
            'title' => [
                'en' => 'Tomboi.io has an active scam accusation',
                'ru' => 'Для Tomboi.io опубликована scam accusation',
            ],
            'lead' => [
                'en' => 'A scam accusation thread has been posted for Tomboi.io on Bitcointalk. Review the thread before using the service.',
                'ru' => 'На Bitcointalk опубликована тема scam accusation по Tomboi.io. Изучите тему перед использованием сервиса.',
            ],
            'items' => [
                'en' => [
                    'BitMixList is flagging this service while the accusation is public and unresolved.',
                    'The service link is disabled from BitMixList until this warning is reviewed again.',
                ],
                'ru' => [
                    'BitMixList помечает этот сервис, пока обвинение публично и не закрыто.',
                    'Ссылка на сервис отключена на BitMixList до повторного пересмотра предупреждения.',
                ],
            ],
            'source' => [
                'label' => [
                    'en' => 'Bitcointalk scam accusation thread',
                    'ru' => 'тема scam accusation на Bitcointalk',
                ],
                'url' => 'https://bitcointalk.org/index.php?topic=5584545.msg66788575#msg66788575',
            ],
        ];
    }

    return [];
}

function directory_expand_tox_support(array $links): array
{
    $html = (string) ($links['support_html'] ?? '');
    if ($html === '') {
        return $links;
    }

    if (preg_match_all('/tox:([A-Fa-f0-9]+)/', $html, $matches) === false || $matches[1] === []) {
        return $links;
    }

    foreach ($matches[1] as $toxId) {
        $links['support'] = str_replace('Add contact', $toxId, (string) ($links['support'] ?? ''));
        $links['support_html'] = preg_replace(
            '/(<a\b[^>]*href=["\']tox:' . preg_quote($toxId, '/') . '["\'][^>]*>)(.*?)(<\/a>)/iu',
            '${1}' . $toxId . '${3}',
            (string) ($links['support_html'] ?? '')
        ) ?? (string) ($links['support_html'] ?? '');
    }

    return $links;
}

function directory_section_heading_map(array $categories): array
{
    $map = [];
    foreach ($categories as $slug => $category) {
        foreach (['index_label', 'title', 'nav_label'] as $key) {
            foreach (['en', 'ru'] as $locale) {
                $label = $category[$key][$locale] ?? '';
                if ($label !== '') {
                    $map[directory_normalize_heading($label)] = $slug;
                }
            }
        }
    }

    foreach ([
        'Список бирж, которые никогда не требуют KYC' => 'neverkyc-exchanges',
        'Список P2P маркетплейсов' => 'p2p-markets',
        'The List Of Wasabi Coordinators' => 'coordinators',
    ] as $heading => $slug) {
        $map[directory_normalize_heading($heading)] = $slug;
    }

    return $map;
}

function directory_normalize_heading(string $heading): string
{
    $heading = preg_replace('/\s+/u', ' ', html_entity_decode($heading, ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? $heading;
    return mb_strtolower(trim($heading), 'UTF-8');
}

function directory_node_has_class(DOMElement $node, string $class): bool
{
    return str_contains(' ' . preg_replace('/\s+/u', ' ', $node->getAttribute('class')) . ' ', ' ' . $class . ' ');
}

function directory_extract_mirrors(DOMXPath $xpath): array
{
    $mirrors = [];
    $detailsNodes = $xpath->query('//details[' . directory_xpath_class('mirror-details') . ']');

    foreach ($detailsNodes ?: [] as $details) {
        $summary = directory_text(directory_first($xpath, './summary', $details));
        if (
            !preg_match('/mirror|url/i', $summary)
            && !preg_match('/зеркал|url|адрес/ui', $summary)
        ) {
            continue;
        }

        foreach ($xpath->query('.//li[strong]', $details) ?: [] as $li) {
            $strong = directory_first($xpath, './strong', $li);
            $name = directory_text($strong);
            if ($name === '' || preg_match('/bitmixlist/i', $name)) {
                continue;
            }

            $items = [];
            foreach ($xpath->query('.//a[@href]', $li) ?: [] as $anchor) {
                if (!$anchor instanceof DOMElement) {
                    continue;
                }
                $items[] = [
                    'label' => directory_text($anchor),
                    'url' => html_entity_decode($anchor->getAttribute('href'), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                ];
            }

            if ($items !== []) {
                $mirrors[directory_key($name)] = $items;
            }
        }
    }

    return $mirrors;
}

function directory_table_facts(DOMXPath $xpath, array $headers, array $cells, string $categorySlug): array
{
    $facts = [];
    foreach ($cells as $index => $cell) {
        $label = $headers[$index] ?? 'Field';
        if (
            $index === 0
            || directory_is_website_header($label)
            || directory_is_tor_header($label)
            || directory_is_support_header($label)
            || directory_is_status_header($label)
            || directory_is_config_header($label)
            || ($categorySlug === 'mixers' && directory_is_support_channel_header($label))
        ) {
            continue;
        }

        $value = directory_table_cell_value($xpath, $cell);
        if ($value === '') {
            continue;
        }

        $facts[] = [
            'label' => $label,
            'value' => $value,
            'html' => directory_unwrap_cell_value_html(directory_inner_html($cell)),
        ];
    }

    return $facts;
}

function directory_unwrap_cell_value_html(string $html): string
{
    $html = trim($html);

    while (preg_match('/^<span\s+class=(["\'])directory-cell-value\1>(.*)<\/span>$/is', $html, $matches) === 1) {
        $html = trim($matches[2]);
    }

    return $html;
}

function directory_table_cell_value(DOMXPath $xpath, DOMNode $cell): string
{
    $coinList = directory_first($xpath, './/*[contains(concat(" ", normalize-space(@class), " "), " coin-list ")][@aria-label]', $cell);
    if ($coinList instanceof DOMElement) {
        return trim(html_entity_decode($coinList->getAttribute('aria-label'), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    return directory_text($cell);
}

function directory_table_config(DOMXPath $xpath, array $headers, array $cells): string
{
    foreach ($cells as $index => $cell) {
        $label = $headers[$index] ?? '';
        if (!directory_is_config_header($label)) {
            continue;
        }

        return directory_extract_config_html($xpath, $cell);
    }

    return '';
}

function directory_extract_config_html(DOMXPath $xpath, DOMNode $cell): string
{
    $source = directory_first($xpath, './/*[contains(concat(" ", normalize-space(@class), " "), " config-popover ")]', $cell) ?? $cell;
    $clone = $source->cloneNode(true);
    if (!$clone instanceof DOMElement) {
        return '';
    }

    $cloneXpath = new DOMXPath($clone->ownerDocument);
    $remove = [];
    foreach ($cloneXpath->query('.//button', $clone) ?: [] as $button) {
        $remove[] = $button;
    }
    foreach ($cloneXpath->query('./strong', $clone) ?: [] as $strong) {
        $text = directory_normalize_heading($strong->textContent);
        if ($text === 'config' || $text === 'конфигурация') {
            $remove[] = $strong;
        }
    }
    foreach ($remove as $node) {
        $node->parentNode?->removeChild($node);
    }

    $html = trim(directory_inner_html($clone));
    if ($html !== '') {
        return $html;
    }

    return directory_flat_config_to_list(directory_text($cell));
}

function directory_flat_config_to_list(string $text): string
{
    $text = trim(preg_replace('/\s+/u', ' ', $text) ?? '');
    if ($text === '') {
        return '';
    }

    $text = preg_replace('/^(Show Config|Показать конфигурацию)\s*/u', '', $text) ?? $text;
    $text = preg_replace('/^✕\s*/u', '', $text) ?? $text;
    $text = preg_replace('/^(Config|Конфигурация)\s*/u', '', $text) ?? $text;
    $labels = [
        'Allowed Input Amounts',
        'Allowed Input Types',
        'Allowed Output Amounts',
        'Allowed Output Types',
        'Minimum Inputs',
        'Maximum Inputs',
        'Maximum Round Registration Time',
        'Разрешённые суммы ввода',
        'Разрешенные суммы ввода',
        'Разрешённые типы ввода',
        'Разрешенные типы ввода',
        'Разрешённые суммы вывода',
        'Разрешенные суммы вывода',
        'Разрешённые типы вывода',
        'Разрешенные типы вывода',
        'Минимальное количество входов',
        'Максимальное количество входов',
        'Максимальное время регистрации раунда',
    ];
    $pattern = '/(?=(' . implode('|', array_map(static fn (string $label): string => preg_quote($label, '/'), $labels)) . '):)/u';
    $parts = array_values(array_filter(array_map('trim', preg_split($pattern, $text) ?: [])));

    if ($parts === []) {
        return '<p>' . htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8') . '</p>';
    }

    $items = '';
    foreach ($parts as $part) {
        $items .= '<li>' . htmlspecialchars($part, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8') . '</li>' . "\n";
    }

    return '<ul>
' . trim($items) . '
</ul>';
}

function directory_service_links(DOMXPath $xpath, array $headers, array $cells, ?DOMNode $fallbackNameNode): array
{
    $links = [
        'clearnet' => '',
        'tor' => 'No',
        'mirrors' => [],
        'support' => '',
        'support_html' => '',
    ];

    foreach ($cells as $index => $cell) {
        $label = $headers[$index] ?? '';
        $anchor = directory_first($xpath, './/a[@href]', $cell);
        $value = directory_text($cell);

        if (directory_is_website_header($label) && $anchor instanceof DOMElement) {
            $links['clearnet'] = html_entity_decode($anchor->getAttribute('href'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        if (directory_is_tor_header($label)) {
            $links['tor'] = 'No';
            if ($anchor instanceof DOMElement) {
                $links['tor'] = html_entity_decode($anchor->getAttribute('href'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
        }

        if (directory_is_support_header($label)) {
            $links['support'] = $value;
            $links['support_html'] = directory_inner_html($cell);
        }
    }

    if ($links['clearnet'] === '' && $fallbackNameNode instanceof DOMElement) {
        $links['clearnet'] = html_entity_decode($fallbackNameNode->getAttribute('href'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    return $links;
}

function directory_is_website_header(string $label): bool
{
    $normalized = mb_strtolower($label, 'UTF-8');
    return str_contains($normalized, 'website')
        || str_contains($normalized, 'веб')
        || str_contains($normalized, 'coordinator link')
        || str_contains($normalized, 'url координатора');
}

function directory_is_tor_header(string $label): bool
{
    $normalized = mb_strtolower($label, 'UTF-8');
    $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $normalized);
    if ($ascii === false) {
        $ascii = $normalized;
    }
    $folded = strtr($normalized, [
        'т' => 't',
        'о' => 'o',
        'р' => 'p',
        'с' => 'c',
        'а' => 'a',
        'е' => 'e',
        'у' => 'y',
        'х' => 'x',
    ]);

    return preg_match('/(^|[^a-z])tor([^a-z]|$)/', $normalized) === 1
        || preg_match('/(^|[^a-z])tor([^a-z]|$)/', strtolower($ascii)) === 1
        || preg_match('/(^|[^a-z])tor([^a-z]|$)/', $folded) === 1;
}

function directory_is_support_header(string $label): bool
{
    $normalized = mb_strtolower($label, 'UTF-8');
    return str_contains($normalized, 'support') || str_contains($normalized, 'поддерж');
}

function directory_is_status_header(string $label): bool
{
    $normalized = mb_strtolower(trim($label), 'UTF-8');
    return in_array($normalized, ['status', 'live status', 'статус', 'доступность'], true);
}

function directory_is_support_channel_header(string $label): bool
{
    $normalized = mb_strtolower(trim($label), 'UTF-8');
    return in_array($normalized, [
        'email',
        'telegram',
        'jabber/xmpp',
        'tox',
        'pgp',
        'pgp checker',
        'chat',
    ], true);
}

function directory_is_config_header(string $label): bool
{
    $normalized = mb_strtolower($label, 'UTF-8');
    return str_contains($normalized, 'config') || str_contains($normalized, 'конфигурац');
}

function directory_entries_by_category(array $entries): array
{
    $byCategory = [];
    foreach ($entries as $entry) {
        $byCategory[$entry['category']][] = $entry;
    }

    return $byCategory;
}

function directory_validate_data(array $data, string $root, bool $requireOutputs = false): array
{
    $errors = [];
    $seen = [];

    foreach ($data['entries'] as $entry) {
        $categorySlug = $entry['category'];
        $slug = $entry['slug'];
        $key = $categorySlug . '/' . $slug;

        if (isset($seen[$key])) {
            $errors[] = "Duplicate slug: {$key}";
        }
        $seen[$key] = true;

        foreach (['en', 'ru'] as $locale) {
            if (($entry['content'][$locale]['name'] ?? '') === '') {
                $errors[] = "Missing {$locale} name for {$key}";
            }
            if (!isset($entry['facts'][$locale]) || $entry['facts'][$locale] === []) {
                $errors[] = "Missing {$locale} facts for {$key}";
            }
            if ($requireOutputs && !is_file($root . '/' . $entry['output_paths'][$locale])) {
                $errors[] = "Missing generated output: {$entry['output_paths'][$locale]}";
            }
        }

        $clearnet = $entry['links']['clearnet'] ?? '';
        if ($clearnet !== '' && !preg_match('~^https?://~i', $clearnet)) {
            $errors[] = "Invalid clearnet URL for {$key}: {$clearnet}";
        }

        $tor = $entry['links']['tor'] ?? 'No';
        if ($tor !== 'No' && !preg_match('~^https?://~i', $tor)) {
            $errors[] = "Invalid Tor URL for {$key}: {$tor}";
        }

        foreach ($entry['links']['mirrors'] ?? [] as $mirror) {
            $url = $mirror['url'] ?? '';
            if ($url !== '' && !preg_match('~^https?://~i', $url)) {
                $errors[] = "Invalid mirror URL for {$key}: {$url}";
            }
        }

        foreach (['webp', 'image'] as $assetKey) {
            $asset = $entry['assets'][$assetKey] ?? '';
            if ($asset === '') {
                continue;
            }
            $assetPath = $root . '/' . preg_replace('~^\.\./~', '', $asset);
            if (!is_file($assetPath)) {
                $errors[] = "Missing asset for {$key}: {$asset}";
            }
        }
    }

    return $errors;
}
