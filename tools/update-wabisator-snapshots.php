#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/directory/extract.php';

$snapshotRoot = __DIR__ . '/../src/directory/snapshots';
$snapshots = [
    'wabisator-config.json' => directory_fetch_wabisator_config(),
    'wabisator-volume-history.json' => directory_fetch_wabisator_volume_history(),
];

foreach ($snapshots as $filename => $data) {
    $path = $snapshotRoot . '/' . $filename;
    $json = json_encode(
        $data,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
    ) . PHP_EOL;
    $temporary = $path . '.tmp';
    if (file_put_contents($temporary, $json, LOCK_EX) === false || !rename($temporary, $path)) {
        @unlink($temporary);
        throw new RuntimeException("Unable to update {$path}");
    }
    echo "Updated {$path}" . PHP_EOL;
}

echo "Review the snapshot diff and run php tools/build-directory.php before committing." . PHP_EOL;

