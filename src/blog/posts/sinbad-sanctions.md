---
slug: sinbad-sanctions
status: published
published_at: 2025-02-12T00:00:00Z
updated_at: 2026-02-19T00:00:00Z
author: NotATether
canonical_path: sinbad-sanctions.html
body_format: html
locales:
  en:
    title: Sinbad Sanctions
    description: "Sinbad sanctions case explained: launch history, Lazarus-linked flows, Dutch and Europol seizure actions, OFAC designation, and compliance checks for flagged wallets."
  ru:
    title: Санкции против Sinbad
    description: "Sinbad.io появился в конце 2022 года как высокообъемный кастодиальный миксер с акцентом на скорость, настраиваемые задержки и поддержку крупных переводов."
    body: ""
---
<p>Sinbad.io appeared in late 2022 as a high-volume custodial mixer marketed around speed, adjustable delays, and support for larger transfers. The rollout was aggressive: signature campaigns on Bitcointalk, Telegram promotion, and private outreach to OTC intermediaries who could direct flow quickly. From day one, researchers compared the service to <a href="blender-sanctions.html">Blender.io</a> because of repeated overlaps in campaign assets, wording, and infrastructure behavior.</p>
        <p>This page tracks the full enforcement arc: launch patterns, Lazarus-linked tracing claims, coordinated seizure activity, and the November 2023 OFAC designation that placed multiple Sinbad-linked identifiers on the SDN list. It is written as a practical reference for exchanges, OTC desks, wallet teams, and investigators who need a clear chronology and actionable controls.</p>
        <h2 class="wp-block-heading" id="sinbad">Sinbad Launch and Lazarus Links</h2>
        <p>Open-source and commercial tracing firms reported that Sinbad activity overlapped with clusters already associated with Lazarus-linked laundering routes. Public reports connected portions of Horizon and Ronin theft flows to Sinbad entry points and then toward exchange off-ramps, which fit a pattern already discussed in the wider <a href="crackdown.html">sanctions timeline</a>.</p>
        <p>Analysts also argued that Sinbad reused enough legacy wallet and campaign infrastructure to accelerate attribution. If those links were accurate, the reuse helped early growth but reduced operational cover, because investigators could start from known Blender-era anchors rather than building a fresh map from zero.</p>
        <h2 class="wp-block-heading" id="takedown">Europol and FIOD Seize Sinbad</h2>
        <p>Dutch authorities at <a href="https://www.fiod.nl/fiod-takes-large-crypto-currency-mixer-off-the-air/" target="_blank" rel="noopener">FIOD</a>, working with Europol and U.S. counterparts, announced action against Sinbad infrastructure and related assets. The operation followed the now-familiar cross-border model: coordinated warrants, server capture, wallet tracking, and parallel information requests to infrastructure providers and trading venues.</p>
        <p>For compliance teams, the operational lesson is straightforward. Once a mixer investigation moves into coordinated seizure mode, response times shrink dramatically and requests for logs, wallet associations, and communications history arrive in parallel. The same pattern appears in other takedown pages, including <a href="chipmixer-seizure.html">ChipMixer</a>.</p>
        <h2 class="wp-block-heading" id="ofac">OFAC&#8217;s November 2023 Designation</h2>
        <p>On November 29, 2023, U.S. Treasury <a href="https://home.treasury.gov/news/press-releases/jy1920" target="_blank" rel="noopener">sanctioned Sinbad</a> and published a detailed set of linked identifiers. The designation included not only transaction addresses but also domains, aliases, and promotional payment trails, which signaled that enforcement attention now extends beyond core mixer wallets into the surrounding business ecosystem.</p>
        <p>That scope matters for exchanges and OTC desks. It means screening programs must cover marketing and affiliate pathways, not just obvious deposit endpoints, and teams need complete evidence trails showing when alerts were triggered, blocked, escalated, and reported.</p>
        <h2 class="wp-block-heading" id="lessons">Operational Lessons for Privacy Users</h2>
        <p>Sinbad shows how sanctions pressure often unfolds in stages: early tracing, public designations, infrastructure action, then follow-on screening by exchanges and banks. Users who treated the service as a clean break from earlier sanctioned mixers still faced downstream exposure when links were later mapped and published.</p>
        <p>If you use custodial privacy services, treat outages and payout anomalies as risk signals. Keep clear records of deposit addresses, preserve proof of funds origin, and have a fallback route ready before a sanction event lands. In practice, preparation determines whether a review is resolved quickly or becomes a long account freeze.</p>
        <h2 class="wp-block-heading" id="compliance">Compliance Checklist</h2>
        <p>Compliance desks, OTC brokers, and even active peer-to-peer traders need repeatable routines, not generic policy language. Treat this checklist as a working control set and revisit it after each sanctions update or infrastructure incident.</p>
        <ul>
          <li>Screen every UTXO with tools like the <a href="aml-check.html">BitMixList AML Checker</a> before sending it to a custodial venue, and archive the screenshots or CSV hits for future audits.</li>
          <li>Document how you block SDN-listed addresses if you operate infrastructure or OTC services, including which wallets or nodes auto-reject flagged inputs and where manual overrides live.</li>
          <li>Monitor marketing partners and affiliate programs. OFAC noted that Sinbad&#8217;s forum promotions tied promoter wallets to sanctioned addresses, so track payouts, public handles, and domains used for campaigns.</li>
          <li>Align incident response plans with hardware inventories. If authorities seize a data center, know which wallets, CRM systems, and email gateways were affected so you can notify customers in hours, not days.</li>
        </ul>
        <p>The Sinbad case reinforces a hard reality: when a mixer is linked to nation-state laundering narratives, enforcement escalation is fast and cross-jurisdictional. If you rely on custodial privacy tools, plan for abrupt takedowns and apply OFAC controls immediately if you are a U.S. person or U.S.-exposed business.</p>
        <h2 class="wp-block-heading" id="references">References</h2>
        <p>The primary sources below support the chronology and are useful for due diligence, incident reporting, and internal compliance training.</p>
        <ul>
          <li><a href="https://home.treasury.gov/news/press-releases/jy0768" target="_blank" rel="noopener">U.S. Treasury sanctions Blender.io (2022)</a></li>
          <li><a href="https://www.fiod.nl/fiod-takes-large-crypto-currency-mixer-off-the-air/" target="_blank" rel="noopener">FIOD press release on Sinbad seizure (2023)</a></li>
          <li><a href="https://home.treasury.gov/news/press-releases/jy1920" target="_blank" rel="noopener">U.S. Treasury sanctions Sinbad.io (2023)</a></li>
          <li><a href="https://www.reuters.com/technology/us-sanctions-virtual-currency-mixer-sinbad-2023-11-29/" target="_blank" rel="noopener">Reuters coverage of Sinbad sanctions</a></li>
          <li><a href="https://www.infosecurity-magazine.com/news/crypto-mixer-launders-100m-north/" target="_blank" rel="noopener">Infosecurity: Crypto mixer launders $100M for North Korea</a></li>
        </ul>
        <p>Once again: do not use mixers for illicit activity, and never interact with addresses that appear on sanctions lists.</p>

<!--blog:locale:ru-->
<p>Sinbad.io появился в конце 2022 года как высокообъемный кастодиальный миксер с акцентом на скорость, настраиваемые задержки и поддержку крупных переводов. Запуск был агрессивным: подписные кампании на Bitcointalk, продвижение в Telegram и прямое взаимодействие с OTC-посредниками, способными быстро направлять потоки. С самого начала исследователи сравнивали сервис с <a href="blender-sanctions.html">Blender.io</a> из-за повторяющихся совпадений в маркетинговых материалах, формулировках и поведении инфраструктуры.</p>
<p>Эта страница отслеживает полный цикл правоприменительных действий: паттерны запуска, заявления о связях с Lazarus, координированные изъятия и санкции OFAC в ноябре 2023 года, в рамках которых ряд идентификаторов, связанных с Sinbad, был включен в список SDN. Материал подготовлен как практическое руководство для бирж, OTC-десков, команд кошельков и следственных органов, которым нужна четкая хронология и применимые меры контроля.</p>
<h2 class="wp-block-heading" id="sinbad">Запуск Sinbad и связи с Lazarus</h2>
<p>Компании, занимающиеся блокчейн-аналитикой (как с открытым исходным кодом, так и коммерческие), сообщали, что активность Sinbad пересекалась с кластерами, ранее связанными с маршрутами отмывания, приписываемыми группе Lazarus. В публичных отчетах часть потоков из взломов Horizon и Ronin связывалась с входными точками Sinbad, а затем — с выходами на биржи, что соответствует уже описанным паттернам в более широкой <a href="crackdown.html">хронологии санкций</a>.</p>
<p>Аналитики также утверждали, что Sinbad повторно использовал элементы старой инфраструктуры кошельков и маркетинговых кампаний, что ускорило атрибуцию. Если эти связи верны, такое повторное использование помогло быстрому росту, но одновременно снизило уровень операционного прикрытия, поскольку следователи могли опираться на известные якоря эпохи Blender, а не строить анализ с нуля.</p>
<h2 class="wp-block-heading" id="takedown">Europol и FIOD конфискуют Sinbad</h2>
<p>Нидерландские власти из <a href="https://www.fiod.nl/fiod-takes-large-crypto-currency-mixer-off-the-air/" rel="noopener" target="_blank">FIOD</a>, действуя совместно с Europol и партнерами из США, объявили о мерах против инфраструктуры Sinbad и связанных с ней активов. Операция следовала уже знакомой трансграничной модели: координированные ордера, изъятие серверов, отслеживание кошельков и параллельные запросы информации к провайдерам инфраструктуры и торговым площадкам.</p>
<p>Для комплаенс-команд практический вывод очевиден. Как только расследование миксера переходит в фазу координированных изъятий, время на реакцию резко сокращается, а запросы на логи, связи кошельков и историю коммуникаций поступают одновременно. Тот же паттерн наблюдается и в других кейсах ликвидаций, включая <a href="chipmixer-seizure.html">ChipMixer</a>.</p>
<h2 class="wp-block-heading" id="ofac">Объявление OFAC о санкциях в ноябре 2023 года</h2>
<p>29 ноября 2023 года U.S. Treasury <a href="https://home.treasury.gov/news/press-releases/jy1920" rel="noopener" target="_blank">ввело санкции против Sinbad</a> и опубликовало подробный набор связанных идентификаторов. В перечень вошли не только адреса транзакций, но и домены, алиасы и следы маркетинговых выплат, что показывает: внимание регуляторов распространяется не только на кошельки миксера, но и на окружающую бизнес-инфраструктуру.</p>
<p>Этот масштаб важен для бирж и OTC-десков. Он означает, что системы скрининга должны охватывать не только очевидные адреса для депозитов, но и маркетинговые и партнерские каналы, а команды должны иметь полную доказательную цепочку — когда сработали алерты, как они были обработаны, заблокированы, эскалированы и зафиксированы.</p>
<h2 class="wp-block-heading" id="lessons">Практические выводы для пользователей приватности</h2>
<p>Sinbad показывает, как санкционное давление обычно развивается поэтапно: сначала отслеживание потоков, затем публичные санкционные списки, после этого действия по инфраструктуре и уже затем — усиленный скрининг со стороны бирж и банков. Пользователи, которые воспринимали сервис как «чистый разрыв» с ранее санкционированными миксерами, все равно сталкивались с рисками позже, когда связи были установлены и опубликованы.</p>
<p>Если вы используете кастодиальные инструменты приватности, рассматривайте сбои и аномалии выплат как сигналы риска. Ведите точный учет адресов депозитов, сохраняйте подтверждения происхождения средств и держите запасной маршрут до того, как наступит санкционное событие. На практике именно подготовка определяет, будет ли проверка быстрой или превратится в длительную заморозку аккаунта.</p>
<h2 class="wp-block-heading" id="compliance">Контрольный список соответствия</h2>
<p>Комплаенс-командам, OTC-брокерам и даже активным P2P-трейдерам нужны повторяемые процедуры, а не абстрактные формулировки. Рассматривайте этот чек-лист как рабочий набор контроля и пересматривайте его после каждого обновления санкций или инцидента с инфраструктурой.</p>
<ul>
<li>Проверяйте каждый UTXO с помощью инструментов вроде <a href="aml-check.html">AML-чекера BitMixList</a> перед отправкой на кастодиальную площадку и сохраняйте скриншоты или отчеты CSV для будущих проверок.</li>
<li>Документируйте, как вы блокируете адреса из списка SDN, если управляете инфраструктурой или OTC-сервисами, включая то, какие кошельки или ноды автоматически отклоняют подозрительные входы и где предусмотрены ручные исключения.</li>
<li>Отслеживайте маркетинговых партнеров и аффилиатные программы. OFAC указало, что продвижение Sinbad на форумах связывало кошельки промоутеров с санкционированными адресами, поэтому важно фиксировать выплаты, публичные аккаунты и используемые домены.</li>
<li>Синхронизируйте планы реагирования на инциденты с перечнем оборудования. Если дата-центр изымается, вы должны знать, какие кошельки, системы CRM и почтовые шлюзы затронуты, чтобы уведомить пользователей в течение часов, а не дней.</li>
</ul>
<p>Кейс Sinbad подчеркивает жесткую реальность: когда миксер связывают с нарративами о деятельности на уровне государств, эскалация происходит быстро и сразу в нескольких юрисдикциях. Если вы используете кастодиальные инструменты приватности, закладывайте сценарий резких закрытий и применяйте требования OFAC немедленно, если вы подпадаете под юрисдикцию США или связаны с ней.</p>
<h2 class="wp-block-heading" id="references">Источники</h2>
<p>Основные источники ниже подтверждают хронологию и полезны для должной проверки, отчетности по инцидентам и внутреннего обучения комплаенс-команд.</p>
<ul>
<li><a href="https://home.treasury.gov/news/press-releases/jy0768" rel="noopener" target="_blank">U.S. Treasury — санкции против Blender.io (2022)</a></li>
<li><a href="https://www.fiod.nl/fiod-takes-large-crypto-currency-mixer-off-the-air/" rel="noopener" target="_blank">FIOD — пресс-релиз об изъятии Sinbad (2023)</a></li>
<li><a href="https://home.treasury.gov/news/press-releases/jy1920" rel="noopener" target="_blank">U.S. Treasury — санкции против Sinbad.io (2023)</a></li>
<li><a href="https://www.reuters.com/technology/us-sanctions-virtual-currency-mixer-sinbad-2023-11-29/" rel="noopener" target="_blank">Reuters — освещение санкций против Sinbad</a></li>
<li><a href="https://www.infosecurity-magazine.com/news/crypto-mixer-launders-100m-north/" rel="noopener" target="_blank">Infosecurity — материал о миксере, отмывшем $100 млн для Северной Кореи</a></li>
</ul>
<p>И еще раз: не используйте миксеры для незаконной деятельности и не взаимодействуйте с адресами, находящимися в санкционных списках.</p>
