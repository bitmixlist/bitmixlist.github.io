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
    $mixersCategorySlug = 'mixers';
    $neverKycCategorySlug = 'neverkyc-exchanges';
    $instantCategorySlug = 'instant-exchanges';
    $wmixSlug = 'wmix';
    $altquickSlug = 'altquick';
    $betKarmaSlug = 'betkarma';
    $elCapoSlug = 'el-capo';
    $zeroTraceSlug = '0trace';
    $yifiSlug = 'yifi';
    $mixersType = $categories[$mixersCategorySlug]['type'] ?? 'service';
    $neverKycType = $categories[$neverKycCategorySlug]['type'] ?? 'service';
    $instantType = $categories[$instantCategorySlug]['type'] ?? 'service';

    return [
        [
            'id' => $mixersCategorySlug . ':' . $wmixSlug,
            'slug' => $wmixSlug,
            'category' => $mixersCategorySlug,
            'type' => $mixersType,
            'assets' => [
                'webp' => '',
                'image' => 'wp-content/uploads/2023/12/wmix.png',
                'alt' => 'wMix logo',
            ],
            'links' => [
                'clearnet' => 'https://wmix.to/',
                'tor' => 'http://wmixerdmg2ugsrnae5roeedq2qxtly5cbcmr57iqbpcetyfb4fs2txad.onion/',
                'mirrors' => [],
                'support' => 'Telegram bot / support@wmix.to / PGP',
                'support_html' => '<a href="https://t.me/wmixer_bot" target="_blank" rel="noopener noreferrer">Telegram bot</a> / <a href="mailto:support@wmix.to">support@wmix.to</a> / <a href="https://wmix.to/pgp-key.txt" target="_blank" rel="noopener noreferrer">PGP</a>',
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
                        'en' => 'wmix.to uses JavaScript',
                        'ru' => 'wmix.to использует JavaScript',
                    ],
                    'lead' => [
                        'en' => 'A clearnet check on July 20, 2026 found executable JavaScript on the public page for wmix.to.',
                        'ru' => 'Проверка clearnet-страницы 20 июля 2026 года обнаружила исполняемый JavaScript на публичной странице wmix.to.',
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
                            'en' => 'wmix.to clearnet response',
                            'ru' => 'clearnet-ответ wmix.to',
                        ],
                        'url' => 'https://wmix.to/',
                    ],
                ],
            ],
            'content' => [
                'en' => [
                    'name' => 'Wmix',
                    'summary' => 'Mixing fee: 5% + 0.0007 BTC',
                    'description' => 'Wmix is a Bitcoin mixer with clearnet and onion access, Telegram bot support, PGP-signed guarantee letters, and a no-logs claim.',
                ],
                'ru' => [
                    'name' => 'Wmix',
                    'summary' => 'Комиссия за смешивание: 5% + 0.0007 BTC',
                    'description' => 'Wmix — биткоин-миксер с clearnet- и onion-доступом, Telegram-ботом, PGP-подписанными письмами гарантии и заявленной политикой без логов.',
                ],
            ],
            'facts' => [
                'en' => [
                    directory_manual_fact('Founded', '2026'),
                    directory_manual_fact('Coins', 'BTC'),
                    directory_manual_fact('Resells', 'Jambler'),
                    directory_manual_fact('Mixing Fee', '5% + 0.0007 BTC'),
                    directory_manual_fact('Minimum', '0.001 BTC'),
                    directory_manual_fact('Maximum', '50 BTC'),
                    [
                        'label' => 'Telegram Bot',
                        'value' => 'Yes',
                        'html' => '<a href="https://t.me/wmixer_bot" rel="noopener noreferrer" title="Yes" target="_blank">Yes</a>',
                    ],
                ],
                'ru' => [
                    directory_manual_fact('Основан', '2026'),
                    directory_manual_fact('Монеты', 'BTC'),
                    directory_manual_fact('Реселл', 'Jambler'),
                    directory_manual_fact('Плата за миксинг', '5% + 0.0007 BTC'),
                    directory_manual_fact('Минимум', '0.001 BTC'),
                    directory_manual_fact('Максимум', '50 BTC'),
                    [
                        'label' => 'Телеграм-бот',
                        'value' => 'Да',
                        'html' => '<a href="https://t.me/wmixer_bot" rel="noopener noreferrer" title="Да" target="_blank">Да</a>',
                    ],
                ],
            ],
            'table_display' => [
                'en' => 'Wmix',
                'ru' => 'Wmix',
            ],
            'notes' => [
                'en' => '<p>Wmix is a Bitcoin mixer with clearnet and onion access. The official page describes a workflow that replaces deposits with verified exchange funds, splits payouts into multiple parts, and sends them after random delays of 1 to 8 hours.</p><p>The service states that every order receives a PGP-signed letter of guarantee, uses a strict no-logs policy, and deletes order processing data after completion or address expiration. Its published parameters list BTC only, a 0.001 BTC minimum, 50 BTC maximum per request, one confirmation required, addresses valid for 7 days, and a 5% + 0.0007 BTC commission. Wmix also advertises a free 0.001 BTC trial mix.</p><p>Support is available through the Telegram bot and support@wmix.to. PGP fingerprint: B8A5 CFCA F63F F2D8 384A 6B12 D3B2 8095 6F0E 7CAF.</p>',
                'ru' => '<p>Wmix — биткоин-миксер с clearnet- и onion-доступом. Официальная страница описывает схему, где депозит заменяется проверенными монетами с бирж, выплаты дробятся на части и отправляются со случайными задержками от 1 до 8 часов.</p><p>Сервис заявляет, что каждый ордер получает PGP-подписанное письмо гарантии, использует политику без логов и удаляет данные обработки после завершения или истечения срока адреса. Опубликованные параметры: только BTC, минимум 0.001 BTC, максимум 50 BTC на заявку, 1 подтверждение, адреса действительны 7 дней, комиссия 5% + 0.0007 BTC. Wmix также рекламирует бесплатный тестовый микс на 0.001 BTC.</p><p>Поддержка доступна через Telegram-бота и support@wmix.to. PGP fingerprint: B8A5 CFCA F63F F2D8 384A 6B12 D3B2 8095 6F0E 7CAF.</p>',
            ],
            'config' => [
                'en' => '',
                'ru' => '',
            ],
            'volume_history' => [],
            'index_paths' => [
                'en' => $mixersCategorySlug . '/' . $wmixSlug . '.html',
                'ru' => $mixersCategorySlug . '/' . $wmixSlug . '.html',
            ],
            'output_paths' => [
                'en' => $mixersCategorySlug . '/' . $wmixSlug . '.html',
                'ru' => 'ru/' . $mixersCategorySlug . '/' . $wmixSlug . '.html',
            ],
        ],
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
            'id' => $neverKycCategorySlug . ':' . $betKarmaSlug,
            'slug' => $betKarmaSlug,
            'category' => $neverKycCategorySlug,
            'type' => $neverKycType,
            'assets' => [
                'webp' => 'wp-content/uploads/2023/12/betkarma.webp',
                'image' => 'wp-content/uploads/2023/12/betkarma.png',
                'alt' => 'BetKarma logo',
                // Sampled from 256x256_logo_betkarma.png dominant opaque fill (#1f1b20)
                'background_color' => '#1f1b20',
            ],
            'links' => [
                // Listing ref link provided by operator; actual exchange app is separate.
                'clearnet' => 'https://betkarma.art/r/A2KQKBGJ',
                'actual_clearnet' => 'https://betkarma.art/en/exchange',
                'tor' => 'http://betkarmaf7h5ubytt73ealijl7fiquooiysklmcbuk66zdux6x6musid.onion/',
                'mirrors' => [
                    [
                        'url' => 'https://betkarma.exchange/',
                        'label' => 'betkarma.exchange',
                    ],
                ],
                'support' => 'Live chat / SimpleX (private link on site) / Bitcoin signing key',
                'support_html' => 'Live chat or <a href="https://betkarma.art/en/exchange/simplex" target="_blank" rel="noopener noreferrer">SimpleX</a> (private connection link on the website) / <a href="https://betkarma.art/en/letter-of-guarantee" target="_blank" rel="noopener noreferrer">Bitcoin signing key</a>',
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
                        'en' => 'betkarma.art uses JavaScript',
                        'ru' => 'betkarma.art использует JavaScript',
                    ],
                    'lead' => [
                        'en' => 'A clearnet check on July 30, 2026 found executable JavaScript on the public exchange page for betkarma.art.',
                        'ru' => 'Проверка clearnet-страницы 30 июля 2026 года обнаружила исполняемый JavaScript на публичной странице обменника betkarma.art.',
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
                            'en' => 'BetKarma exchange page',
                            'ru' => 'Страница обменника BetKarma',
                        ],
                        'url' => 'https://betkarma.art/en/exchange',
                    ],
                ],
            ],
            'content' => [
                'en' => [
                    'name' => 'BetKarma',
                    'summary' => 'Fee: 0.3%–1.8% (route-dependent)',
                    'description' => 'BetKarma is a custodial Never-KYC exchange (operating since March 2026) focused on privacy-oriented fixed routes, Monero exits, shielded Zcash, and same-asset reserve swaps, with clearnet, Tor, and Letters of Guarantee signed by a dedicated Bitcoin address.',
                ],
                'ru' => [
                    'name' => 'BetKarma',
                    'summary' => 'Комиссия: 0,3%–1,8% (зависит от маршрута)',
                    'description' => 'BetKarma — кастодиальный Never-KYC обменник (с марта 2026) с фиксированными privacy-маршрутами, выходами в Monero, shielded Zcash и same-asset свопами из резерва; clearnet, Tor и гарантийные письма с подписью выделенного Bitcoin-адреса.',
                ],
            ],
            'facts' => [
                'en' => [
                    directory_manual_fact('Founded', 'March 2026'),
                    directory_manual_fact('Coins', 'BTC, XMR, shielded ZEC, ETH, BNB, TRX, LTC, SOL, USDT (TRON/ETH/BSC/SOL), USDC (ETH/BSC)'),
                    directory_manual_fact('Routes', '38 fixed routes (not an all-to-all matrix)'),
                    directory_manual_fact('KYC', 'Never (operator policy; no registration or email)'),
                    directory_manual_fact('Fee', '0.3%–1.8% by route; fixed in quote; no separate payout fee'),
                    directory_manual_fact('Guarantee', 'Bitcoin P2WPKH message signature (no OpenPGP)'),
                ],
                'ru' => [
                    directory_manual_fact('Основан', 'Март 2026'),
                    directory_manual_fact('Монеты', 'BTC, XMR, shielded ZEC, ETH, BNB, TRX, LTC, SOL, USDT (TRON/ETH/BSC/SOL), USDC (ETH/BSC)'),
                    directory_manual_fact('Маршруты', '38 фиксированных маршрутов (не полная матрица пар)'),
                    directory_manual_fact('KYC', 'Никогда (политика оператора; без регистрации и email)'),
                    directory_manual_fact('Комиссия', '0,3%–1,8% по маршруту; фиксируется в котировке; без отдельной платы за вывод'),
                    directory_manual_fact('Гарантия', 'Подпись сообщения Bitcoin P2WPKH (без OpenPGP)'),
                ],
            ],
            'table_display' => [
                'en' => 'BetKarma',
                'ru' => 'BetKarma',
            ],
            'notes' => [
                'en' => '<p>Custodial Never-KYC exchange operating since March 2026. No registration or email. Operator policy: never KYC, no AML-score surcharge, and no funds held pending identity or source-of-funds documents (policy claims only — not an audit).</p><p>Assets and networks: Bitcoin; Monero; shielded Zcash; Ethereum; BNB; TRX; Litecoin; Solana; USDT on TRON, Ethereum, BSC, and Solana; USDC on Ethereum and BSC. Support is via <strong>fixed routes</strong> (38 routes in five groups), not a universal all-to-all pair matrix: BTC↔USDT TRON private reserve swaps; 12 routes for buying Monero privately; 10 routes for spending Monero privately; routes into BTC and ETH; routes into USDT and USDC stablecoins. Includes privacy-oriented paths, Monero exits, shielded Zcash, and same-asset reserve swaps.</p><p>Fee range <strong>0.3%–1.8%</strong> depending on route. Exact fee, rate, minimum, maximum, required confirmations, and final receive amount are displayed and fixed before the customer sends funds. No additional payout fee. Payouts are made from separate reserve liquidity.</p><p>Website: <a href="https://betkarma.exchange/" target="_blank" rel="noopener noreferrer">betkarma.exchange</a>. Exchange app: <a href="https://betkarma.art/en/exchange" target="_blank" rel="noopener noreferrer">betkarma.art/en/exchange</a>. Tor: <code>betkarmaf7h5ubytt73ealijl7fiquooiysklmcbuk66zdux6x6musid.onion</code>.</p><p>Support: live chat or SimpleX via the private connection link on the website.</p><p>Guarantee: BetKarma does not use OpenPGP for order guarantees. Each Letter of Guarantee is signed with dedicated Bitcoin P2WPKH message-signing key <code>bc1q6etkarmarvej0874ghw396eacejlsy5ygew5yk</code> before payment. Verify on the <a href="https://betkarma.art/en/letter-of-guarantee" target="_blank" rel="noopener noreferrer">official key page</a>, with the tool below, or the <a href="https://grggoo.github.io/betkarma-verify/letter.html?lang=en" target="_blank" rel="noopener noreferrer">open-source verifier</a> before sending funds.</p>',
                'ru' => '<p>Кастодиальный Never-KYC обменник с марта 2026. Без регистрации и email. Политика оператора: без KYC, без надбавки по AML-score и без удержания средств до документов о личности/источнике средств (только заявления политики — не аудит).</p><p>Активы и сети: Bitcoin; Monero; shielded Zcash; Ethereum; BNB; TRX; Litecoin; Solana; USDT в сетях TRON, Ethereum, BSC и Solana; USDC в Ethereum и BSC. Поддержка через <strong>фиксированные маршруты</strong> (38 маршрутов в пяти группах), а не полная матрица пар: приватные резервные свопы BTC↔USDT TRON; 12 маршрутов для покупки Monero; 10 маршрутов для траты Monero; маршруты в BTC и ETH; маршруты в USDT и USDC. Включает privacy-направления, выходы в Monero, shielded Zcash и same-asset свопы из резерва.</p><p>Комиссия <strong>0,3%–1,8%</strong> в зависимости от маршрута. Точная комиссия, курс, минимум, максимум, число подтверждений и сумма к получению показываются и фиксируются до отправки средств. Отдельной платы за вывод нет. Выплаты из отдельной резервной ликвидности.</p><p>Сайт: <a href="https://betkarma.exchange/" target="_blank" rel="noopener noreferrer">betkarma.exchange</a>. Приложение обмена: <a href="https://betkarma.art/en/exchange" target="_blank" rel="noopener noreferrer">betkarma.art/en/exchange</a>. Tor: <code>betkarmaf7h5ubytt73ealijl7fiquooiysklmcbuk66zdux6x6musid.onion</code>.</p><p>Поддержка: live chat или SimpleX по приватной ссылке на сайте.</p><p>Гарантия: OpenPGP для гарантийных писем не используется. Каждое Letter of Guarantee подписывается выделенным Bitcoin P2WPKH-ключом <code>bc1q6etkarmarvej0874ghw396eacejlsy5ygew5yk</code> до оплаты. Проверяйте на <a href="https://betkarma.art/ru/letter-of-guarantee" target="_blank" rel="noopener noreferrer">официальной странице ключа</a>, инструментом ниже или в <a href="https://grggoo.github.io/betkarma-verify/letter.html?lang=ru" target="_blank" rel="noopener noreferrer">открытом верификаторе</a> до отправки средств.</p>',
            ],
            'config' => [
                'en' => '',
                'ru' => '',
            ],
            'volume_history' => [],
            'index_paths' => [
                'en' => $neverKycCategorySlug . '/' . $betKarmaSlug . '.html',
                'ru' => $neverKycCategorySlug . '/' . $betKarmaSlug . '.html',
            ],
            'output_paths' => [
                'en' => $neverKycCategorySlug . '/' . $betKarmaSlug . '.html',
                'ru' => 'ru/' . $neverKycCategorySlug . '/' . $betKarmaSlug . '.html',
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
            'id' => $neverKycCategorySlug . ':' . $zeroTraceSlug,
            'slug' => $zeroTraceSlug,
            'category' => $neverKycCategorySlug,
            'type' => $neverKycType,
            'assets' => [
                'webp' => '',
                'image' => 'wp-content/uploads/2023/12/0trace.png',
                'alt' => '0trace logo',
            ],
            'links' => [
                'clearnet' => 'https://orangefren.com/goto/zerotrace',
                'actual_clearnet' => 'https://0trace.io/',
                'tor' => 'http://rnwis2whetqcj4oknksnc5l24jbh33nflunifff3xtjjonnoxu3ld6id.onion/goto/zerotrace',
                'actual_tor' => 'http://n55kxqrra37apxjlqirvgxcodeq7q5vp7kez6d2cngeo2ksfo6yzn5qd.onion/',
                'mirrors' => [],
                'support' => 'Encrypted chat / Jabber / SimpleX / X / Bitcointalk',
                'support_html' => 'Encrypted chat on site / <a href="xmpp:0trace@exploit.im">Jabber</a> / <a href="https://smp11.simplex.im/a#OZNLsxcV3alnyXcWUnd6m-BBPGbyKWys6ajTbxwziyg" target="_blank" rel="noopener noreferrer">SimpleX</a> / <a href="https://x.com/0trace_io" target="_blank" rel="noopener noreferrer">@0trace_io</a> / <a href="https://bitcointalk.org/index.php?topic=5577836.0" target="_blank" rel="noopener noreferrer">Bitcointalk</a>',
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
                        'en' => '0trace.io uses JavaScript',
                        'ru' => '0trace.io использует JavaScript',
                    ],
                    'lead' => [
                        'en' => 'A clearnet check on July 10, 2026 found executable JavaScript on the public page for 0trace.io.',
                        'ru' => 'Проверка clearnet-страницы 10 июля 2026 года обнаружила исполняемый JavaScript на публичной странице 0trace.io.',
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
                            'en' => '0trace.io clearnet response',
                            'ru' => 'clearnet-ответ 0trace.io',
                        ],
                        'url' => 'https://0trace.io/',
                    ],
                ],
            ],
            'content' => [
                'en' => [
                    'name' => '0trace',
                    'summary' => 'Fee: 0.5%',
                    'description' => '0trace is a no-KYC instant private cryptocurrency exchange with Tor support and signed order proofs.',
                ],
                'ru' => [
                    'name' => '0trace',
                    'summary' => 'Комиссия: 0,5%',
                    'description' => '0trace — мгновенный приватный обменник без KYC с поддержкой Tor и подписанными доказательствами ордеров.',
                ],
            ],
            'facts' => [
                'en' => [
                    directory_manual_fact('Founded', '2026'),
                    directory_manual_fact('Fee', '0.5% service fee (quote includes fees)'),
                ],
                'ru' => [
                    directory_manual_fact('Основан', '2026'),
                    directory_manual_fact('Комиссия', '0,5% сервисный сбор (в котировке)'),
                ],
            ],
            'table_display' => [
                'en' => '0trace',
                'ru' => '0trace',
            ],
            'notes' => [
                'en' => '<p>0trace is an instant private no-KYC/AML cryptocurrency exchange. No account or registration required. Uses own liquidity with isolated wallets and one-time addresses (OTA) per order. No external chain analysis, AML scoring or third-party involvement.</p><p>Features: Tor mirror, API, signed Guarantees (pre-send) and Receipts (post-swap) verifiable locally at their /verify page (no upload), ephemeral data (orders deleted after 72h or on demand; chat after 24h inactivity), encrypted in-site support chat. Early access limits apply.</p><p>Prominently supports Monero (native daemon), BTC, USDT (multiple chains), ETH, SOL, LTC, DASH and more. The 0.5% service fee is shown transparently. Announced March 2026 on Bitcointalk. PGP available.</p><p>Links via Orangefren are used for the official site (for tracking/guarantee purposes if applicable); the actual service URL is listed in the Links table above.</p>',
                'ru' => '<p>0trace — мгновенный приватный обменник без KYC/AML. Без аккаунта и регистрации. Собственная ликвидность, изолированные кошельки, одноразовые адреса (OTA). Без внешнего анализа цепочек, скоринга AML или третьих сторон.</p><p>Возможности: Tor-зеркало, API, подписанные Гарантии (до отправки) и Квитанции (после), проверяемые локально на /verify, эфемерные данные (ордера удаляются через 72 ч или по запросу). Лимиты на раннем этапе.</p><p>Поддержка Monero (нативно), BTC, USDT (разные сети), ETH, SOL, LTC, DASH и др. Комиссия 0,5% прозрачно указана в котировке. Анонс в марте 2026 на Bitcointalk. Есть PGP.</p><p>Ссылки через Orangefren используются для официального сайта; фактический URL сервиса указан в таблице Ссылок выше.</p>',
            ],
            'config' => [
                'en' => '',
                'ru' => '',
            ],
            'volume_history' => [],
            'index_paths' => [
                'en' => $neverKycCategorySlug . '/' . $zeroTraceSlug . '.html',
                'ru' => $neverKycCategorySlug . '/' . $zeroTraceSlug . '.html',
            ],
            'output_paths' => [
                'en' => $neverKycCategorySlug . '/' . $zeroTraceSlug . '.html',
                'ru' => 'ru/' . $neverKycCategorySlug . '/' . $zeroTraceSlug . '.html',
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
