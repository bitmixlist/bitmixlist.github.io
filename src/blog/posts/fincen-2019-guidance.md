---
slug: fincen-2019-guidance
status: published
published_at: 2025-02-12T00:00:00Z
updated_at: 2026-02-19T00:00:00Z
author: NotATether
canonical_path: fincen-2019-guidance.html
body_format: html
locales:
  en:
    title: FinCEN’s 2019 Mixer Guidance
    description: "FinCEN 2019 guidance explained: why custodial mixers are treated as money transmitters, what BSA obligations apply, and how this shaped later enforcement."
  ru:
    title: Руководство FinCEN 2019 года по миксерам
    description: "В руководстве FinCEN 2019 разъясняется: почему депозитарные миксеры рассматриваются как передатчики денег, какие обязательства BSA применяются и как это влияет на последующее правоприменение."
    body: ""
---
<p>FinCEN's FIN-2019-G001, published on May 9, 2019, is one of the key documents behind U.S. enforcement against custodial crypto privacy services. The memo did not invent a new statute, but it clarified how existing Bank Secrecy Act obligations apply to "convertible virtual currency" businesses and removed much of the ambiguity that operators previously relied on.</p>
        <p>For mixer operators, the message was direct: if your service accepts and transmits value on behalf of others, you are likely in money-transmitter territory regardless of whether your frontend calls itself a wallet, a tumbler, a protocol, or an automated tool.</p>
        <h2 class="wp-block-heading" id="definitions">Who FinCEN Sees As A Money Services Business</h2>
        <p>The document introduces three roles:</p>
        <ul>
          <li><strong>Users</strong> who obtain virtual currency for their own purposes. Users are generally not MSBs.</li>
          <li><strong>Exchangers</strong> who trade CVC for fiat or other CVC on behalf of others.</li>
          <li><strong>Administrators</strong> who issue, redeem, or have the ability to withdraw CVC from circulation.</li>
        </ul>
        <p>FinCEN's test is functional, not branding-based: anyone who "accepts and transmits value" for another person can be treated as an MSB. That is why custodial mixers remain high-risk under this guidance even when they market themselves as software or privacy infrastructure.</p>
        <h2 class="wp-block-heading" id="obligations">Obligations For Mixers And Custodial Tools</h2>
        <p>The guidance reiterates that MSBs must:</p>
        <ul>
          <li>Register with FinCEN within 180 days of starting operations.</li>
          <li>Designate a compliance officer, run an AML program, and train staff.</li>
          <li>Maintain customer identification records and file Suspicious Activity Reports for $2,000+ suspicious transactions.</li>
          <li>Keep transaction logs for five years and respond to law-enforcement requests.</li>
        </ul>
        <p>The document also makes clear that fee collection, managed routing, and service advertising can undercut a "mere software provider" argument. In practical terms, once a service touches custody or transmission for users, compliance obligations attach quickly.</p>
        <h2 class="wp-block-heading" id="extraterritorial">Extraterritorial Reach</h2>
        <p>FinCEN also emphasizes extraterritorial exposure. Foreign operators can still face U.S. enforcement when they serve U.S. customers or interact with U.S.-linked banking rails. Later cases repeatedly cite this section to support "fair notice" arguments against offshore mixer operators.</p>
        <h2 class="wp-block-heading" id="impact">Lasting Impact On Enforcement</h2>
        <p>Since 2019, prosecutors and examiners have used FIN-2019-G001 as baseline notice language. It appears across major complaints and sanctions-era actions as evidence that operators were warned about classification and registration risk.</p>
        <ul>
          <li>The <a href="helix-larry-harmon.html">Helix/Grams indictment</a> (2020) and $60M FinCEN penalty.</li>
          <li>The <a href="bitcoin-fog-sterlingov.html">Bitcoin Fog conviction</a> (2024), where agents described the service as an unlicensed money transmitter.</li>
          <li>The Treasury Department&#8217;s mixer sanctions press releases in 2022–2023.</li>
        </ul>
        <p>If you run a custodial privacy service, "we only provide privacy" is no longer a workable legal shield in U.S. context. Operationally, your options narrow to full compliance or strict jurisdictional exclusion and controls designed to keep U.S. touchpoints out of scope.</p>
        <h2 class="wp-block-heading" id="sources">Primary Source</h2>
        <ul>
          <li><a href="https://www.fincen.gov/system/files/2019-05/FinCEN%20Guidance%20CVC%20FINAL%20508.pdf" target="_blank" rel="noopener">FinCEN Guidance FIN-2019-G001 (PDF)</a></li>
        </ul>
        <p>BitMixList does not operate a mixer. We publish this guide so builders and users grasp the compliance landscape before deploying or interacting with privacy services.</p>

<!--blog:locale:ru-->
<p>FIN-2019-G001 FinCEN, опубликованный 9 мая 2019 года, — один из ключевых документов, лежащих в основе правоприменения в США против кастодиальных криптосервисов для приватности. Этот меморандум не вводил нового закона, но разъяснил, как действующие требования Bank Secrecy Act применяются к бизнесам, работающим с «convertible virtual currency», и устранил значительную часть неопределенности, на которую ранее опирались операторы.</p>
<p>Для операторов миксеров посыл был прямым: если ваш сервис принимает и передает средства от имени других, вы, скорее всего, попадаете в категорию провайдеров денежных переводов — независимо от того, называет ли ваш интерфейс себя кошельком, тумблером, протоколом или автоматизированным инструментом.</p>
<h2 class="wp-block-heading">Кого FinCEN рассматривает как провайдера денежных услуг</h2>
<p>В руководстве повторно подчеркивается, что MSB обязаны:</p>
<ul>
<li>Зарегистрироваться в FinCEN в течение 180 дней с момента начала деятельности.</li>
<li>Назначить ответственного за комплаенс, внедрить AML-программу и обучать персонал.</li>
<li>Вести учет идентификационных данных клиентов и подавать отчеты о подозрительной активности (SAR) для транзакций от $2,000 и выше при наличии подозрений.</li>
<li>Хранить логи транзакций в течение пяти лет и отвечать на запросы правоохранительных органов.</li>
</ul>
<p>Документ также ясно дает понять, что взимание комиссий, управляемая маршрутизация и публичное продвижение сервиса могут ослабить аргумент о «чисто программном обеспечении». На практике, как только сервис получает контроль над средствами или участвует в их передаче для пользователей, требования комплаенса начинают применяться практически сразу.</p>
<h2 class="wp-block-heading">Обязательства для миксеров и кастодиальных сервисов</h2>
<p>В руководстве повторяется, что MSB должны:</p>
<ul>
<li>Зарегистрируйтесь в FinCEN в течение 180 дней с момента начала работы.</li>
<li>Назначьте ответственного за соблюдение требований, запустите программу ПОД и обучите персонал.</li>
<li>Ведите записи идентификации клиентов и подавайте отчеты о подозрительной деятельности в случае подозрительных транзакций на сумму более 2000 долларов США.</li>
<li>Храните журналы транзакций в течение пяти лет и отвечайте на запросы правоохранительных органов.</li>
</ul>
<p>В документе также ясно говорится, что сбор платежей, управляемая маршрутизация и реклама услуг могут опровергнуть аргумент «простого поставщика программного обеспечения». С практической точки зрения, как только услуга касается хранения или передачи для пользователей, быстро наступают обязательства по соблюдению требований.</p>
<h2 class="wp-block-heading">Экстерриториальный охват</h2>
<p>FinCEN также подчеркивает экстерриториальное применение требований. Иностранные операторы могут столкнуться с правоприменением со стороны США, если они обслуживают американских пользователей или взаимодействуют с банковской инфраструктурой, связанной с США. В последующих делах на этот раздел регулярно ссылаются для обоснования аргумента «достаточного уведомления» (fair notice) в отношении офшорных операторов миксеров.</p>
<h2 class="wp-block-heading">Долгосрочное влияние на практику применения закона</h2>
<p>С 2019 года прокуроры и проверяющие используют FIN-2019-G001 как базовое уведомление. Он фигурирует во многих ключевых обвинениях и санкционных кейсах как доказательство того, что операторы были предупреждены о рисках классификации и необходимости регистрации.</p>
<ul>
<li><a href="helix-larry-harmon.html">Обвинение по делу Helix/Grams</a> (2020) и штраф FinCEN на $60 млн.</li>
<li><a href="bitcoin-fog-sterlingov.html">Приговор по делу Bitcoin Fog</a> (2024), где агенты описывали сервис как незарегистрированного провайдера денежных переводов.</li>
<li>Пресс-релизы Казначейства по санкциям против миксеров в 2022–2023 годах.</li>
</ul>
<p>Если вы управляете кастодиальным сервисом приватности, аргумент «мы всего лишь обеспечиваем приватность» больше не работает как юридическая защита в контексте США. С операционной точки зрения выбор сужается до полной комплаенс-модели либо жесткого исключения юрисдикции США с мерами контроля, направленными на устранение любых точек соприкосновения с американской инфраструктурой.</p>
<h2 class="wp-block-heading">Первоисточник</h2>
<ul>
<li><a href="https://www.fincen.gov/system/files/2019-05/FinCEN%20Guidance%20CVC%20FINAL%20508.pdf" target="_blank" rel="noopener">Руководство FinCEN FIN-2019-G001 (PDF)</a></li>
</ul>
<p>BitMixList не управляет миксером. Мы публикуем это руководство, чтобы разработчики и пользователи понимали комплаенс-ландшафт до запуска или взаимодействия с сервисами приватности.</p>
