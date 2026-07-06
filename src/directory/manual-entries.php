<?php

declare(strict_types=1);

function directory_manual_entry_removed_ids(): array
{
    return [
        'instant-exchanges:altquick',
        'neverkyc-exchanges:yifi',
    ];
}

function directory_manual_entries(array $categories): array
{
    $neverKycCategorySlug = 'neverkyc-exchanges';
    $instantCategorySlug = 'instant-exchanges';
    $altquickSlug = 'altquick';
    $elCapoSlug = 'el-capo';
    $yifiSlug = 'yifi';
    $neverKycType = $categories[$neverKycCategorySlug]['type'] ?? 'service';
    $instantType = $categories[$instantCategorySlug]['type'] ?? 'service';

    return [
        [
            'id' => $neverKycCategorySlug . ':' . $altquickSlug,
            'slug' => $altquickSlug,
            'category' => $neverKycCategorySlug,
            'type' => $neverKycType,
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
                'en' => $neverKycCategorySlug . '/' . $altquickSlug . '.html',
                'ru' => $neverKycCategorySlug . '/' . $altquickSlug . '.html',
            ],
            'output_paths' => [
                'en' => $neverKycCategorySlug . '/' . $altquickSlug . '.html',
                'ru' => 'ru/' . $neverKycCategorySlug . '/' . $altquickSlug . '.html',
            ],
        ],
        [
            'id' => $neverKycCategorySlug . ':' . $elCapoSlug,
            'slug' => $elCapoSlug,
            'category' => $neverKycCategorySlug,
            'type' => $neverKycType,
            'assets' => [
                'webp' => '',
                'image' => 'wp-content/uploads/2023/12/el-capo.svg',
                'alt' => 'El Capo logo',
            ],
            'links' => [
                'clearnet' => 'https://elcapo.io/',
                'tor' => 'http://elcapo4l4xad5iipvv6ewg46x6jgb26ed43e5jbrlajxatjoxybue4id.onion',
                'mirrors' => [],
                'support' => '@elcapo_support / SimpleX / support@elcapo.io / PGP / contact form',
                'support_html' => '<a href="https://t.me/elcapo_support">@elcapo_support</a> / <a href="https://smp9.simplex.im/a#BpHSUyxfhdGsV6ia-bG-G6UlvEZuCEbO8Q05cie5oBQ">SimpleX</a> / <a href="mailto:support@elcapo.io">support@elcapo.io</a> / <a href="https://elcapo.io/pgp.txt">PGP</a> / <a href="https://elcapo.io/pages/contact.html">contact form</a>',
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
                        'en' => 'elcapo.io uses JavaScript',
                        'ru' => 'elcapo.io использует JavaScript',
                    ],
                    'lead' => [
                        'en' => 'A clearnet check on July 3, 2026 found executable JavaScript on the public page for elcapo.io.',
                        'ru' => 'Проверка clearnet-страницы 3 июля 2026 года обнаружила исполняемый JavaScript на публичной странице elcapo.io.',
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
                            'en' => 'elcapo.io clearnet response',
                            'ru' => 'clearnet-ответ elcapo.io',
                        ],
                        'url' => 'https://elcapo.io/',
                    ],
                ],
            ],
            'content' => [
                'en' => [
                    'name' => 'El Capo',
                    'summary' => 'Fee: 0.75% margin in rate',
                    'description' => 'El Capo is a no-KYC, no-logs instant crypto exchange with clearnet and onion access.',
                ],
                'ru' => [
                    'name' => 'El Capo',
                    'summary' => 'Комиссия: маржа 0,75% в курсе',
                    'description' => 'El Capo — no-KYC/no-logs мгновенный криптообменник с clearnet- и onion-доступом.',
                ],
            ],
            'facts' => [
                'en' => [
                    directory_manual_fact('Founded', '2026'),
                    directory_manual_fact('Fee', 'No separate fee; 0.75% margin is built into the displayed rate'),
                ],
                'ru' => [
                    directory_manual_fact('Основан', '2026'),
                    directory_manual_fact('Комиссия', 'Отдельной комиссии нет; маржа 0,75% включена в отображаемый курс'),
                ],
            ],
            'table_display' => [
                'en' => 'El Capo',
                'ru' => 'El Capo',
            ],
            'notes' => [
                'en' => '<p>The <a href="https://elcapo.io/pages/faq.html">El Capo FAQ</a> describes the service as a no-registration, no-KYC exchange with Tor support and says XMR is supported as both a source and destination currency. The FAQ lists BTC, ETH, XMR, LTC including MWEB, XRP, SOL, and USDT on ERC-20, TRC-20, and Solana.</p><p>The <a href="https://elcapo.io/pages/terms.html">terms page</a> says order details are automatically deleted 30 days after completion, or immediately upon request through support. El Capo offers fixed and floating rates; the submitted listing details state that there is no separate service fee and that a 0.75% margin is built into the displayed rate.</p>',
                'ru' => '<p><a href="https://elcapo.io/pages/faq.html">FAQ El Capo</a> описывает сервис как обменник без регистрации и KYC с поддержкой Tor и указывает, что XMR поддерживается как для отправки, так и для получения. В FAQ перечислены BTC, ETH, XMR, LTC включая MWEB, XRP, SOL и USDT в сетях ERC-20, TRC-20 и Solana.</p><p><a href="https://elcapo.io/pages/terms.html">Страница условий</a> сообщает, что данные ордера автоматически удаляются через 30 дней после завершения или немедленно по запросу через поддержку. El Capo предлагает фиксированный и плавающий курс; в предоставленных данных для листинга указано, что отдельной сервисной комиссии нет, а маржа 0,75% включена в отображаемый курс.</p>',
            ],
            'config' => [
                'en' => '',
                'ru' => '',
            ],
            'volume_history' => [],
            'index_paths' => [
                'en' => $neverKycCategorySlug . '/' . $elCapoSlug . '.html',
                'ru' => $neverKycCategorySlug . '/' . $elCapoSlug . '.html',
            ],
            'output_paths' => [
                'en' => $neverKycCategorySlug . '/' . $elCapoSlug . '.html',
                'ru' => 'ru/' . $neverKycCategorySlug . '/' . $elCapoSlug . '.html',
            ],
        ],
        [
            'id' => $instantCategorySlug . ':' . $yifiSlug,
            'slug' => $yifiSlug,
            'category' => $instantCategorySlug,
            'type' => $instantType,
            'assets' => [
                'webp' => '',
                'image' => 'wp-content/uploads/2023/12/yifi.jpg',
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
                    'summary' => 'Fee: None',
                    'description' => 'YiFi is a swap and yield aggregator that routes across DEX, CEX, and private liquidity paths.',
                ],
                'ru' => [
                    'name' => 'YiFi',
                    'summary' => 'Комиссия: Нет',
                    'description' => 'YiFi — агрегатор обменов и доходности, который маршрутизирует сделки через DEX, CEX и приватные источники ликвидности.',
                ],
            ],
            'facts' => [
                'en' => [
                    directory_manual_fact('Founded', '2026'),
                    directory_manual_fact('Fee', 'None'),
                ],
                'ru' => [
                    directory_manual_fact('Основан', '2026'),
                    directory_manual_fact('Комиссия', 'Нет'),
                ],
            ],
            'table_display' => [
                'en' => 'YiFi',
                'ru' => 'YiFi',
            ],
            'notes' => [
                'en' => '<p>The <a href="https://bitcointalk.org/index.php?topic=5579150.msg66573538#msg66573538">Bitcointalk announcement</a> describes YiFi as a swap aggregator for DEX, CEX, and private routes. It says private swaps use two independent providers and an XMR intermediate transfer to break the on-chain link between sender and receiver.</p><p>YiFi states that KYC requirements, if any, are imposed by integrated providers rather than YiFi. In the same thread, the operator said YiFi is non-custodial and that users may still need to follow a provider-specific process if a provider-side flag or freeze occurs.</p><p>Network and exchange/liquidity-provider fees still apply.</p>',
                'ru' => '<p><a href="https://bitcointalk.org/index.php?topic=5579150.msg66573538#msg66573538">Анонс на Bitcointalk</a> описывает YiFi как агрегатор обменов для DEX, CEX и приватных маршрутов. В нем сказано, что приватные обмены используют двух независимых провайдеров и промежуточный перевод через XMR, чтобы разорвать on-chain связь между отправителем и получателем.</p><p>YiFi указывает, что требования KYC, если они возникают, задаются интегрированными провайдерами, а не самим YiFi. В той же теме оператор написал, что YiFi не хранит средства, а при флаге или заморозке на стороне провайдера пользователю все равно может потребоваться пройти процедуру конкретного провайдера.</p><p>Сетевые комиссии и комиссии бирж/провайдеров ликвидности все равно применяются.</p>',
            ],
            'config' => [
                'en' => '',
                'ru' => '',
            ],
            'volume_history' => [],
            'index_paths' => [
                'en' => $instantCategorySlug . '/' . $yifiSlug . '.html',
                'ru' => $instantCategorySlug . '/' . $yifiSlug . '.html',
            ],
            'output_paths' => [
                'en' => $instantCategorySlug . '/' . $yifiSlug . '.html',
                'ru' => 'ru/' . $instantCategorySlug . '/' . $yifiSlug . '.html',
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
