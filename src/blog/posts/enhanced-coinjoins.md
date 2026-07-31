---
slug: enhanced-coinjoins
status: published
published_at: 2025-06-05T00:00:00Z
updated_at: 2025-06-05T00:00:00Z
author: NotATether
canonical_path: enhanced-coinjoins.html
body_format: html
locales:
  en:
    title: Enhanced CoinJoins
    description: "Whirlpool, WabiSabi, and other advanced CoinJoin designs built after the first generation."
  ru:
    title: Усовершенствованные CoinJoins
    description: "Whirlpool, WabiSabi и другие передовые разработки CoinJoin, созданные после первого поколения."
    body: ""
---
<p>Second-generation CoinJoin projects like Whirlpool, WabiSabi, and SNICKER introduced larger anonymity sets and coordinated payouts between 2018 and 2024. Here is a quick tour.</p>
        <h2 class="wp-block-heading" id="whirlpool">Whirlpool</h2>
        <ul>
          <li><strong>2019:</strong> Samourai launches Whirlpool with cyclical “mix to remix” logic.</li>
          <li><strong>2020–2022:</strong> zkSNACKs implements WabiSabi to allow unequal outputs while maintaining privacy.</li>
          <li><strong>2023–2024:</strong> Research like SNICKER/JoinMarket v0.9 explores payjoin-style mixes without centralized coordinators.</li>
        </ul>
        <h2 class="wp-block-heading" id="updates">Why Enhancements Matter</h2>
        <ul>
          <li>Payjoin, Stonewallx2, and other techniques hide mixes inside ordinary-looking spends.</li>
          <li>WabiSabi’s keyed credentials allow unequal outputs so users can consolidate without leaking value.</li>
          <li>Whirlpool’s “mix-to-remix” increases anonymity sets over time.</li>
        </ul>
        <p>The <a href="coinjoin.html">CoinJoin explainer</a> covers the underlying math; this page captures the newer UX and policy debates referenced in <a href="evolving-regulation.html">Evolving Regulation</a>.</p>
        <h2 class="wp-block-heading" id="future">Future Work</h2>
        <p>Developers are experimenting with federated coordinators, blinded liquidity credentials, and covenant-based designs to embed privacy deeper into Bitcoin.</p>
        <ul>
          <li><strong>2019:</strong> OKEx Korea and Upbit remove XMR and ZEC after FATF issues the travel-rule guidance. European brokers begin geofencing German and Dutch IPs.</li>
          <li><strong>2020–2021:</strong> Bithumb, Coincheck, and Australia’s Independent Reserve ax privacy pairs. Binance restricts XMR margin products in multiple regions.</li>
          <li><strong>2022:</strong> Kraken delists XMR for U.K. customers after FCA pressure; Huobi exits the privacy market globally; FTX (pre-collapse) reclassifies Monero withdrawals as "manual review only."</li>
          <li><strong>2023 onward:</strong> Coinbase halts inbound XMR transfers entirely, and EU MiCA consultations single out mixers and privacy coins for "optional disintermediation" clauses.</li>
        </ul>
        <p>Where exchanges complied most aggressively—South Korea, Japan, the U.K.—lawmakers cited ransomware narratives and the difficulty of satisfying the "travel rule" with shielded transactions.</p>
        <h2 class="wp-block-heading" id="drivers">Why Exchanges Folded</h2>
        <ol>
          <li><strong>Correspondent banking choke points:</strong> Fiat partners threatened to terminate settlement accounts if platforms left XMR live.</li>
          <li><strong>Licensing leverage:</strong> Regulators tied VASP renewals to "enhanced transparency" commitments, effectively forcing delistings.</li>
          <li><strong>Analytics vendor lobbying:</strong> Chain-surveillance firms framed privacy coins as "un-scorable" to upsell new monitoring tools.</li>
        </ol>
        <p>Meanwhile, privacy coin communities argued that the same exchanges happily listed outright scams. Delistings hurt volume but did not eradicate Monero: OTC desks, peer-to-peer swaps, and <a href="private-exchanges.html">second-wave private exchanges</a> filled the gap.</p>
        <h2 class="wp-block-heading" id="workarounds">How Users Adapted</h2>
        <ul>
          <li><strong>Peer-to-peer markets:</strong> Platforms like <a href="https://agoradesk.com/" target="_blank" rel="noopener">Agoradesk</a> and <a href="https://learn.robosats.com/" target="_blank" rel="noopener">Robosats</a> became primary on-ramps for Monero.</li>
          <li><strong>Atomic swaps:</strong> <a href="atomic-swaps.html">Atomic swap bridges</a> let users move between BTC and XMR without centralized custody.</li>
          <li><strong>Decentralized liquidity:</strong> Community-run liquidity bots recycled XMR liquidity into wrapped assets and stablecoins.</li>
        </ul>
        <p>Regulators never proved that delistings reduced crime; instead they pushed liquidity into less supervised venues. That mirrors the lesson from <a href="exchange-freezes.html">exchange freezes</a>: compliance crackdowns usually punish ordinary users first.</p>
        <h2 class="wp-block-heading" id="implications">Implications for Mixers and Wallets</h2>
        <p>Mixers seldom rely on privacy coins directly, but delistings forced them to reconsider payout options. Some services added XMR withdrawals to let customers escape tainted BTC, while others doubled down on CoinJoin tooling. Wallets that integrate swaps (e.g., Feather, Cake) became essential bridges between Bitcoin privacy tools and the XMR liquidity layer.</p>

<!--blog:locale:ru-->
<p>Проекты CoinJoin второго поколения, такие как Whirlpool, WabiSabi и SNICKER, внедрили более крупные наборы анонимности и координированные выплаты в период с 2018 по 2024 год. Вот краткий обзор.</p>
<h2 class="wp-block-heading">Whirlpool</h2>
<ul>
<li>2019: Samourai запускает Whirlpool с циклической логикой «mix to remix».</li>
<li>2020–2022: zkSNACKs внедряет WabiSabi, позволяя использовать неравные выходы при сохранении приватности.</li>
<li>2023–2024: исследования, такие как SNICKER и JoinMarket v0.9, изучают модели миксинга в стиле PayJoin без централизованных координаторов.</li>
</ul>
<h2 class="wp-block-heading">Почему совершенствования имеют значение</h2>
<ul>
<li>PayJoin, Stonewallx2 и другие техники маскируют миксинг под обычные транзакции.</li>
<li>Ключевые учётные данные WabiSabi позволяют использовать неравные выходы, благодаря чему пользователи могут консолидировать средства без раскрытия их суммы.</li>
<li>Логика «mix-to-remix» в Whirlpool со временем увеличивает набор анонимности.</li>
<li><a href="coinjoin.html">Объяснение CoinJoin</a> охватывает базовую математику, тогда как этот раздел фокусируется на более современных аспектах UX и регуляторных дискуссиях, рассмотренных в разделе <a href="evolving-regulation.html">Эволюция регулирования</a>.</li>
</ul>
<h2 class="wp-block-heading">Последующая работа</h2>
<p>Разработчики экспериментируют с федеративными координаторами, слепыми (blinded) учётными данными ликвидности и дизайнами на основе ковенантов, чтобы глубже встроить приватность в Биткоин.</p>
<p>OKEx Korea и Upbit удаляют XMR и ZEC после того, как FATF выпускает руководство по Travel Rule. Европейские брокеры начинают геоблокировать IP-адреса из Германии и Нидерландов. 2020–2021: Bithumb, Coincheck и австралийская Independent Reserve убирают пары с приватными монетами. Binance ограничивает маржинальные продукты с XMR в нескольких регионах. 2022: Kraken исключает XMR для клиентов из Великобритании под давлением FCA; Huobi полностью выходит из сегмента приватных монет; FTX (до краха) переводит вывод Monero в режим «только с ручной проверкой». С 2023 года: Coinbase полностью прекращает входящие переводы XMR, а консультации по регулированию MiCA в ЕС отдельно выделяют миксеры и приватные монеты, включая положения об «опциональной деинтермедиации».</p>
<p>В юрисдикциях, где биржи действовали наиболее жёстко — Южная Корея, Япония и Великобритания — законодатели ссылались на кейсы с программами вымогателями и сложности соблюдения «правила путешествия» при использовании приватных транзакций.</p>
<h2 class="wp-block-heading">Почему биржи уступили</h2>
<ol>
<li>Узкие места в корреспондентском банкинге: фиатные партнёры угрожали закрыть расчётные счета, если платформы продолжат поддерживать XMR.</li>
<li>Давление через лицензирование: регуляторы увязывали продление лицензий VASP с обязательствами по «повышенной прозрачности», фактически вынуждая к делистингу.</li>
<li>Лоббирование со стороны аналитических компаний: фирмы по блокчейн-аналитике представляли приватные монеты как «неподдающиеся оценке», тем самым продвигая свои новые инструменты мониторинга.</li>
</ol>
<p>Тем временем сообщества приватных монет указывали, что те же биржи без проблем листят откровенно сомнительные проекты. Делистинги снизили объёмы торгов, но не уничтожили Monero: OTC-дески, P2P-обмены и <a href="private-exchanges.html">вторая волна частных обменников</a> закрыли этот пробел.</p>
<h2 class="wp-block-heading">Как пользователи адаптировались</h2>
<ul>
<li>Одноранговые рынки: платформы вроде <a href="https://agoradesk.com/" target="_blank" rel="noopener">Agoradesk</a> и <a href="https://learn.robosats.com/" target="_blank" rel="noopener">Robosats</a> стали основными точками входа в Monero.</li>
<li>Атомарные свопы: <a href="atomic-swaps.html">мосты на основе атомарных свопов</a> позволяют пользователям обменивать BTC и XMR без централизованного хранения средств.</li>
<li>Децентрализованная ликвидность: управляемые сообществом боты ликвидности перераспределяют XMR в обёрнутые активы и стейблкоины.</li>
<li>Регуляторы так и не доказали, что делистинги снизили уровень преступности; вместо этого ликвидность просто переместилась в менее регулируемые сегменты.</li>
<li>Это повторяет урок <a href="exchange-freezes.html">заморозок на биржах</a>: ужесточение комплаенса чаще всего в первую очередь бьёт по обычным пользователям.</li>
</ul>
<h2 class="wp-block-heading">Последствия для миксеров и кошельков</h2>
<p>Миксеры редко напрямую полагаются на приватные монеты, но делистинги заставили их пересмотреть варианты выплат. Некоторые сервисы добавили вывод в XMR, чтобы позволить пользователям уйти от «запятнанных» BTC, тогда как другие сделали упор на инструменты CoinJoin. Кошельки с интегрированными свопами (например, Feather, Cake) стали важным мостом между инструментами приватности Биткоина и ликвидностью XMR.</p>
