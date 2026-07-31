---
slug: secret-service-mixer-advisory
status: published
published_at: 2026-02-12T00:00:00Z
updated_at: 2026-02-19T00:00:00Z
author: NotATether
canonical_path: secret-service-mixer-advisory.html
body_format: html
locales:
  en:
    title: The Secret Service’s Mixer Advisory
    description: "U.S. Secret Service mixer advisory explained: key warnings for banks, enforcement signals for custodial mixers, and privacy lessons for users and developers."
  ru:
    title: Рекомендации Secret Service по миксерам
    description: "В мае 2025 года U.S. Secret Service опубликовала публичные рекомендации по криптовалютным миксерам, ориентированные прежде всего на банки, комплаенс-команды бирж и следственные органы."
    body: ""
---
<p>In May 2025, the U.S. Secret Service released a <a href="https://www.secretservice.gov/sites/default/files/reports/2025-06/Public-Alert-Cryptocurrency-Mixing.pdf" target="_blank" rel="noopener">public advisory on cryptocurrency mixers</a> aimed primarily at banks, exchange compliance teams, and investigators. The advisory frames custodial mixing services as high-risk infrastructure in ransomware and sanctions cases, and it encourages institutions to escalate monitoring whenever mixer-linked flows appear.</p>
        <p>For privacy users and developers, the document is useful because it shows how law-enforcement messaging is translated into day-to-day banking controls. This page summarizes the most practical warnings and explains why the guidance creates tension with the common mixer marketing claim that services keep little or no usable record data.</p>
        <h2 class="wp-block-heading" id="key-points">Key Warnings from the Advisory</h2>
        <p>The advisory does not read like abstract policy. It is written as an operational playbook for institutions that need to decide when to freeze funds, file reports, and notify investigators.</p>
        <ul>
          <li><strong>Mixers are money businesses:</strong> The Secret Service reiterates that custodial tumblers act as money transmitters, regardless of whether they call themselves “privacy tools.” That echoes the <a href="fincen-2019-guidance.html">FinCEN 2019 guidance</a> that put mixers squarely inside Bank Secrecy Act obligations.</li>
          <li><strong>Investigations follow the fiat exit:</strong> Agents highlight cases like <a href="helix-larry-harmon.html">Helix</a> and <a href="chipmixer-seizure.html">ChipMixer</a> where seizures occurred when laundered funds tried to hit exchanges or OTC desks.</li>
        <li><strong>Seizure-ready playbook:</strong> The advisory coaches banks to freeze deposits that touched a mixer, file Suspicious Activity Reports, and loop agents in before any suspect attempts to cash out.</li>
        </ul>
        <h2 class="wp-block-heading" id="log-irony">The Log-Keeping Irony</h2>
        <p>Many mixer landing pages still promise "no logs" or fast record deletion, but the advisory repeatedly emphasizes evidence preservation and infrastructure correlation. Investigations described by agencies typically combine seized server data, exchange submissions, and communication artifacts to reconstruct transaction paths, even when a service publicly claims not to retain identifying data.</p>
        <p>That creates a basic contradiction users should notice. If institutions are instructed to collect and preserve artifacts before freezing funds, then practical log surfaces exist somewhere in the pipeline, whether at the mixer, at counterparties, or at the exchange off-ramp.</p>
        <p>The irony appears most clearly in two places:</p>
        <ol>
          <li><strong>Marketing spin collapses:</strong> If compliance desks are told to expect log requests, then “no log” banners simply advertise that the operator is willing to delete evidence—precisely the behaviour that gets admins charged, as <a href="helix-larry-harmon.html">Larry Harmon</a> discovered.</li>
          <li><strong>Custodial design is the root cause:</strong> The few systems that truly cannot log anything (for example, <a href="coinjoin.html">CoinJoin</a> implementations) are outside the advisory’s scope because they never take custody. The USSS warning therefore reads like a reminder that centralized tumblers are surveillance honeypots masquerading as privacy.</li>
        </ol>
        <p>Mixers insisting that “logs are purged after 24 hours” should expect compliance teams to cite the advisory as proof that those claims cannot be trusted.</p>
        <h2 class="wp-block-heading" id="recommendations">What the Secret Service Wants Institutions to Do</h2>
        <p>At a process level, the guidance pushes institutions toward faster escalation and better evidence handling rather than passive transaction monitoring.</p>
        <ul>
          <li><strong>Correlate mixer inflows with scams:</strong> Banks are told to compare mixer-linked transactions with reports of romance scams, business email compromise, and investment fraud.</li>
          <li><strong>Screen for sanctioned actors:</strong> The advisory points to <a href="sinbad-sanctions.html">Sinbad</a> and Blender as proof that mixers now fall under OFAC regimes.</li>
          <li><strong>Preserve evidence:</strong> Institutions should retain logs, wallet addresses, and communication history before freezing assets so they can hand investigators more than a raw transaction ID.</li>
        </ul>
        <h2 class="wp-block-heading" id="privacy-takeaways">Takeaways for Privacy Advocates</h2>
        <p>For privacy advocates, the practical takeaway is that custodial infrastructure remains the easiest pressure point for both regulators and financial institutions. If a service controls funds, hosts support channels, and intermediates payouts, it is likely to be treated as a compliance subject first and a privacy tool second.</p>
        <ul>
          <li>Centralized mixers remain the easiest enforcement target; decentralized and non-custodial coordination tools are harder to demonize because there is no server to seize.</li>
          <li>Users should assume that any custody-based mixer keeps enough metadata to satisfy a subpoena or that data will be reconstructed from seized keys.</li>
          <li>When a mixer’s FAQ boasts about “no logs,” ask how they generate refund letters or support tickets—if there is a ticketing system, there is a log.</li>
        </ul>
        <p>Privacy still matters, but this advisory makes the legal direction clear: turnkey custodial mixing businesses face sustained enforcement and reporting pressure. Over time, operators either move toward heavier compliance controls or users migrate toward self-custodial alternatives that reduce single points of seizure and log collection.</p>
        <h2 class="wp-block-heading" id="references">References</h2>
        <ul>
          <li><a href="https://www.secretservice.gov/sites/default/files/reports/2025-06/Public-Alert-Cryptocurrency-Mixing.pdf" target="_blank" rel="noopener">U.S. Secret Service – Public Advisory: Cryptocurrency Mixers (May 2025)</a></li>
          <li><a href="https://home.treasury.gov/news/press-releases/jy1468" target="_blank" rel="noopener">U.S. Treasury – OFAC designates Sinbad mixer (Nov 2023)</a></li>
          <li><a href="https://www.justice.gov/opa/pr/justice-department-announces-charges-broadest-cryptocurrency-enforcement-action-us-history" target="_blank" rel="noopener">U.S. Department of Justice – ChipMixer takedown announcement (Mar 2023)</a></li>
        </ul>

<!--blog:locale:ru-->
<p>В мае 2025 года U.S. Secret Service опубликовала публичные рекомендации по криптовалютным миксерам, ориентированные прежде всего на банки, комплаенс-команды бирж и следственные органы. В документе кастодиальные миксеры рассматриваются как инфраструктура повышенного риска в делах, связанных с вымогательством и санкциями, и учреждениям рекомендуется усиливать мониторинг при обнаружении потоков, связанных с миксерами.</p>
<p>Для пользователей и разработчиков в сфере приватности этот документ важен тем, что показывает, как риторика правоохранительных органов превращается в повседневные банковские практики контроля. На этой странице суммированы наиболее практические предупреждения и объясняется, почему такие рекомендации создают напряжение с распространенным маркетинговым тезисом миксеров о минимальном или отсутствующем хранении данных.</p>
<h2 class="wp-block-heading" id="key-points">Ключевые предупреждения из рекомендаций</h2>
<p>Рекомендации написаны не как абстрактная политика, а как практическое руководство для организаций, которым нужно решать, когда замораживать средства, подавать отчеты и уведомлять следственные органы.</p>
<ul>
<li>Миксеры рассматриваются как финансовые сервисы: Secret Service подчеркивает, что кастодиальные миксеры фактически действуют как операторы денежных переводов, независимо от того, называют ли они себя «инструментами приватности». Это перекликается с <a href="fincen-2019-guidance.html">разъяснениями FinCEN 2019 года</a>, которые включили миксеры в сферу требований Bank Secrecy Act.</li>
<li>Расследования фокусируются на выходе в фиат: приводятся примеры вроде <a href="helix-larry-harmon.html">Helix</a> и <a href="chipmixer-seizure.html">ChipMixer</a>, где изъятия происходили на этапе попытки вывода средств через биржи или OTC-дески.</li>
<li>Готовность к изъятию: банкам рекомендуется замораживать депозиты, связанные с миксерами, подавать отчеты о подозрительной активности и подключать следственные органы до того, как подозреваемый попытается обналичить средства.</li>
</ul>
<h2 class="wp-block-heading" id="log-irony">Парадокс хранения логов</h2>
<p>Многие сайты миксеров по-прежнему обещают «отсутствие логов» или быстрое удаление данных, но в рекомендациях неоднократно подчеркивается важность сохранения доказательств и сопоставления инфраструктуры. Описанные расследования обычно комбинируют изъятые серверные данные, информацию от бирж и коммуникационные артефакты, чтобы восстановить путь транзакций — даже если сервис публично заявляет, что не хранит идентифицирующие данные.</p>
<p>Это создает очевидное противоречие, на которое стоит обращать внимание. Если учреждениям предписано собирать и сохранять артефакты до заморозки средств, значит, точки хранения данных где-то в цепочке все же существуют — на стороне миксера, у контрагентов или на этапе вывода через биржу.</p>
<p>Парадокс особенно заметен в двух аспектах:</p>
<ol>
<li>Маркетинговые заявления не выдерживают проверки: если комплаенс-отделы ожидают запросы логов, то баннеры «no logs» фактически сигнализируют о готовности оператора удалять доказательства — именно такое поведение и становилось основанием для обвинений, как показал кейс Larry Harmon.</li>
<li>Кастодиальная модель — ключевая причина: системы, которые действительно не могут вести логи (например, реализации <a href="coinjoin.html">CoinJoin</a>), находятся вне фокуса рекомендаций, потому что не принимают средства на хранение. В этом смысле предупреждение выглядит как напоминание о том, что централизованные миксеры могут превращаться в точки сбора данных, несмотря на заявленную приватность.</li>
</ol>
<p>Миксеры, утверждающие, что «логи удаляются через 24 часа», должны учитывать, что комплаенс-команды будут ссылаться на такие рекомендации как на аргумент в пользу недоверия к подобным заявлениям.</p>
<h2 class="wp-block-heading" id="recommendations">Что Secret Service требует от организаций</h2>
<p>На уровне процессов рекомендации подталкивают организации к более быстрой эскалации и более тщательной работе с доказательствами, а не к пассивному мониторингу транзакций.</p>
<ul>
<li>Сопоставлять входящие потоки через миксеры с мошенничеством: банкам рекомендуется сравнивать такие транзакции с кейсами романтических схем, компрометации деловой переписки и инвестиционных афер.</li>
<li>Проверять на санкционные риски: в рекомендациях упоминаются <a href="sinbad-sanctions.html">Sinbad</a> и Blender как примеры того, что миксеры подпадают под режимы OFAC.</li>
<li>Сохранять доказательства: учреждения должны фиксировать логи, адреса кошельков и историю коммуникаций до заморозки средств, чтобы передавать следственным органам не только идентификаторы транзакций, но и более полный контекст.</li>
</ul>
<h2 class="wp-block-heading" id="privacy-takeaways">Выводы для сторонников приватности</h2>
<p>Для сторонников приватности практический вывод в том, что кастодиальная инфраструктура остается самой уязвимой точкой давления для регуляторов и финансовых институтов. Если сервис контролирует средства, ведет поддержку и выступает посредником при выплатах, его, скорее всего, будут рассматривать в первую очередь как объект комплаенса, а уже потом как инструмент приватности.</p>
<ul>
<li>Централизованные миксеры остаются самой простой целью для преследования; децентрализованные и некастодиальные инструменты координации сложнее атаковать, поскольку нет единого сервера для изъятия.</li>
<li>Пользователям стоит исходить из того, что любой кастодиальный миксер хранит достаточно метаданных для выполнения судебного запроса, либо такие данные могут быть восстановлены из изъятой инфраструктуры.</li>
<li>Если в FAQ миксера заявлено «нет логов», стоит задать простой вопрос: как тогда обрабатываются возвраты или обращения в поддержку — если есть тикет-система, значит есть и логи.</li>
</ul>
<p>Приватность по-прежнему важна, но эти рекомендации ясно показывают направление регулирования: кастодиальные миксеры будут находиться под постоянным давлением в части контроля и отчетности. Со временем операторы либо переходят к более жестким комплаенс-моделям, либо пользователи смещаются в сторону некастодиальных решений, которые уменьшают количество точек для изъятия и сбора данных.</p>
<h2 class="wp-block-heading" id="references">Источники</h2>
<ul>
<li><a href="https://www.secretservice.gov/sites/default/files/reports/2025-06/Public-Alert-Cryptocurrency-Mixing.pdf" rel="noopener" target="_blank">U.S. Secret Service – публичные рекомендации: Cryptocurrency Mixers (май 2025)</a></li>
<li><a href="https://home.treasury.gov/news/press-releases/jy1468" rel="noopener" target="_blank">U.S. Treasury – OFAC включает миксер Sinbad в санкционный список (ноябрь 2023)</a></li>
<li><a href="https://www.justice.gov/opa/pr/justice-department-announces-charges-broadest-cryptocurrency-enforcement-action-us-history" rel="noopener" target="_blank">U.S. Department of Justice – объявление о ликвидации ChipMixer (март 2023)</a></li>
</ul>
