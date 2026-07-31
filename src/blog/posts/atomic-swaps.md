---
# HARDWIRED: legacy root HTML is source of truth; not a blog post
slug: atomic-swaps
status: draft
published_at: 2025-02-12T00:00:00Z
updated_at: 2026-02-19T00:00:00Z
author: NotATether
canonical_path: atomic-swaps.html
body_format: html
locales:
  en:
    title: "Atomic Swaps & Privacy"
    description: "Atomic swaps for privacy: how BTC cross-chain swaps work, where they reduce exchange exposure, and the liquidity, timing, and compliance risks to plan for."
  ru:
    title: Атомарные свопы и приватность
    description: "Атомарные свопы для приватности: как работают кросс-чейновые свопы BTC, где они снижают риски обмена, а также риски ликвидности, сроков и требований соблюдения, которые следует планировать."
    body: ""
---
<p>Atomic swaps let you move value across chains directly from wallet to wallet, without handing custody to a centralized exchange in the middle. That matters for privacy because the exchange layer is where identity records, freeze controls, and travel-rule metadata usually accumulate. If your goal is to reduce that exposure, swaps create a route that does not depend on a traditional deposit-withdrawal account model.</p>
        <p>In practical workflows, swaps often act as a bridge layer: leave Bitcoin, pass through a different ecosystem, then re-enter Bitcoin with cleaner separation than a single-chain route can usually provide. That is why swaps show up in our <a href="mixer-privacy.html">Mixer Privacy guide</a> alongside CoinJoin and mixer strategy. They are not a replacement for those tools, but they are a high-value option when you need to cross chains without trusting one operator with your entire balance.</p>
        <h2 class="wp-block-heading" id="why-swaps">Why Atomic Swaps Matter for Privacy</h2>
        <p>CoinJoin and mixers mostly reorganize transaction visibility inside Bitcoin itself. Atomic swaps add a different lever: they let you step off the chain entirely, reset heuristics in a separate market, and come back later through another path. Services such as <a href="private-exchanges.html#unstoppableswap">UnstoppableSwap</a>, Bisq-style coordination, and related peer routing models are useful here because users can negotiate terms without first surrendering funds.</p>
        <ul>
          <li><strong>No shared custody:</strong> each side keeps keys until settlement conditions are met.</li>
          <li><strong>Lower identity drag:</strong> no direct exchange account means less automatic KYC data collection.</li>
          <li><strong>Route flexibility:</strong> swaps can bridge to XMR, Lightning, or other paths before re-entry.</li>
        </ul>
        <p>That said, swaps are not automatic privacy guarantees. If wallet hygiene is weak or re-entry behavior is predictable, observers can still correlate movements. You still need tight <a href="address-reuse.html">address reuse discipline</a>, output planning, and staged post-swap handling.</p>
        <h2 class="wp-block-heading" id="mechanics">How Swaps Work</h2>
        <p>The classic model uses <strong>hash time-locked contracts (HTLCs)</strong>. Both parties lock funds on their respective chains under the same secret hash and timeout conditions. One redemption reveals the secret, and that reveal lets the counterparty claim the other side. If deadlines pass, refund paths return funds to original owners. This is the same "all or nothing" logic behind early public swap demos such as Decred↔Litecoin in 2017.</p>
        <p>For BTC↔XMR and similar pairs, modern tooling often relies on <strong>adaptor signatures</strong> and scriptless constructions because Monero does not expose Bitcoin-style HTLC scripting. Taproot helps on the Bitcoin side by reducing obvious script fingerprints and improving efficiency, but implementation quality still matters more than marketing terms.</p>
        <h2 class="wp-block-heading" id="workflows">Typical 2026 Workflows</h2>
        <p>Most successful swap flows are operationally boring: isolate funds first, agree terms clearly, monitor settlement actively, and avoid rushed re-entry.</p>
        <ol>
          <li><strong>Segregate funds:</strong> label UTXOs intended for swap activity and keep them isolated from unrelated history.</li>
          <li><strong>Negotiate terms:</strong> use coordinators or relays to lock amount, timeout windows, and fee assumptions.</li>
          <li><strong>Fund contracts:</strong> publish HTLC/adaptor transactions and track both chains during settlement.</li>
          <li><strong>Claim safely:</strong> when one side redeems, finalize the opposite side promptly before timeout pressure rises.</li>
          <li><strong>Re-enter deliberately:</strong> decide whether to pass through <a href="enhanced-coinjoins.html">CoinJoin</a>, Lightning, or another bridge before touching KYC endpoints.</li>
        </ol>
        <h2 class="wp-block-heading" id="tooling">Current Tooling &amp; Liquidity Sources</h2>
        <p>The current tooling landscape ranges from strict peer swaps to pooled cross-chain liquidity networks. The label "atomic swap" gets used broadly, so always verify custody assumptions and failure behavior before committing size.</p>
        <ul>
          <li><strong><a href="private-exchanges.html#unstoppableswap">UnstoppableSwap / eigenwallet</a>:</strong> BTC↔XMR-focused automation with watchtower-style monitoring.</li>
          <li><strong>Bisq &amp; RoboSats:</strong> peer coordination venues that can support swap-style flows with collateral controls.</li>
          <li><strong>THORChain / Chainflip / Maya:</strong> deeper cross-chain pools with higher complexity and fee dynamics.</li>
          <li><strong>Lightning Loop / Boltz / submarine swaps:</strong> practical bridge between on-chain BTC and Lightning liquidity.</li>
        </ul>
        <p>If a coordinator can halt redemptions or geoblock access, treat that as real operational risk, not a footnote. Keep backup routes and avoid leaving funds in half-complete sessions.</p>
        <h2 class="wp-block-heading" id="limits">Risks &amp; Limitations</h2>
        <p>Swaps reduce some risks and introduce others. Most failures come from liquidity gaps, bad timing under congestion, or users overestimating privacy from one successful round.</p>
        <ul>
          <li><strong>Compatibility limits:</strong> both chains must support usable primitives for secure settlement.</li>
          <li><strong>Liquidity constraints:</strong> thin books can leak intent or stall larger trades.</li>
          <li><strong>Timing stress:</strong> fee spikes and slow blocks can force refund races.</li>
          <li><strong>Residual fingerprinting:</strong> some patterns remain recognizable even with better scripting.</li>
          <li><strong>Regulatory pressure points:</strong> relays and coordinators can still face takedowns or restrictions.</li>
        </ul>
        <h2 class="wp-block-heading" id="status">Adoption Snapshot – February 2026</h2>
        <p>Adoption has moved well beyond pure experimentation. Wallet ecosystems now expose more hooks for swap tooling, Tor-first relay infrastructure is more common, and large cross-chain pools report materially higher throughput than previous years. At the same time, papers such as Lu et al.'s <a href="https://doi.org/10.1109/TDSC.2020.3043366" target="_blank" rel="noopener">CoinLayering</a> remind us that multi-hop privacy architecture becomes hard to operate quickly at scale. For routine spending, mixers and CoinJoin are often simpler; swaps are strongest when the mission is controlled entry or exit between chains.</p>
        <h2 class="wp-block-heading" id="resources">Further Reading &amp; Tools</h2>
        <ul>
          <li><a href="https://blog.decred.org/2017/09/20/First-Atomic-Swap-Between-Decred-and-Litecoin/" target="_blank" rel="noopener">Decred ↔ Litecoin swap announcement (2017)</a></li>
          <li><a href="https://unstoppableswap.net/why" target="_blank" rel="noopener">UnstoppableSwap technical overview</a></li>
          <li><a href="https://thorchain.org/blog" target="_blank" rel="noopener">THORChain engineering updates</a></li>
          <li><a href="https://www.fool.com/terms/a/atomic-swap/" target="_blank" rel="noopener">Plain-language atomic swap primer</a></li>
          <li><a href="https://doi.org/10.1109/TDSC.2020.3043366" target="_blank" rel="noopener">Lu et al., CoinLayering</a></li>
        </ul>
        <p>Atomic swaps complement mixers and CoinJoin; they do not replace either. Only swap lawfully sourced funds, keep labels for resulting outputs, and use the <a href="aml-check.html">BitMixList AML Checker</a> before touching regulated venues that may request provenance.</p>

<!--blog:locale:ru-->
<p>Атомарные свопы позволяют перемещать ценность между блокчейнами напрямую — из кошелька в кошелёк, без передачи средств на хранение централизованной бирже. Это важно для приватности, потому что именно на уровне бирж обычно накапливаются записи об идентификации, механизмы заморозки средств и метаданные Travel Rule. Если ваша цель — снизить такую степень раскрытия информации, свопы создают маршрут, который не зависит от традиционной модели депозита и вывода средств через аккаунт.</p>
<p>В практических сценариях свопы часто работают как мостовой слой: выход из Bitcoin, переход через другую экосистему, а затем возвращение в Bitcoin с более чистым разделением, чем обычно возможно при использовании только одной цепочки. Поэтому свопы упоминаются в нашем <a href="mixer-privacy.html">Руководстве о приватности миксера</a> вместе с CoinJoin и стратегиями использования миксеров. Они не заменяют эти инструменты, но являются ценным вариантом, когда нужно перейти между блокчейнами, не доверяя одному оператору весь свой баланс.</p>
<h2 class="wp-block-heading">Почему атомарные свопы важны для приватности</h2>
<p>CoinJoin и миксеры в основном меняют структуру видимости транзакций внутри самого Bitcoin. Атомарные свопы добавляют другой инструмент: они позволяют полностью выйти из этой цепочки, сбросить эвристики в другой рыночной среде и затем вернуться позже по другому маршруту. Сервисы, такие как <a href="private-exchanges.html#unstoppableswap">UnstoppableSwap</a>, координация в стиле Bisq и похожие модели маршрутизации P2P, полезны в этом случае, поскольку пользователи могут согласовывать условия сделки, не передавая средства на хранение заранее.</p>
<ul>
<li><strong>Нет общей кастодии:</strong> каждая сторона сохраняет контроль над своими ключами до тех пор, пока не будут выполнены условия сделки.</li>
<li><strong>Меньше следов идентификации:</strong> отсутствие прямого аккаунта на бирже означает меньше автоматического сбора KYC-данных.</li>
<li><strong>Гибкость маршрутов:</strong> свопы могут служить мостом к XMR, Lightning или другим путям перед повторным входом.</li>
</ul>
<p>При этом свопы не дают автоматической гарантии приватности. Если гигиена кошельков слабая или поведение при повторном входе в сеть предсказуемо, наблюдатели всё равно могут сопоставить движения средств. Поэтому по-прежнему важны строгая <a href="address-reuse.html">дисциплина использования адресов</a>, планирование выходов и поэтапная обработка средств после свопа.</p>
<h2 class="wp-block-heading">Как работают свопы</h2>
<p>Классическая модель использует <strong>контракты с хешем и таймлоком (HTLC)</strong>. Обе стороны блокируют средства в своих блокчейнах с использованием одного и того же хеша секрета и условий по времени. Когда одна сторона получает средства и раскрывает секрет, это раскрытие позволяет второй стороне получить свою часть сделки. Если сроки истекают, механизмы возврата возвращают средства их первоначальным владельцам. Это та же логика «всё или ничего», которая лежала в основе ранних публичных демонстраций свопов, например Decred ↔ Litecoin в 2017 году.</p>
<p>Для пар BTC ↔ XMR и похожих комбинаций современный инструментарий часто использует <strong>адапторные подписи</strong> и так называемые бесскриптовые конструкции, поскольку Monero не поддерживает скрипты HTLC в стиле Bitcoin. Taproot помогает со стороны Bitcoin, уменьшая заметные следы скриптов и повышая эффективность, однако качество реализации по-прежнему важнее любых маркетинговых терминов.</p>
<h2 class="wp-block-heading">Типичные сценарии использования в 2026 году</h2>
<p>Большинство успешных своп-потоков с операционной точки зрения скучны: сначала изолируйте средства, чётко согласуйте условия, внимательно следите за расчётом сделки и избегайте поспешного повторного входа.</p>
<ol>
<li><strong>Изолируйте средства:</strong> помечайте UTXO, предназначенные для свопов, и держите их отдельно от другой истории транзакций.</li>
<li><strong>Согласуйте условия:</strong> используйте координаторов или ретрансляторы, чтобы зафиксировать сумму, окна тайм-аута и предполагаемые комиссии.</li>
<li><strong>Финансируйте контракты:</strong> публикуйте HTLC- и адапторные транзакции и отслеживайте обе цепочки во время завершения сделки.</li>
<li><strong>Безопасно завершайте:</strong> когда одна сторона получает средства, оперативно завершайте получение средств со второй стороны до истечения тайм-аута.</li>
<li><strong>Осознанно возвращайтесь в сеть:</strong> решите, стоит ли пройти через <a href="enhanced-coinjoins.html">CoinJoin</a>, Lightning или другой промежуточный слой перед взаимодействием с KYC-площадками.</li>
</ol>
<h2 class="wp-block-heading">Текущие инструменты и источники ликвидности</h2>
<p>Современная экосистема инструментов варьируется от строго P2P-свопов до сетей объединённой кроссчейн-ликвидности. Термин «атомарные свопы» используется довольно широко, поэтому перед проведением крупной операции всегда стоит проверить модель хранения средств и поведение системы в случае сбоя.</p>
<ul>
<li><strong><a href="private-exchanges.html#unstoppableswap">UnstoppableSwap / eigenwallet</a>:</strong> автоматизация свопов BTC ↔ XMR с мониторингом в стиле watchtower.</li>
<li><strong>Bisq и RoboSats:</strong> площадки для координации P2P-сделок, которые могут поддерживать своп-подобные схемы с залоговыми механизмами.</li>
<li><strong>THORChain / Chainflip / Maya:</strong> более глубокие пулы кроссчейн-ликвидности с большей сложностью и динамикой комиссий.</li>
<li><strong>Lightning Loop / Boltz / submarine swaps:</strong> практический мост между BTC в ончейне и ликвидностью сети Lightning.</li>
</ul>
<p>Если координатор может остановить выполнение сделки или геоблокировать доступ, рассматривайте это как реальный операционный риск, а не как второстепенную деталь. Держите запасные маршруты и избегайте оставлять средства в незавершённых сессиях.</p>
<h2 class="wp-block-heading">Риски и ограничения</h2>
<p>Свопы снижают одни риски, но создают другие. Большинство проблем возникает из-за нехватки ликвидности, неудачного тайминга во время перегрузки сети или из-за того, что пользователи переоценивают уровень приватности после одного успешного обмена.</p>
<ul>
<li><strong>Ограничения совместимости:</strong> обе сети должны поддерживать необходимые примитивы для безопасного завершения сделки.</li>
<li><strong>Ограничения ликвидности:</strong> тонкие рынки могут раскрывать намерение сделки или задерживать крупные обмены.</li>
<li><strong>Давление по времени:</strong> всплески комиссий и медленные блоки могут привести к гонке за возврат средств.</li>
<li><strong>Остаточные «отпечатки»:</strong> некоторые поведенческие паттерны остаются распознаваемыми даже при более продвинутых скриптовых механизмах.</li>
<li><strong>Регуляторные точки давления:</strong> ретрансляторы и координаторы всё ещё могут сталкиваться с блокировками или ограничениями со стороны властей.</li>
</ul>
<h2 class="wp-block-heading">Картина внедрения — февраль 2026 года</h2>
<p>Использование уже давно вышло за рамки чистых экспериментов. Экосистемы кошельков теперь предоставляют больше возможностей для интеграции инструментов свопов, инфраструктура ретрансляторов с приоритетом Tor стала более распространённой, а крупные кроссчейн-пулы сообщают о значительно большем объёме операций, чем в предыдущие годы. В то же время исследования, такие как работа Lu и соавторов <a href="https://doi.org/10.1109/TDSC.2020.3043366" target="_blank" rel="noopener">Наслоение монет</a>, напоминают, что многоступенчатая архитектура приватности становится сложной в управлении при быстром масштабировании. Для повседневных операций миксеры и CoinJoin часто оказываются проще; свопы же наиболее полезны, когда задача — контролируемый вход или выход между разными блокчейнами.</p>
<h2 class="wp-block-heading">Дополнительная литература и инструменты</h2>
<ul>
<li><a href="https://blog.decred.org/2017/09/20/First-Atomic-Swap-Between-Decred-and-Litecoin/" target="_blank" rel="noopener">Объявление о свопе Decred ↔ Litecoin (2017)</a></li>
<li><a href="https://unstoppableswap.net/why" target="_blank" rel="noopener">Технический обзор UnstoppableSwap</a></li>
<li><a href="https://thorchain.org/blog" target="_blank" rel="noopener">Инженерные обновления THORChain</a></li>
<li><a href="https://www.fool.com/terms/a/atomic-swap/" target="_blank" rel="noopener">Объяснение атомарных свопов простым языком</a></li>
<li><a href="https://doi.org/10.1109/TDSC.2020.3043366" target="_blank" rel="noopener">Лу и соавторы, CoinLayering</a></li>
</ul>
<p>Атомарные свопы дополняют миксеры и CoinJoin; они не заменяют ни один из этих инструментов. Используйте для свопов только средства законного происхождения, сохраняйте метки для полученных выходов и используйте <a href="aml-check.html">AML-чекер BitMixList</a> перед взаимодействием с регулируемыми площадками, которые могут запросить подтверждение происхождения средств.</p>
