<?php

declare(strict_types=1);

function bitmixlist_build_search_index(string $root): array
{
    $entries = [];
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($files as $file) {
        if (!$file->isFile() || strtolower($file->getExtension()) !== 'html') {
            continue;
        }

        $path = $file->getPathname();
        $relative = str_replace('\\', '/', substr($path, strlen($root) + 1));
        if (bitmixlist_search_should_skip($relative)) {
            continue;
        }

        $entry = bitmixlist_search_entry_from_file($path, $relative);
        if ($entry !== null) {
            $entries[] = $entry;
        }
    }

    usort($entries, static function (array $a, array $b): int {
        return [$a['locale'], $a['url'], $a['title']] <=> [$b['locale'], $b['url'], $b['title']];
    });

    return [
        'generated_at' => gmdate('c'),
        'entries' => $entries,
    ];
}

function bitmixlist_write_search_index(string $root): void
{
    $json = bitmixlist_search_index_json($root);
    file_put_contents($root . '/site-search-index.json', $json);
}

function bitmixlist_check_search_index(string $root): void
{
    $path = $root . '/site-search-index.json';
    if (!is_file($path)) {
        fwrite(STDERR, 'Missing site-search-index.json' . PHP_EOL);
        exit(1);
    }

    $expected = bitmixlist_search_index_json($root);
    $actual = file_get_contents($path);
    $actualComparable = preg_replace('/"generated_at":\s*"[^"]+"/', '"generated_at":""', $actual ?: '');
    $expectedComparable = preg_replace('/"generated_at":\s*"[^"]+"/', '"generated_at":""', $expected);

    if ($actualComparable !== $expectedComparable) {
        fwrite(STDERR, 'Search index is stale: site-search-index.json' . PHP_EOL);
        exit(1);
    }
}

function bitmixlist_search_index_json(string $root): string
{
    return json_encode(
        bitmixlist_build_search_index($root),
        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    ) . "\n";
}

function bitmixlist_search_should_skip(string $relative): bool
{
    return str_starts_with($relative, 'wp-content/')
        || str_starts_with($relative, 'src/')
        || str_starts_with($relative, 'tools/')
        || str_starts_with($relative, '.');
}

function bitmixlist_search_entry_from_file(string $path, string $relative): ?array
{
    $html = file_get_contents($path);
    if ($html === false || trim($html) === '') {
        return null;
    }

    $dom = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_NOWARNING | LIBXML_NOERROR);
    libxml_clear_errors();
    $xpath = new DOMXPath($dom);

    $title = bitmixlist_search_text($xpath->query('//title')->item(0) ?? null);
    $description = bitmixlist_search_meta($xpath, 'description');
    $heading = bitmixlist_search_text($xpath->query('//h1 | //h2')->item(0) ?? null);
    $locale = str_starts_with($relative, 'ru/') || preg_match('/<html[^>]*\slang=["\']ru/i', $html) === 1 ? 'ru' : 'en';
    $url = bitmixlist_search_url($relative);
    $section = bitmixlist_search_section($relative, $heading, $locale);
    $body = bitmixlist_search_body_text($dom);

    if ($title === '' && $description === '' && $body === '') {
        return null;
    }

    return [
        'title' => bitmixlist_search_clean_title($title, $heading),
        'url' => $url,
        'locale' => $locale,
        'section' => $section,
        'description' => $description !== '' ? $description : mb_substr($body, 0, 220, 'UTF-8'),
        'text' => mb_substr(trim($title . ' ' . $description . ' ' . $heading . ' ' . $body), 0, 3200, 'UTF-8'),
    ];
}

function bitmixlist_search_meta(DOMXPath $xpath, string $name): string
{
    $node = $xpath->query('//meta[translate(@name, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")="' . $name . '"]')->item(0);
    if (!$node instanceof DOMElement) {
        return '';
    }

    return bitmixlist_search_normalize($node->getAttribute('content'));
}

function bitmixlist_search_url(string $relative): string
{
    if ($relative === 'index.html') {
        return '/';
    }
    if ($relative === 'ru/index.html') {
        return '/ru/';
    }
    if (str_ends_with($relative, '/index.html')) {
        return '/' . substr($relative, 0, -strlen('index.html'));
    }

    return '/' . $relative;
}

function bitmixlist_search_section(string $relative, string $heading, string $locale): string
{
    $path = str_starts_with($relative, 'ru/') ? substr($relative, 3) : $relative;
    $segment = explode('/', $path)[0] ?? '';
    $labels = [
        'mixers' => ['en' => 'Mixers', 'ru' => 'Миксеры'],
        'neverkyc-exchanges' => ['en' => 'Exchange Never-KYC', 'ru' => 'Обмен без KYC'],
        'instant-exchanges' => ['en' => 'Exchange Instant', 'ru' => 'Обмен мгновенный'],
        'p2p-markets' => ['en' => 'P2P Marketplaces', 'ru' => 'P2P-площадки'],
        'coordinators' => ['en' => 'Coordinators', 'ru' => 'Координаторы'],
        'privacy-tools' => ['en' => 'Privacy Tools', 'ru' => 'Инструменты приватности'],
    ];

    if (isset($labels[$segment][$locale])) {
        return $labels[$segment][$locale];
    }

    return $heading !== '' ? $heading : ($locale === 'ru' ? 'Страница' : 'Page');
}

function bitmixlist_search_clean_title(string $title, string $heading): string
{
    $title = preg_replace('/\s+[-|]\s+BitMixList(?:\s*[-|].*)?$/u', '', $title) ?? $title;
    $title = bitmixlist_search_normalize($title);

    return $title !== '' ? $title : $heading;
}

function bitmixlist_search_body_text(DOMDocument $dom): string
{
    $xpath = new DOMXPath($dom);
    $excluded = [];
    foreach ($xpath->query('//*[@data-search-exclude]') ?: [] as $node) {
        $excluded[] = $node;
    }
    foreach ($excluded as $node) {
        $node->parentNode?->removeChild($node);
    }

    foreach ($xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " coin-list ")][@aria-label]') ?: [] as $node) {
        if (!$node instanceof DOMElement) {
            continue;
        }

        while ($node->firstChild) {
            $node->removeChild($node->firstChild);
        }
        $node->appendChild($dom->createTextNode(' ' . $node->getAttribute('aria-label') . ' '));
    }

    foreach (['script', 'style', 'noscript', 'nav', 'header', 'footer', 'svg'] as $tag) {
        $nodes = [];
        foreach ($dom->getElementsByTagName($tag) as $node) {
            $nodes[] = $node;
        }
        foreach ($nodes as $node) {
            $node->parentNode?->removeChild($node);
        }
    }

    $body = $dom->getElementsByTagName('body')->item(0);

    return bitmixlist_search_normalize($body ? $body->textContent : $dom->textContent);
}

function bitmixlist_search_text(?DOMNode $node): string
{
    return bitmixlist_search_normalize($node ? $node->textContent : '');
}

function bitmixlist_search_normalize(string $value): string
{
    return trim(preg_replace('/\s+/u', ' ', html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? '');
}
