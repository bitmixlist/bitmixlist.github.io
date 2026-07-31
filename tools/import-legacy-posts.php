#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/blog/import.php';
require_once __DIR__ . '/../src/blog/publish.php';

$root = dirname(__DIR__);
$dryRun = in_array('--dry-run', $argv, true);
$rebuild = in_array('--rebuild', $argv, true);
$only = null;

foreach ($argv as $arg) {
    if (str_starts_with($arg, '--only=')) {
        $only = array_values(array_filter(array_map('trim', explode(',', substr($arg, 7)))));
    }
}

$result = blog_import_legacy($root, $dryRun, $only);
$label = $dryRun ? 'Would import' : 'Imported';
echo $label . ' ' . count($result['imported']) . " post(s).\n";
foreach ($result['imported'] as $slug) {
    echo "  + {$slug}\n";
}
if ($result['skipped'] !== []) {
    echo 'Skipped ' . count($result['skipped']) . ":\n";
    foreach ($result['skipped'] as $s) {
        echo "  - {$s}\n";
    }
}

// Confirm utilities never appear
foreach (blog_utility_slugs() as $util) {
    if (in_array($util, $result['imported'], true)) {
        fwrite(STDERR, "ERROR: utility slug imported: {$util}\n");
        exit(1);
    }
}

if (!$dryRun && $rebuild) {
    $build = blog_build($root);
    blog_update_sitemap($root);
    echo 'Rebuilt ' . count($build['written']) . " public files.\n";
}

echo "Done.\n";
