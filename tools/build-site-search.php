#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/search-index.php';

$root = dirname(__DIR__);

if (in_array('--check', $argv, true)) {
    bitmixlist_check_search_index($root);
    echo 'Search index is current.' . PHP_EOL;
    exit(0);
}

bitmixlist_write_search_index($root);
echo 'Search index written to site-search-index.json.' . PHP_EOL;
