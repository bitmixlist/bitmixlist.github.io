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
                'webp' => '',
                'image' => 'wp-content/uploads/2023/12/betkarma.svg',
                'alt' => 'betKarma logo',
                'background_color' => '#111010',
            ],
            'links' => [
                'clearnet' => 'https://betkarma.art/en/exchange',
                'tor' => 'http://betkarmaf7h5ubytt73ealijl7fiquooiysklmcbuk66zdux6x6musid.onion/',
                'mirrors' => [
                    [
                        'url' => 'https://betkarma.exchange/',
                        'label' => 'betkarma.exchange',
                    ],
                ],
                'support' => 'On-site chat / @bet_karma / SimpleX / betkarma@pm.me / Bitcoin signing key',
                'support_html' => 'On-site chat / <a href="https://t.me/bet_karma" target="_blank" rel="noopener noreferrer">@bet_karma</a> / <a href="https://betkarma.art/en/exchange/simplex" target="_blank" rel="noopener noreferrer">SimpleX</a> / <a href="mailto:betkarma@pm.me">betkarma@pm.me</a> / <a href="https://betkarma.art/en/letter-of-guarantee" target="_blank" rel="noopener noreferrer">Bitcoin signing key</a>',
            ],
            'status' => [],
            'notices' => [
                [
                    'type' => 'notice',
                    'label' => [
                        'en' => 'Unverified allegation',
                        'ru' => 'Неподтвержденное утверждение',
                    ],
                    'title' => [
                        'en' => 'Bitcointalk user alleged a possible Tomboi connection',
                        'ru' => 'Пользователь Bitcointalk предположил возможную связь с Tomboi',
                    ],
                    'lead' => [
                        'en' => 'A June 2, 2026 Bitcointalk post alleged a possible operational link between betKarma and Tomboi, the subject of the scam-accusation thread. The post is an allegation, not proof of common ownership.',
                        'ru' => 'В сообщении на Bitcointalk от 2 июня 2026 года была предположена возможная операционная связь между betKarma и Tomboi, которому посвящена тема с обвинением в мошенничестве. Это утверждение, а не доказательство общего владельца.',
                    ],
                    'items' => [
                        'en' => [
                            'The author cited a betkarma referral identifier in a NEAR Intents transaction, use of the same domain reseller, and forum-account timing.',
                            'No independent confirmation of common ownership was found during the July 30, 2026 review.',
                            'Read the thread, preserve the signed guarantee before payment, and transact cautiously.',
                        ],
                        'ru' => [
                            'Автор сослался на реферальный идентификатор betkarma в транзакции NEAR Intents, одного и того же реселлера доменов и время активности форумных аккаунтов.',
                            'Во время проверки 30 июля 2026 года независимого подтверждения общего владельца найдено не было.',
                            'Изучите тему, сохраните подписанное гарантийное письмо до оплаты и соблюдайте осторожность.',
                        ],
                    ],
                    'source' => [
                        'label' => [
                            'en' => 'Bitcointalk allegation thread',
                            'ru' => 'Тема с утверждением на Bitcointalk',
                        ],
                        'url' => 'https://bitcointalk.org/index.php?topic=5584545.msg66786615#msg66786615',
                    ],
                ],
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
                            'en' => 'betKarma exchange page',
                            'ru' => 'Страница обменника betKarma',
                        ],
                        'url' => 'https://betkarma.art/en/exchange',
                    ],
                ],
            ],
            'content' => [
                'en' => [
                    'name' => 'betKarma',
                    'summary' => 'Fee: Included in live quote',
                    'description' => 'betKarma is a no-account cryptocurrency exchange and same-asset mixer with a published KYC-never policy, Tor access, and BIP-137-signed guarantee letters.',
                ],
                'ru' => [
                    'name' => 'betKarma',
                    'summary' => 'Комиссия: включена в котировку',
                    'description' => 'betKarma — криптообменник и сервис смешивания одинаковых активов без аккаунта, с заявленной политикой KYC-never, доступом через Tor и гарантийными письмами с подписью BIP-137.',
                ],
            ],
            'facts' => [
                'en' => [
                    directory_manual_fact('Founded', '2026'),
                    directory_manual_fact('Coins', 'BTC, XMR, ETH, LTC, SOL, ZEC, BNB, TRX, USDT, USDC'),
                    directory_manual_fact('KYC', 'Never (operator policy)'),
                    directory_manual_fact('Fee', 'Included in live quote; varies by direction'),
                    directory_manual_fact('Guarantee', 'Bitcoin BIP-137 signed message'),
                ],
                'ru' => [
                    directory_manual_fact('Основан', '2026'),
                    directory_manual_fact('Монеты', 'BTC, XMR, ETH, LTC, SOL, ZEC, BNB, TRX, USDT, USDC'),
                    directory_manual_fact('KYC', 'Никогда (политика оператора)'),
                    directory_manual_fact('Комиссия', 'Включена в котировку; зависит от направления'),
                    directory_manual_fact('Гарантия', 'Сообщение с Bitcoin-подписью BIP-137'),
                ],
            ],
            'table_display' => [
                'en' => 'betKarma',
                'ru' => 'betKarma',
            ],
            'notes' => [
                'en' => '<p>The <a href="https://betkarma.art/en/exchange">current official exchange page</a> and <a href="https://betkarma.art/en/info">detailed service information</a> present betKarma as a no-account exchange and same-asset mixer with no KYC, no AML screening, and no logs. These are operator policy statements, not independent audit findings. A public exchange order does not require an account or email, and the service publishes clearnet, redirecting mirror, and onion access.</p><p>Published routes cover BTC, XMR, ETH, LTC, SOL, shielded ZEC, BNB, TRX, and USDT/USDC on supported networks. The operator groups them as same-asset BTC and USDT mixing; buying XMR; spending XMR; swapping stablecoins or shielded ZEC into BTC/ETH; and cashing BTC, ETH, or shielded ZEC out to supported stablecoins.</p><p>The fee, confirmation depth, minimum, and maximum are shown for the selected route before confirmation; the fee is included in the live quote. Payouts are released manually by the operator. Cross-asset deposit quotes normally have a 15-minute window, same-asset mixer orders have a one-hour window, and the exact deadline appears on the order. XMR-funded routes state 10 confirmations. The operator says a market move above 1% before the deposit appears can lead to a refund or a new current-rate offer.</p><p>The operator says payouts come from internal reserves rather than forwarding a client deposit directly to the recipient. Order pages are private; reopening one outside the original browser requires the exact recipient address, and order data can be deleted after completion or expiry. Every exchange order receives a human-readable Letter of Guarantee signed as a Bitcoin BIP-137 message, with the exact-text SHA-256 shown separately.</p><p><strong>Published guarantee trust anchor:</strong> <code>bc1q6etkarmarvej0874ghw396eacejlsy5ygew5yk</code>. This is a Bitcoin signing address, <strong>not an OpenPGP/PGP public key</strong>. Compare it with the <a href="https://betkarma.art/en/letter-of-guarantee">official key page</a> and verify the exact letter locally below or with the <a href="https://grggoo.github.io/betkarma-verify/letter.html?lang=en">open-source verifier</a> before paying.</p><p>The <a href="https://www.altcoinstalks.com/index.php?topic=344275.0">official AltcoinsTalks announcement</a> was posted on June 13, 2026 and states that 0.32 BTC was placed in forum escrow; the escrow post says a refund claim must include a guarantee signed by the published address. This listing has not independently confirmed that the escrow remains funded. Separately, a June 2 <a href="https://bitcointalk.org/index.php?topic=5584545.msg66786615#msg66786615">Bitcointalk post</a> alleged a possible link to Tomboi based on a NEAR Intents referral identifier and other circumstantial observations. No common ownership has been independently established.</p><p>betKarma also operates a crypto casino, which its <a href="https://betkarma.art/en/info">own FAQ</a> describes as unlicensed. Advertised 100% exchange-fee cashback is credited as a casino bonus with a 50× wagering requirement, not as a cash rebate.</p>',
                'ru' => '<p><a href="https://betkarma.art/ru/exchange">Текущая официальная страница обменника</a> и <a href="https://betkarma.art/ru/info">подробная информация о сервисе</a> представляют betKarma как обменник и сервис смешивания одинаковых активов без аккаунта, KYC, AML-проверки и логов. Это заявления о политике оператора, а не результаты независимого аудита. Для публичной заявки на обмен не нужны аккаунт или email; сервис публикует clearnet-адрес, перенаправляющее зеркало и onion-адрес.</p><p>Опубликованные направления охватывают BTC, XMR, ETH, LTC, SOL, shielded ZEC, BNB, TRX, а также USDT/USDC в поддерживаемых сетях. Оператор группирует их как смешивание BTC и USDT в тот же актив; покупку XMR; расходование XMR; обмен стейблкоинов или shielded ZEC на BTC/ETH; и вывод BTC, ETH или shielded ZEC в поддерживаемые стейблкоины.</p><p>Комиссия, число подтверждений, минимум и максимум показываются для выбранного направления до подтверждения; комиссия включена в живую котировку. Выплаты оператор отправляет вручную. Для межактивных обменов окно депозита обычно составляет 15 минут, для смешивания одинакового актива — один час; точный срок указан в заявке. Для направлений с отправкой XMR указано 10 подтверждений. По заявлению оператора, движение рынка более чем на 1% до обнаружения депозита может привести к предложению возврата или нового текущего курса.</p><p>Оператор заявляет, что выплаты идут из внутренних резервов, а депозит клиента не пересылается напрямую получателю. Страницы заявок приватны: вне исходного браузера для повторного открытия нужен точный адрес получателя; данные заявки можно удалить после завершения или истечения срока. Каждая заявка получает читаемое гарантийное письмо с Bitcoin-подписью BIP-137, а SHA-256 точного текста показывается отдельно.</p><p><strong>Опубликованная точка доверия для гарантий:</strong> <code>bc1q6etkarmarvej0874ghw396eacejlsy5ygew5yk</code>. Это адрес Bitcoin для подписи, <strong>а не публичный ключ OpenPGP/PGP</strong>. Сверьте его с <a href="https://betkarma.art/ru/letter-of-guarantee">официальной страницей ключа</a> и проверьте точный текст письма локально ниже или в <a href="https://grggoo.github.io/betkarma-verify/letter.html?lang=ru">открытом верификаторе</a> до оплаты.</p><p><a href="https://www.altcoinstalks.com/index.php?topic=344275.0">Официальный анонс на AltcoinsTalks</a> опубликован 13 июня 2026 года и сообщает о помещении 0,32 BTC в форумное эскроу; в сообщении эскроу указано, что требование возврата должно включать гарантию с подписью опубликованного адреса. Этот каталог не подтверждал независимо, что эскроу продолжает быть пополненным. Отдельно в сообщении на <a href="https://bitcointalk.org/index.php?topic=5584545.msg66786615#msg66786615">Bitcointalk</a> от 2 июня была предположена возможная связь с Tomboi на основании реферального идентификатора NEAR Intents и других косвенных наблюдений. Общий владелец независимо не установлен.</p><p>betKarma также управляет криптоказино, которое <a href="https://betkarma.art/ru/info">собственный FAQ сервиса</a> называет нелицензированным. Рекламируемый 100% cashback комиссии обмена зачисляется как бонус казино с требованием отыгрыша 50×, а не как денежный возврат.</p>',
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
