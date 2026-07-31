---
# HARDWIRED: legacy root HTML is source of truth; not a blog post
slug: mica-privacy-rules
status: draft
published_at: 2025-02-12T00:00:00Z
updated_at: 2026-02-19T00:00:00Z
author: NotATether
canonical_path: mica-privacy-rules.html
body_format: html
locales:
  en:
    title: "EU MiCA & Privacy Restrictions"
    description: "EU MiCA privacy rules explained: CASP licensing, Travel Rule checks, unhosted wallet verification, and what mixer users should expect on regulated exchanges."
  ru:
    title: ЕС MiCA и ограничения приватности
    description: "MiCA часто описывают как рамку лицензирования криптоиндустрии, но для пользователей, ориентированных на приватность, ее лучше рассматривать как часть более широкой комплаенс-системы."
    body: ""
---
<p>MiCA is often described as a crypto licensing framework, but for privacy users it is better understood as part of a wider compliance stack. In practice, MiCA rules for service providers sit next to the EU AML package and the updated transfer rules, which means exchanges and custodians are expected to collect more user information and treat privacy-linked flows as higher risk. The result is not a direct ban on code, but a market structure where regulated venues increasingly avoid activity they cannot explain to supervisors.</p>
        <p>That distinction matters for anyone using mixers, CoinJoin tools, or self-custody workflows. The software itself can remain legal while access to fiat ramps, stablecoin rails, and major EU exchanges becomes tighter. For users, the operational question shifts from "is this tool legal?" to "can I still move funds through licensed intermediaries after using it?"</p>
        <h2 class="wp-block-heading" id="vasp">VASP Licensing & Custody Rules</h2>
        <p>MiCA requires crypto-asset service providers (CASPs) to obtain authorization from a national competent authority before offering services across the EU. Once licensed, those firms are expected to run compliance controls that look much closer to traditional financial institutions than early crypto exchanges did.</p>
        <ul>
          <li>Identify customers and beneficial owners before providing custody, exchange, or transfer services.</li>
          <li>Maintain auditable transaction and customer records for multi-year retention periods.</li>
          <li>Refuse or pause deposits and withdrawals from wallets they cannot classify with enough confidence for AML controls.</li>
        </ul>
        <p>Member states can also decline licensing where a business model depends on anonymity-enhancing services in a way that prevents effective AML supervision. In plain terms, if a platform cannot show how it monitors risk around mixers and similar tools, licensing becomes difficult even before any enforcement action starts.</p>
        <h2 class="wp-block-heading" id="travel-rule">Travel Rule & Unhosted Wallets</h2>
        <p>The recast Transfer of Funds Regulation (TFR), which operates alongside MiCA, applies Travel Rule logic broadly, including lower-value transfers that many users previously treated as casual activity. CASPs now need stronger sender and recipient data, and they are expected to perform additional checks when one side of the transfer is an unhosted wallet.</p>
        <p>In day-to-day user experience, that typically means:</p>
        <ul>
          <li>Proof-of-control checks before withdrawals, such as signed messages, challenge transactions, or wallet attestations.</li>
          <li>Temporary blocks or manual reviews when transaction history suggests mixer exposure.</li>
          <li>Deposit rejections if a CASP cannot establish a satisfactory source-of-funds narrative.</li>
        </ul>
        <h2 class="wp-block-heading" id="mixers">Implications For Mixers & CoinJoin</h2>
        <p>MiCA does not ban privacy software directly, but the compliance perimeter around licensed services makes mixer-linked flows harder to use inside the regulated EU market. This is why many users see the practical impact first at exchange deposit screens, withdrawal checks, and delayed account reviews rather than in criminal statutes.</p>
        <ul>
          <li>CASPs are expected to define and enforce high-risk transaction policies that often include mixers and tumblers by default.</li>
          <li>MiCA-supervised stablecoin issuers face pressure to screen or restrict addresses linked to sanctions or obfuscation tools.</li>
          <li>Custodial privacy services with EU exposure may need full CASP-style compliance programs, which can undermine their original privacy proposition.</li>
        </ul>
        <h2 class="wp-block-heading" id="what-to-do">What Users Should Do</h2>
        <p>If you rely on EU-regulated exchanges, plan for enhanced due diligence whenever funds touch privacy infrastructure. Keep clear records of lawful source of funds, transaction purpose, and wallet ownership before you need them. Users who prepare that documentation in advance usually resolve compliance reviews faster than users who try to reconstruct history after an account is frozen.</p>
        <p>It is also worth separating strategy by objective: regulated exchange access, private peer-to-peer settlement, and long-term self-custody often require different operational paths. For context on transfer reporting and exchange behavior, see the <a href="fatf-travel-rule.html">FATF Travel Rule</a> page and the running <a href="exchange-freezes.html">exchange freezes</a> tracker.</p>
        <h2 class="wp-block-heading" id="sources">References</h2>
        <ul>
          <li><a href="https://finance.ec.europa.eu/system/files/2023-06/230605-communication-mica_en.pdf" target="_blank" rel="noopener">European Commission MiCA communication</a></li>
          <li><a href="https://www.europarl.europa.eu/doceo/document/TA-9-2023-0104_EN.html" target="_blank" rel="noopener">European Parliament adoption of MiCA and TFR (2023)</a></li>
        </ul>

<!--blog:locale:ru-->
<p>MiCA часто описывают как рамку лицензирования криптоиндустрии, но для пользователей, ориентированных на приватность, ее лучше рассматривать как часть более широкой комплаенс-системы. На практике правила MiCA для провайдеров идут вместе с пакетом AML ЕС и обновленными правилами переводов, что означает: биржи и кастодиальные сервисы обязаны собирать больше данных о пользователях и рассматривать операции, связанные с приватностью, как повышенный риск. В результате речь идет не о прямом запрете кода, а о такой рыночной структуре, где регулируемые площадки все чаще избегают активности, которую сложно объяснить регуляторам.</p>
<p>Это различие важно для тех, кто использует миксеры, инструменты CoinJoin или самостоятельное хранение. Сам софт может оставаться легальным, но доступ к фиатным рельсам, стейблкоинам и крупным биржам ЕС становится более ограниченным. Для пользователей практический вопрос смещается с «легален ли этот инструмент?» на «смогу ли я провести средства через лицензированных посредников после его использования?».</p>
<h2 class="wp-block-heading" id="vasp">Лицензирование VASP и требования к хранению средств</h2>
<p>MiCA требует, чтобы провайдеры криптоактивных услуг (CASP) получали разрешение от национального регулятора перед оказанием услуг по всему ЕС. После лицензирования от таких компаний ожидается уровень комплаенса, гораздо ближе к традиционным финансовым институтам, чем к ранним криптобиржам.</p>
<ul>
<li>Идентифицировать клиентов и бенефициаров до предоставления услуг хранения, обмена или перевода.</li>
<li>Вести аудитируемые записи по транзакциям и клиентам с многолетним сроком хранения.</li>
<li>Отклонять или приостанавливать депозиты и выводы из кошельков, которые нельзя достаточно уверенно классифицировать для целей AML-контроля.</li>
</ul>
<p>Государства-члены также могут отказать в лицензии, если бизнес-модель опирается на сервисы повышения анонимности таким образом, что это мешает эффективному AML-надзору. Проще говоря, если платформа не может показать, как она контролирует риски, связанные с миксерами и подобными инструментами, получение лицензии становится проблематичным еще до начала каких-либо мер со стороны регуляторов.</p>
<h2 class="wp-block-heading" id="travel-rule">Travel Rule и некастодиальные кошельки</h2>
<p>Обновленный Transfer of Funds Regulation (TFR), действующий вместе с MiCA, распространяет логику Travel Rule значительно шире, включая даже небольшие переводы, которые раньше воспринимались как повседневные операции. Теперь CASP должны собирать более подробные данные об отправителе и получателе, а также проводить дополнительные проверки, если одной из сторон является некастодиальный кошелек.</p>
<p>В повседневной практике это обычно означает:</p>
<ul>
<li>Проверки контроля кошелька перед выводом средств — например, подписанные сообщения, тестовые транзакции или подтверждение владения кошельком.</li>
<li>Временные блокировки или ручные проверки, если история транзакций указывает на взаимодействие с миксерами.</li>
<li>Отклонение депозитов, если CASP не может убедительно установить источник средств.</li>
</ul>
<h2 class="wp-block-heading" id="mixers">Последствия для миксеров и CoinJoin</h2>
<p>MiCA напрямую не запрещает ПО для приватности, но комплаенс-контур вокруг лицензированных сервисов делает операции, связанные с миксерами, сложнее внутри регулируемого рынка ЕС. Поэтому пользователи чаще сталкиваются с последствиями не в уголовных нормах, а на практике — при депозитах на биржи, проверках при выводе средств и затянутых проверках аккаунтов.</p>
<ul>
<li>От CASP ожидается формирование и применение политик для высокорисковых транзакций, куда по умолчанию часто попадают миксеры и тумблеры.</li>
<li>Эмитенты стейблкоинов под надзором MiCA испытывают давление в сторону скрининга или ограничения адресов, связанных с санкциями или инструментами обфускации.</li>
<li>Кастодиальные сервисы приватности с присутствием в ЕС могут быть вынуждены внедрять полноценные комплаенс-программы уровня CASP, что подрывает их исходную ценность с точки зрения приватности.</li>
</ul>
<h2 class="wp-block-heading" id="what-to-do">Что следует делать пользователям</h2>
<p>Если вы используете биржи, регулируемые в ЕС, стоит заранее закладывать возможность усиленной должной проверки всякий раз, когда средства взаимодействуют с инфраструктурой приватности. Храните понятные подтверждения источника средств, цели транзакций и владения кошельками до того, как они понадобятся. Пользователи, которые готовят такую документацию заранее, обычно проходят комплаенс-проверки быстрее, чем те, кто пытается восстановить историю уже после заморозки аккаунта.</p>
<p>Также имеет смысл разделять стратегию по целям: доступ к регулируемым биржам, приватные P2P-расчеты и долгосрочное самостоятельное хранение средств часто требуют разных операционных подходов. Для контекста по отчетности переводов и поведению бирж см. страницу <a href="fatf-travel-rule.html">FATF Travel Rule</a> и текущий трекер <a href="exchange-freezes.html">заморозок на биржах</a>.</p>
<h2 class="wp-block-heading" id="sources">Источники</h2>
<ul>
<li><a href="https://finance.ec.europa.eu/system/files/2023-06/230605-communication-mica_en.pdf" rel="noopener" target="_blank">Коммуникация Европейской комиссии MiCA</a></li>
<li><a href="https://www.europarl.europa.eu/doceo/document/TA-9-2023-0104_EN.html" rel="noopener" target="_blank">Принятие Европейским парламентом MiCA и TFR (2023)</a></li>
</ul>
