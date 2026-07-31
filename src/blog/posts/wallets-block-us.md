---
# HARDWIRED: legacy root HTML is source of truth; not a blog post
slug: wallets-block-us
status: draft
published_at: 2025-06-05T00:00:00Z
updated_at: 2026-02-19T00:00:00Z
author: NotATether
canonical_path: wallets-block-us.html
body_format: html
locales:
  en:
    title: "Wallets Block US Users & Wasabi Shutdown"
    description: "Why privacy wallets blocked U.S. users after the Samourai arrests, including Wasabi and Ginger, plus practical steps for maintaining lawful privacy workflows."
  ru:
    title: Кошельки блокируют пользователей из США и закрытие Wasabi
    description: Аресты Samourai в апреле 2024 года почти мгновенно изменили поведение кошельков.
    body: ""
---
<p>The Samourai arrests in April 2024 changed wallet behavior almost immediately. Projects that had marketed privacy as a core feature started geofencing U.S. traffic, pausing coordinators, or removing tools that could be interpreted as mixing infrastructure. Public explanations usually referenced legal uncertainty and the <a href="fbi-non-custodial-warning.html">FBI PSA on non-custodial wallets</a>, but the practical pressure came from multiple directions at once: exchange freezes, banking alerts, and sanctions-driven compliance reviews.</p>
        <p>Wasabi, Railgun-facing interfaces, and several adjacent services tightened access in stages. Ginger, a Wasabi-derived wallet, also moved back and forth on U.S. access as policy and banking pressure changed. This page tracks that pattern and focuses on what users can do if they need lawful privacy options without depending on one coordinator or one jurisdiction.</p>
        <h2 class="wp-block-heading" id="geoblocks">How Wallet Geofencing Took Hold</h2>
        <p>Geofencing did not happen in one move. Operators rolled out restrictions in waves: U.S. IP filtering on coordinators, region blocks in wallet front-ends, and conservative defaults in integrations that previously worked out of the box. Some hardware and software vendors also changed distribution policies because they did not want bundled privacy features to be interpreted as offering regulated money services.</p>
        <p>The core driver was secondary liability risk. Once exchanges began freezing CoinJoin-linked deposits and banks started citing policy alerts, wallet teams moved into defensive mode. In many cases, teams were reacting to potential exposure rather than confirmed court orders. The chilling effect alone was enough to change product decisions.</p>
        <h2 class="wp-block-heading" id="wasabi">Wasabi and Ginger: Shutdowns, Clones, and Reversals</h2>
        <p>Wasabi and Ginger became the clearest examples of two different responses. zkSNACKs shut down its coordinator in June 2024 and cited legal cost, exchange hostility, and enforcement risk. Community-run alternatives appeared, but the primary U.S.-accessible path many users depended on was gone.</p>
        <p>Ginger took a more adaptive route, toggling U.S. access as legal and commercial pressure shifted. Even when access returned, operators still warned users that exchange treatment could remain harsh. That is the key lesson: a geoblock can be lifted, but transaction reputation and compliance friction often remain.</p>
        <h2 class="wp-block-heading" id="impact">Impact on Users and Liquidity</h2>
        <p>For U.S. users, the short-term impact was immediate: fewer pools, longer waits, and higher operational friction. Geofenced coordinators often blocked U.S.-located Tor exits, forcing users to rotate network paths just to reach previously routine features. Exchange compliance teams also started grouping outputs from multiple privacy wallets into the same high-risk bucket, which meant more manual reviews and more source-of-funds requests.</p>
        <p>Liquidity did not disappear, but it shifted. More flow moved to offshore desks, invitation-based markets, and swap-driven routing via <a href="private-exchanges.html">private exchanges</a> and <a href="atomic-swaps.html">atomic swap tools</a>. Users who had relied on one familiar wallet UI suddenly had to learn a multi-tool workflow to keep comparable privacy outcomes.</p>
        <h2 class="wp-block-heading" id="actions">What U.S. Users Can Do</h2>
        <p>The most resilient approach is layered and documented. Keep multiple privacy paths available - CoinJoin variants, PayJoin, Cashu-style e-cash, and <a href="monero-privacy-alternative.html">Monero workflows</a> - so one product decision does not leave you stranded. Before touching regulated venues, keep clear origin records and pre-check exposure using the <a href="aml-check.html">BitMixList AML Checker</a>.</p>
        <p>Also monitor policy changes continuously. A wallet that works for U.S. users today can change access terms quickly after a new enforcement headline. The <a href="crackdown.html">crackdown timeline</a>, the <a href="fbi-non-custodial-warning.html">FBI PSA explainer</a>, and the <a href="samourai-wallet-case.html">Samourai case page</a> help you understand why these shifts happen and how to explain your transaction history when a bank or exchange asks questions.</p>
        <h2 class="wp-block-heading" id="references">References</h2>
        <ul>
          <li><a href="https://www.ic3.gov/Media/Y2024/PSA240425" target="_blank" rel="noopener">FBI IC3 PSA on non-custodial wallets (April 2024)</a></li>
          <li><a href="https://wasabiwallet.io/blog/zkSNACKs-Statement" target="_blank" rel="noopener">zkSNACKs statement announcing Wasabi coordinator shutdown</a></li>
          <li><a href="https://blog.coinbase.com/coinjoin-transactions-freeze-statement" target="_blank" rel="noopener">Exchange policy statement on CoinJoin deposits</a></li>
          <li><a href="https://gingerwallet.org/blog/us-access-update" target="_blank" rel="noopener">Ginger wallet update on U.S. geofence removal</a></li>
          <li><a href="https://home.treasury.gov/news/press-releases/jy1920" target="_blank" rel="noopener">Treasury mixer sanctions press release</a></li>
        </ul>

<!--blog:locale:ru-->
<p>Аресты Samourai в апреле 2024 года почти мгновенно изменили поведение кошельков. Проекты, которые раньше продвигали приватность как ключевую функцию, начали геоблокировать пользователей из США, приостанавливать работу координаторов или удалять инструменты, которые могли трактоваться как инфраструктура миксинга. Публичные объяснения обычно ссылались на юридическую неопределенность и предупреждение ФБР о некастодиальных кошельках, но реальное давление шло сразу по нескольким каналам: заморозки на биржах, сигналы от банков и проверки в рамках санкционного комплаенса.</p>
<p>Wasabi, интерфейсы, связанные с Railgun, и ряд смежных сервисов поэтапно ужесточали доступ. Ginger, кошелек на базе Wasabi, также несколько раз менял политику доступа для пользователей из США в зависимости от регуляторного и банковского давления. Эта страница отслеживает этот паттерн и фокусируется на том, что могут делать пользователи, которым нужны законные инструменты приватности без зависимости от одного координатора или одной юрисдикции.</p>
<h2 class="wp-block-heading" id="geoblocks">Как закрепилось геоблокирование в кошельках</h2>
<p>Геоблокирование появилось не одномоментно. Операторы вводили ограничения поэтапно: фильтрация IP из США на уровне координаторов, региональные блокировки во фронтендах кошельков и более консервативные настройки по умолчанию в интеграциях, которые раньше работали «из коробки». Некоторые производители аппаратных и программных решений также пересмотрели политику распространения, чтобы встроенные функции приватности не трактовались как предоставление регулируемых финансовых услуг.</p>
<p>Ключевым фактором стал риск вторичной ответственности. После того как биржи начали замораживать депозиты, связанные с CoinJoin, а банки — ссылаться на комплаенс-уведомления, команды кошельков перешли в оборонительный режим. Во многих случаях речь шла не о прямых судебных предписаниях, а о реакции на потенциальные риски. Сам «охлаждающий эффект» оказался достаточным, чтобы изменить продуктовые решения.</p>
<h2 class="wp-block-heading" id="wasabi">Wasabi и Ginger: закрытия, клоны и откаты</h2>
<p>Wasabi и Ginger стали наиболее наглядными примерами двух разных подходов. zkSNACKs закрыл свой координатор в июне 2024 года, сославшись на юридические издержки, давление со стороны бирж и риски правоприменения. Появились альтернативы, управляемые сообществом, но основной путь доступа для пользователей из США, на который многие рассчитывали, исчез.</p>
<p>Ginger выбрал более гибкую стратегию, включая и отключая доступ для пользователей из США по мере изменения юридического и коммерческого давления. Даже когда доступ возвращался, операторы предупреждали, что отношение со стороны бирж может оставаться жестким. В этом и заключается ключевой вывод: геоблокировку можно снять, но репутация транзакций и комплаенс-трение часто сохраняются.</p>
<h2 class="wp-block-heading" id="impact">Влияние на пользователей и ликвидность</h2>
<p>Для пользователей из США краткосрочные последствия были мгновенными: меньше пулов, более долгие ожидания и рост операционных издержек. Геоблокированные координаторы часто начали блокировать выходы Tor из США, заставляя пользователей менять сетевые маршруты даже для доступа к базовым функциям. Комплаенс-команды бирж также стали объединять выходы из разных кошельков приватности в одну «высокорисковую» категорию, что привело к большему числу ручных проверок и запросов происхождения средств.</p>
<p>Ликвидность не исчезла, но сместилась. Все больше потоков перешло к офшорным дескам, рынкам с доступом по приглашениям и маршрутам через свопы — с использованием <a href="private-exchanges.html">частных обменников</a> и <a href="atomic-swaps.html">инструментов атомарных свопов</a>. Пользователи, привыкшие к одному интерфейсу кошелька, внезапно столкнулись с необходимостью осваивать многослойный набор инструментов, чтобы сохранить сопоставимый уровень приватности.</p>
<h2 class="wp-block-heading" id="actions">Что могут сделать пользователи из США</h2>
<p>Наиболее устойчивый подход — многоуровневый и документированный. Держите несколько путей приватности: варианты CoinJoin, PayJoin, электронную наличность по модели Cashu и <a href="monero-privacy-alternative.html">сценарии с Monero</a>, чтобы одно решение продукта не оставило вас без альтернатив. Перед взаимодействием с регулируемыми площадками сохраняйте четкие записи о происхождении средств и заранее проверяйте риски с помощью <a href="aml-check.html">AML-чекера BitMixList</a>.</p>
<p>Также важно постоянно отслеживать изменения политики. Кошелек, который сегодня работает для пользователей из США, может быстро изменить условия доступа после нового инфоповода в правоприменении. <a href="crackdown.html">Хронология давления на индустрию</a>, <a href="fbi-non-custodial-warning.html">разбор предупреждений ФБР</a> и <a href="samourai-wallet-case.html">страница по делу Samourai</a> помогают понять причины этих изменений и подготовиться к объяснению истории транзакций при запросах со стороны банка или биржи.</p>
<h2 class="wp-block-heading" id="references">Источники</h2>
<ul>
<li><a href="https://www.ic3.gov/Media/Y2024/PSA240425" rel="noopener" target="_blank">ФБР IC3: предупреждение о некастодиальных кошельках (апрель 2024)</a></li>
<li><a href="https://wasabiwallet.io/blog/zkSNACKs-Statement" rel="noopener" target="_blank">Заявление zkSNACKs о закрытии координатора Wasabi</a></li>
<li><a href="https://blog.coinbase.com/coinjoin-transactions-freeze-statement" rel="noopener" target="_blank">Политическое заявление бирж о депозитах, связанных с CoinJoin</a></li>
<li><a href="https://gingerwallet.org/blog/us-access-update" rel="noopener" target="_blank">Обновление кошелька Ginger об отмене геоблокировки для СШ</a></li>
<li><a href="https://home.treasury.gov/news/press-releases/jy1920" rel="noopener" target="_blank">Пресс-релиз Министерства финансов о санкциях против миксеров</a></li>
</ul>
