---
# HARDWIRED: legacy root HTML is source of truth; not a blog post
slug: exch-seizure
status: draft
published_at: 2025-06-01T00:00:00Z
updated_at: 2026-02-19T00:00:00Z
author: NotATether
canonical_path: exch-seizure.html
body_format: html
locales:
  en:
    title: "eXch Seizure & Bybit Breach Fallout (2025)"
    description: "eXch seizure case study: timeline from the Bybit breach to the 2025 FIOD action, what was seized, and how exchanges expanded freeze policies afterward."
  ru:
    title: Конфискация eXch и последствия взлома Bybit (2025)
    description: "Пример конфискации eXch: график от взлома Bybit до действий FIOD в 2025 году, что было конфисковано и как биржи впоследствии расширили политику замораживания."
    body: ""
---
<p>This page documents the seizure timeline, outlines what investigators actually captured, and summarizes the payment-processor backlash that followed. Use it alongside the <a href="exchange-freezes.html">exchange-freeze survival guide</a>, <a href="private-exchanges.html">private exchange case studies</a>, and the <a href="roman-storm-case.html">Roman Storm/Tornado Cash trial recap</a> to see how regulators stitch these actions into a single narrative.</p>
        <h2 class="wp-block-heading" id="timeline">Key Events: Bybit Hack to FIOD Raid</h2>
        <p>Tracking the sequence helps explain why prosecutors moved so quickly, and why bounty hunters, exchanges, and law enforcement all converged on the same target.</p>
        <ul>
          <li><strong>February 2025 &ndash; Bybit discloses compromise:</strong> Bybit confirmed a private key attack that drained roughly $1.3&nbsp;billion. The exchange launched a $140&nbsp;million bounty for information leading to recovery (<a href="https://www.infosecurity-magazine.com/news/bybit-140m-bounty-recover-mega/" target="_blank" rel="noopener">Infosecurity Magazine</a>), unleashing a wave of bounty hunters and blockchain sleuths.</li>
          <li><strong>March &ndash; April 2025 &ndash; Pressure campaign:</strong> Self-styled investigators spammed mixers, P2P desks, and infrastructure providers with “settlement” threats: pay us or we’ll flag you to exchanges. eXch publicly rejected the extortion but noted that compliance inboxes were overwhelmed.</li>
          <li><strong>Early May 2025 &ndash; Warning signs:</strong> eXch posted on Bitcointalk that “friends in government” tipped them off about an incoming data grab. Withdrawals slowed as the team tried to evacuate cold wallets.</li>
          <li><strong>Mid-May 2025 &ndash; FIOD acts:</strong> The Dutch agency confirmed the seizure, mimicking the press-release cadence used in the Sinbad and Blender cases. Officials bragged about freezing addresses believed to hold Bybit bounty funds.</li>
        </ul>
        <p>The Dutch statement painted eXch as a fully custodial mixer, glossing over the fact that liquidity queues processed multi-hop swaps rather than traditional user deposits. The jurisdictional hook was simple: the servers lived in a Dutch data center, so prosecutors could characterize coordination as control.</p>
        <h2 class="wp-block-heading" id="seizure-details">What Investigators Captured</h2>
        <p>According to mirrored logs and partner testimony, authorities seized far more than public announcements suggested:</p>
        <ul>
          <li><strong>Server images:</strong> Full snapshots of the web front-end, matching engine, and wallet infrastructure, including encrypted keys.</li>
          <li><strong>Hot-wallet balances:</strong> Roughly 210&nbsp;BTC and 8,000&nbsp;ETH queued for payouts, plus smaller XMR/ZEC pools mid-mix.</li>
          <li><strong>User metadata:</strong> Support tickets, payment method notes, and Tor bridge metrics that had been stored for abuse mitigation.</li>
        </ul>
        <p>None of the confiscated funds were returned to users. Prosecutors claimed mutual legal assistance requests would handle restitution, but no MLAT process has surfaced. The outcome mirrors the <a href="cryptomixer-eu-seizure.html">Cryptomixer EU seizure</a> where public deterrence outweighed customer remediation.</p>
        <h2 class="wp-block-heading" id="processors">Payment-Processor &amp; Exchange Backlash</h2>
        <p>The raid provided compliance teams with a convenient scapegoat. Within days, fiat processors and exchanges circulated internal bulletins treating any eXch-tainted coins as suspect, even when customers provided proof-of-funds.</p>
        <ul>
          <li>Major ramps added eXch to heuristic blocklists, triggering automatic account closures and clawbacks.</li>
          <li>Chain-surveillance vendors raised risk scores, forcing OTC desks and <a href="localmonero-agoradesk-exit.html">remaining P2P platforms</a> to reject coins with a single eXch hop.</li>
          <li>Travel-rule middleware started voiding invoices after AML analysts overrode customer attestations, citing “involvement in the eXch situation.”</li>
        </ul>
        <p>This collective response is cataloged in our <a href="exchange-freezes.html">Exchange Freezes</a> explainer, but eXch was the catalyst. It demonstrated how a single seizure can justify network-wide debanking long after the actual infrastructure is gone.</p>
        <h2 class="wp-block-heading" id="lessons">Mitigation Lessons for Privacy Services</h2>
        <ol>
          <li><strong>Distribute infrastructure:</strong> Running all coordinators, relays, or liquidity bridges from one jurisdiction enables unilateral takedowns. Community relays or multi-party custody make it harder to equate coordination with control.</li>
          <li><strong>Maintain redundant communication:</strong> eXch’s only warning lived on Bitcointalk. Operators should sign status updates, host mirrors, and provide public keys so users can verify shutdown messages.</li>
          <li><strong>Document extortion attempts:</strong> “Pay us or we’ll flag you to Bybit” messages are the new phishing. Logging and publishing threats can inoculate the community and prove you refused off-book settlements.</li>
          <li><strong>Prepare for AML spillover:</strong> Keep sanitized payouts, xpubs, and travel-rule payloads handy. Payment gateways and other regulated entities quickly blocked addresses which received coins from eXch after the service was seized. Tools like the <a href="aml-check.html">BitMixList AML Checker</a> help prove coins were clean before entering a seized service.</li>
        </ol>
        <p>The facts captured here provide context whenever exchanges cite “the eXch case” to justify freezing legitimate funds.</p>
        <h2 class="wp-block-heading" id="references">References</h2>
        <ul>
          <li><a href="https://www.infosecurity-magazine.com/news/bybit-140m-bounty-recover-mega/" target="_blank" rel="noopener">Infosecurity Magazine: Bybit offers $140M bounty after mega breach (Feb 2025)</a></li>
          <li><a href="exchange-freezes.html">BitMixList: Exchange Freezes Guide</a></li>
          <li><a href="aftermath.html">BitMixList Aftermath Chapter</a></li>
          <li><a href="private-exchanges.html">BitMixList: Private Exchange Case Studies</a></li>
          <li><a href="roman-storm-case.html">BitMixList: Roman Storm / Tornado Cash Trial Recap</a></li>
        </ul>

<!--blog:locale:ru-->
<p>На этой странице описана хронология конфискации, разъясняется, что именно удалось получить следствию, а также обобщается последовавшая реакция со стороны платёжных процессоров. Используйте её вместе с <a href="exchange-freezes.html">руководством по «выживанию» при заморозках на биржах</a>, <a href="private-exchanges.html">кейсами частных обменников</a> и <a href="roman-storm-case.html">разбором дела Roman Storm/Tornado Cash</a>, чтобы понять, как регуляторы объединяют эти события в единый нарратив.</p>
<h2 class="wp-block-heading">Ключевые события: от взлома Bybit до рейда FIOD</h2>
<p>Отслеживание последовательности помогает понять, почему прокуроры действовали так быстро и почему баунти хантеры, биржи и правоохранительные органы сошлись на одной цели.</p>
<ul>
<li>Февраль 2025 — взлом Bybit: Bybit подтвердил атаку с использованием приватного ключа, в результате которой было выведено около 1,3 млрд долларов. Биржа объявила награду в размере 140 млн долларов за информацию, ведущую к возврату средств (<a href="https://www.infosecurity-magazine.com/news/bybit-140m-bounty-recover-mega/" target="_blank" rel="noopener">Infosecurity Magazine</a>), что вызвало волну активности со стороны баунти хантеров и блокчейн-аналитиков.</li>
<li>Март – апрель 2025 — кампания давления: самопровозглашённые «расследователи» засыпали миксеры, P2P-площадки и инфраструктурных провайдеров угрозами «урегулирования»: платите нам, иначе мы сообщим о вас на биржи. eXch публично отверг вымогательство, но отметил, что их комплаенс-почта была перегружена.</li>
<li>Начало мая 2025 — тревожные сигналы: eXch сообщил на Bitcointalk, что «друзья в правительстве» предупредили их о предстоящем изъятии данных. Вывод средств замедлился, поскольку команда пыталась вывести активы с холодных кошельков.</li>
<li>Середина мая 2025 — действия FIOD: нидерландское ведомство подтвердило конфискацию, повторяя стиль пресс-релизов, использованный в делах Sinbad и Blender. Представители заявили о заморозке адресов, предположительно содержащих средства из наград Bybit.</li>
</ul>
<p>В официальном заявлении нидерландской стороны eXch был представлен как полностью кастодиальный миксер, при этом было упущено, что очереди ликвидности обрабатывали многошаговые свопы, а не классические пользовательские депозиты. Юрисдикционное обоснование было простым: серверы находились в нидерландском дата-центре, что позволило прокурорам трактовать координацию как контроль.</p>
<h2 class="wp-block-heading">Что именно изъяли следственные органы</h2>
<p>Согласно зеркальным логам и показаниям партнеров, власти изъяли гораздо больше, чем следует из публичных заявлений:</p>
<ul>
<li>Образы серверов: полные снимки веб-фронтенда, matching-engine и кошельковой инфраструктуры, включая зашифрованные ключи.</li>
<li>Балансы горячих кошельков: около 210 BTC и 8 000 ETH, ожидавших выплат, а также меньшие пулы XMR/ZEC в процессе микширования.</li>
<li>Пользовательские метаданные: тикеты поддержки, заметки о способах оплаты и метрики мостов Tor, которые хранились для предотвращения злоупотреблений.</li>
</ul>
<p>Ни одно из конфискованных средств не было возвращено пользователям. Прокуроры заявили, что реституция будет осуществляться через механизмы международной правовой помощи, однако никаких процедур MLAT так и не появилось. Итог повторяет кейс с <a href="cryptomixer-eu-seizure.html">конфискацией Cryptomixer в ЕС</a>, где публичный эффект сдерживания оказался важнее компенсации клиентам.</p>
<h2 class="wp-block-heading">Реакция платежных процессоров и бирж</h2>
<p>Рейд дал комплаенс-командам удобный повод для ужесточения мер. В течение нескольких дней фиатные процессоры и биржи начали распространять внутренние уведомления, в которых любые монеты, связанные с eXch, рассматривались как подозрительные — даже в случаях, когда клиенты предоставляли подтверждение происхождения средств.</p>
<ul>
<li>Крупные фиатные шлюзы добавили eXch в эвристические блок-листы, что привело к автоматическим закрытиям аккаунтов и возвратам средств.</li>
<li>Провайдеры блокчейн-аналитики повысили риск-оценки, из-за чего OTC-дески и <a href="localmonero-agoradesk-exit.html">оставшиеся P2P-платформы</a> стали отклонять монеты даже при одном взаимодействии с eXch.</li>
<li>Промежуточное ПО для соблюдения Travel Rule начало аннулировать инвойсы после того, как AML-аналитики отклоняли подтверждения клиентов, ссылаясь на «связь с ситуацией вокруг eXch».</li>
</ul>
<p>Эта коллективная реакция описана в нашем разборе <a href="exchange-freezes.html">Заморозки бирж</a>, однако именно eXch стал триггером. Этот кейс показал, как одна конфискация может привести к масштабному отключению от финансовой инфраструктуры, даже спустя долгое время после исчезновения самого сервиса.</p>
<h2 class="wp-block-heading">Выводы по снижению рисков для сервисов приватности</h2>
<p>1 Распределяйте инфраструктуру: размещение всех координаторов, реле или мостов ликвидности в одной юрисдикции позволяет провести одностороннее закрытие. Реле, управляемые сообществом, или мультистороннее хранение усложняют попытки приравнять координацию к контролю.</p>
<ol>
<li>Поддерживайте резервные каналы связи: у eXch единственное предупреждение было опубликовано на Bitcointalk. Операторам стоит подписывать обновления статуса, размещать зеркала и предоставлять публичные ключи, чтобы пользователи могли проверять сообщения о закрытии.</li>
<li>Фиксируйте попытки вымогательства: сообщения в духе «заплатите нам, иначе мы пожалуемся на вас в Bybit» — это новая форма фишинга. Логирование и публикация таких угроз помогают защитить сообщество и подтвердить отказ от неформальных договоренностей.</li>
<li>Готовьтесь к эффекту AML-перекрытия: держите под рукой очищенные выплаты, xpub и данные для соблюдения Travel Rule. Платежные шлюзы и другие регулируемые участники быстро блокировали адреса, получившие средства с eXch после его конфискации. Инструменты вроде <a href="aml-check.html">AML-чекера BitMixList</a> помогают подтвердить, что средства были чистыми до попадания в изъятый сервис.</li>
<li>Приведенные факты дают контекст в ситуациях, когда биржи ссылаются на «кейс eXch», чтобы обосновать заморозку легитимных средств.</li>
</ol>
<h2 class="wp-block-heading">Источники</h2>
<ul>
<li><a href="https://www.infosecurity-magazine.com/news/bybit-140m-bounty-recover-mega/" target="_blank" rel="noopener">Infosecurity Magazine: Bybit предлагает вознаграждение в $140 млн после масштабного взлома (февраль 2025)</a></li>
<li><a href="exchange-freezes.html">BitMixList: руководство по заморозкам на биржах</a></li>
<li><a href="aftermath.html">BitMixList Aftermath Chapter — раздел eXch</a></li>
<li><a href="private-exchanges.html">BitMixList: кейсы частных обменников</a></li>
<li><a href="roman-storm-case.html">BitMixList: обзор судебного дела Roman Storm / Tornado Cash</a></li>
</ul>
