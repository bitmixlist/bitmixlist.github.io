<?php

declare(strict_types=1);

function directory_manual_entry_removed_ids(): array
{
    return [
        'instant-exchanges:altquick',
    ];
}

function directory_manual_entries(array $categories): array
{
    $categorySlug = 'neverkyc-exchanges';
    $slug = 'altquick';
    $type = $categories[$categorySlug]['type'] ?? 'service';

    return [
        [
            'id' => $categorySlug . ':' . $slug,
            'slug' => $slug,
            'category' => $categorySlug,
            'type' => $type,
            'assets' => [
                'webp' => '',
                'image' => 'wp-content/uploads/2023/12/altquick-white-bg.png',
                'alt' => 'AltQuick logo',
            ],
            'links' => [
                'clearnet' => 'https://altquick.com/?aKey=72470881de66c2ee9eae13a4dcca5d791de4fd14',
                'tor' => 'No',
                'mirrors' => [],
                'support' => '',
                'support_html' => '',
            ],
            'status' => [],
            'notices' => [],
            'content' => [
                'en' => [
                    'name' => 'AltQuick',
                    'summary' => 'Fee: 0.5-1%',
                    'description' => '',
                ],
                'ru' => [
                    'name' => 'AltQuick',
                    'summary' => 'Комиссия: 0,5-1%',
                    'description' => '',
                ],
            ],
            'facts' => [
                'en' => [
                    directory_manual_fact('Founded', '2019'),
                    directory_manual_fact('Fee', '0.5-1%'),
                ],
                'ru' => [
                    directory_manual_fact('Основан', '2019'),
                    directory_manual_fact('Комиссия', '0,5-1%'),
                ],
            ],
            'table_display' => [
                'en' => 'AltQuick',
                'ru' => 'AltQuick',
            ],
            'notes' => [
                'en' => '',
                'ru' => '',
            ],
            'config' => [
                'en' => '',
                'ru' => '',
            ],
            'volume_history' => [],
            'index_paths' => [
                'en' => $categorySlug . '/' . $slug . '.html',
                'ru' => $categorySlug . '/' . $slug . '.html',
            ],
            'output_paths' => [
                'en' => $categorySlug . '/' . $slug . '.html',
                'ru' => 'ru/' . $categorySlug . '/' . $slug . '.html',
            ],
        ],
    ];
}

function directory_manual_fact(string $label, string $value): array
{
    return [
        'label' => $label,
        'value' => $value,
        'html' => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
    ];
}
