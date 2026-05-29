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

    return trim(preg_replace('/\s+/u', ' ', html_entity_decode($node->textContent, ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? '');
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

function directory_extract_all(string $root): array
{
    $categories = directory_categories();
    $locales = [
        'en' => $root . '/index.html',
        'ru' => $root . '/ru/index.html',
    ];

    $localized = [];
    foreach ($locales as $locale => $path) {
        $localized[$locale] = directory_extract_locale($path, $locale, $categories);
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
            $entries[] = [
                'id' => $categorySlug . ':' . $slug,
                'slug' => $slug,
                'category' => $categorySlug,
                'type' => $category['type'],
                'assets' => $entry['assets'],
                'links' => $entry['links'],
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
                'index_paths' => [
                    'en' => $categorySlug . '/' . $slug . '.html',
                    'ru' => $categorySlug . '/' . $slug . '.html',
                ],
                'output_paths' => [
                    'en' => $categorySlug . '/' . $slug . '.html',
                    'ru' => 'ru/' . $categorySlug . '/' . $slug . '.html',
                ],
            ];
        }
    }

    return [
        'categories' => $categories,
        'entries' => $entries,
    ];
}

function directory_extract_locale(string $path, string $locale, array $categories): array
{
    $xpath = directory_load_dom($path);
    $cardRows = $xpath->query('//div[' . directory_xpath_class('mixer-card-row') . ']');
    $tables = $xpath->query('//figure[' . directory_xpath_class('wp-block-table') . ']//table');
    $mirrorMap = directory_extract_mirrors($xpath);

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

        $result[$categorySlug] = directory_extract_service_category($xpath, $categorySlug, $locale, $cardRow, $table, $mirrorMap);
    }

    return $result;
}

function directory_extract_service_category(DOMXPath $xpath, string $categorySlug, string $locale, DOMNode $cardRow, DOMNode $table, array $mirrorMap): array
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

        $facts = directory_table_facts($xpath, $headers, $row['cells']);
        $links = directory_service_links($xpath, $headers, $row['cells'], $nameNode);
        $links['mirrors'] = $mirrorMap[directory_key($name)] ?? [];

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
                'summary' => directory_text($feeNode),
                'description' => '',
            ],
            'facts' => $facts,
            'table_display' => $row['display_name'],
        ];
    }

    return $entries;
}

function directory_extract_tools(DOMXPath $xpath, string $categorySlug, string $locale): array
{
    $entries = [];
    $query = '//section[' . directory_xpath_class('tool-registry') . ']//li[' . directory_xpath_class('tool') . ']';

    foreach ($xpath->query($query) ?: [] as $tool) {
        $nameNode = directory_first($xpath, './/a[' . directory_xpath_class('tool__name') . ']', $tool);
        $visitNode = directory_first($xpath, './/a[' . directory_xpath_class('tool-visit') . ']', $tool);
        $descNode = directory_first($xpath, './/p[' . directory_xpath_class('tool__desc') . ']', $tool);
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

        $entries[] = [
            'slug' => directory_slugify($name),
            'category' => $categorySlug,
            'assets' => [
                'webp' => '',
                'image' => '',
                'alt' => $name . ' logo',
            ],
            'links' => [
                'clearnet' => $external,
                'tor' => 'No',
                'mirrors' => [],
                'support' => '',
                'support_html' => '',
            ],
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
        ];
    }

    return $entries;
}

function directory_extract_mirrors(DOMXPath $xpath): array
{
    $mirrors = [];
    $detailsNodes = $xpath->query('//details[' . directory_xpath_class('mirror-details') . ']');

    foreach ($detailsNodes ?: [] as $details) {
        $summary = directory_text(directory_first($xpath, './summary', $details));
        if (!preg_match('/mirror/i', $summary) && !preg_match('/зеркал/ui', $summary)) {
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

function directory_table_facts(DOMXPath $xpath, array $headers, array $cells): array
{
    $facts = [];
    foreach ($cells as $index => $cell) {
        $label = $headers[$index] ?? 'Field';
        if ($index === 0 || directory_is_website_header($label) || directory_is_tor_header($label) || directory_is_support_header($label)) {
            continue;
        }

        $value = directory_text($cell);
        if ($value === '') {
            continue;
        }

        $facts[] = [
            'label' => $label,
            'value' => $value,
            'html' => directory_inner_html($cell),
        ];
    }

    return $facts;
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
