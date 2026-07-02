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
    $yifiSlug = 'yifi';
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
            'notices' => [
                [
                    'type' => 'notice',
                    'label' => [
                        'en' => 'Notice',
                        'ru' => 'Уведомление',
                    ],
                    'title' => [
                        'en' => 'altquick.com uses JavaScript',
                        'ru' => 'altquick.com использует JavaScript',
                    ],
                    'lead' => [
                        'en' => 'A clearnet check on June 22, 2026 found executable JavaScript on the public page for altquick.com.',
                        'ru' => 'Проверка clearnet-страницы 22 июня 2026 года обнаружила исполняемый JavaScript на публичной странице altquick.com.',
                    ],
                    'items' => [
                        'en' => [
                            'This is not a harmful-service warning; it is a browser-behavior note for users who disable JavaScript or prefer static pages.',
                        ],
                        'ru' => [
                            'Это не предупреждение о вредоносности сервиса; это пометка о поведении страницы для пользователей, которые отключают JavaScript или предпочитают статические страницы.',
                        ],
                    ],
                    'source' => [
                        'label' => [
                            'en' => 'altquick.com clearnet response',
                            'ru' => 'clearnet-ответ altquick.com',
                        ],
                        'url' => 'https://altquick.com/',
                    ],
                ],
            ],
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
        [
            'id' => $categorySlug . ':' . $yifiSlug,
            'slug' => $yifiSlug,
            'category' => $categorySlug,
            'type' => $type,
            'assets' => [
                'webp' => '',
                'image' => 'wp-content/uploads/2023/12/yifi.svg',
                'alt' => 'YiFi logo',
            ],
            'links' => [
                'clearnet' => 'https://yifi.io/',
                'tor' => 'No',
                'mirrors' => [],
                'support' => '@yifi_defi / @yifi_io',
                'support_html' => '<a href="https://t.me/yifi_defi">@yifi_defi</a> / <a href="https://x.com/yifi_io">@yifi_io</a>',
            ],
            'status' => [],
            'notices' => [
                [
                    'type' => 'notice',
                    'label' => [
                        'en' => 'Notice',
                        'ru' => 'Уведомление',
                    ],
                    'title' => [
                        'en' => 'yifi.io uses JavaScript',
                        'ru' => 'yifi.io использует JavaScript',
                    ],
                    'lead' => [
                        'en' => 'A clearnet check on July 2, 2026 found executable JavaScript on the public page for yifi.io.',
                        'ru' => 'Проверка clearnet-страницы 2 июля 2026 года обнаружила исполняемый JavaScript на публичной странице yifi.io.',
                    ],
                    'items' => [
                        'en' => [
                            'This is not a harmful-service warning; it is a browser-behavior note for users who disable JavaScript or prefer static pages.',
                        ],
                        'ru' => [
                            'Это не предупреждение о вредоносности сервиса; это пометка о поведении страницы для пользователей, которые отключают JavaScript или предпочитают статические страницы.',
                        ],
                    ],
                    'source' => [
                        'label' => [
                            'en' => 'yifi.io clearnet response',
                            'ru' => 'clearnet-ответ yifi.io',
                        ],
                        'url' => 'https://yifi.io/',
                    ],
                ],
            ],
            'content' => [
                'en' => [
                    'name' => 'YiFi',
                    'summary' => 'Fee: no YiFi platform fee',
                    'description' => 'YiFi is a swap and yield aggregator that routes across DEX, CEX, and private liquidity paths.',
                ],
                'ru' => [
                    'name' => 'YiFi',
                    'summary' => 'Комиссия: без комиссии YiFi',
                    'description' => 'YiFi — агрегатор обменов и доходности, который маршрутизирует сделки через DEX, CEX и приватные источники ликвидности.',
                ],
            ],
            'facts' => [
                'en' => [
                    directory_manual_fact('Founded', '2026'),
                    directory_manual_fact('Fee', 'No YiFi platform fee'),
                ],
                'ru' => [
                    directory_manual_fact('Основан', '2026'),
                    directory_manual_fact('Комиссия', 'Без комиссии YiFi'),
                ],
            ],
            'table_display' => [
                'en' => 'YiFi',
                'ru' => 'YiFi',
            ],
            'notes' => [
                'en' => '<p>The <a href="https://bitcointalk.org/index.php?topic=5579150.msg66573538#msg66573538">Bitcointalk announcement</a> describes YiFi as a swap aggregator for DEX, CEX, and private routes. It says private swaps use two independent providers and an XMR intermediate transfer to break the on-chain link between sender and receiver.</p><p>YiFi states that KYC requirements, if any, are imposed by integrated providers rather than YiFi. In the same thread, the operator said YiFi is non-custodial and that users may still need to follow a provider-specific process if a provider-side flag or freeze occurs.</p><p>The official site says YiFi does not charge an additional swap platform fee; network and exchange/liquidity-provider fees still apply.</p>',
                'ru' => '<p><a href="https://bitcointalk.org/index.php?topic=5579150.msg66573538#msg66573538">Анонс на Bitcointalk</a> описывает YiFi как агрегатор обменов для DEX, CEX и приватных маршрутов. В нем сказано, что приватные обмены используют двух независимых провайдеров и промежуточный перевод через XMR, чтобы разорвать on-chain связь между отправителем и получателем.</p><p>YiFi указывает, что требования KYC, если они возникают, задаются интегрированными провайдерами, а не самим YiFi. В той же теме оператор написал, что YiFi не хранит средства, а при флаге или заморозке на стороне провайдера пользователю все равно может потребоваться пройти процедуру конкретного провайдера.</p><p>Официальный сайт сообщает, что YiFi не взимает дополнительную комиссию платформы за обмен; сетевые комиссии и комиссии бирж/провайдеров ликвидности все равно применяются.</p>',
            ],
            'config' => [
                'en' => '',
                'ru' => '',
            ],
            'volume_history' => [],
            'index_paths' => [
                'en' => $categorySlug . '/' . $yifiSlug . '.html',
                'ru' => $categorySlug . '/' . $yifiSlug . '.html',
            ],
            'output_paths' => [
                'en' => $categorySlug . '/' . $yifiSlug . '.html',
                'ru' => 'ru/' . $categorySlug . '/' . $yifiSlug . '.html',
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
