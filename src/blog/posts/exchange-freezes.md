---
# HARDWIRED: legacy root HTML is source of truth; not a blog post
slug: exchange-freezes
status: draft
published_at: 2025-02-12T00:00:00Z
updated_at: 2026-02-19T00:00:00Z
author: NotATether
canonical_path: exchange-freezes.html
body_format: html
locales:
  en:
    title: Exchange Freezes After Mixing
    description: "Exchange freeze guide: why CoinJoin and mixer-linked deposits trigger AML reviews, what compliance teams check, and how to reduce account lock risk."
  ru:
    title: Заморозки на биржах после миксинга
    description: "Руководство по заморозке биржи: почему CoinJoin и депозиты, связанные с миксером, вызывают проверки AML, что проверяют группы обеспечения соответствия и как снизить риск блокировки учетной записи."
    body: ""
---
<p>Account freezes after mixing are usually not random mistakes; they are a predictable side effect of how centralized exchanges run compliance. Once funds touch CoinJoin rounds, known mixer clusters, or sanctions-adjacent flows, automated risk systems can escalate the account for manual review. For users, that often feels sudden because the trigger appears only when a deposit is made, but internally the exchange is following a pre-defined AML workflow.</p>
        <p>This page explains why those reviews happen, what signals compliance teams actually look at, and how to lower the chance that your funds get stuck in a long questionnaire cycle.</p>
        <h2 class="wp-block-heading" id="aml">Why Exchanges Flag CoinJoin Outputs</h2>
        <ul>
          <li><strong>Regulatory expectations:</strong> The <a href="fincen-2019-guidance.html">FinCEN 2019 guidance</a> explicitly classifies mixers as money transmitters, so exchanges must document mixed deposits.</li>
          <li><strong>Analytics alerts:</strong> Chainalysis and similar vendors tag CoinJoin outputs and mixers as &#8220;high risk.&#8221; Their <a href="https://www.chainalysis.com/blog/crypto-mixer-criminal-volume-2022/" target="_blank" rel="noopener">2022 report</a> noted record highs in CoinJoin traffic, prompting exchanges to tighten monitoring.</li>
          <li><strong>Sanctions enforcement:</strong> Interacting with SDN-listed addresses (Blender, Sinbad, Tornado Cash) obliges exchanges to block or file SARs immediately.</li>
        </ul>
        <h2 class="wp-block-heading" id="examples">Examples From Recent Years</h2>
        <p>Users have repeatedly reported freezes after sending post-mix outputs to major venues such as Binance, OKX, and Coinbase. Public statements over the last few years confirm that exchanges sometimes pause these flows for manual review, especially after analytics vendors refresh clustering models. The exact threshold varies by venue, but the pattern is consistent: once a deposit matches a flagged cluster, normal processing can stop immediately.</p>
        <h2 class="wp-block-heading" id="mitigation">How To Reduce The Risk</h2>
        <ol>
          <li>Allow time between mixing and depositing. Waiting a few days and making intermediate, non-KYC spends reduces the chance of deterministic linkage.</li>
          <li>Maintain audit trails. If an exchange questions you, provide transaction IDs and proof that the source of funds is legitimate.</li>
          <li>Pre-check your UTXOs with the <a href="aml-check.html">BitMixList AML Checker</a> so you know how a compliance desk is likely to score the deposit before you send it.</li>
          <li>Understand the terms of service. Some exchanges prohibit using privacy tools entirely; if you break that rule, they can hold your balance indefinitely.</li>
        </ol>
        <p>The safest operational rule is separation: do not mix coins you plan to redeposit into centralized exchanges on a short timeline. If exchange liquidity is your end goal, plan provenance and route structure first instead of improvising after funds are already flagged.</p>
        <h2 class="wp-block-heading" id="references">References</h2>
        <ul>
          <li><a href="https://www.chainalysis.com/blog/crypto-mixer-criminal-volume-2022/" target="_blank" rel="noopener">Chainalysis: Mixer Usage Reaches All-Time Highs (2022)</a></li>
          <li><a href="https://home.treasury.gov/news/press-releases/jy0768" target="_blank" rel="noopener">OFAC on Blender.io sanctions (2022)</a></li>
        </ul>
        <p>Only mix lawfully sourced funds, and assume centralized venues may request extra documentation even when your intent is legitimate privacy.</p>

<!--blog:locale:ru-->
<p>Заморозка аккаунтов после миксинга — это, как правило, не случайные ошибки, а предсказуемое следствие того, как централизованные биржи выстраивают комплаенс. Как только средства проходят через раунды CoinJoin, известные кластеры миксеров или потоки, связанные с санкционными рисками, автоматические системы оценки риска могут перевести аккаунт на ручную проверку. Для пользователей это часто выглядит внезапно, потому что триггер проявляется только при депозите, но внутри биржи запускается заранее заданный AML-процесс.</p>
<p>На этой странице объясняется, почему происходят такие проверки, на какие сигналы на самом деле обращают внимание комплаенс-команды и как снизить вероятность того, что ваши средства застрянут в затяжном цикле проверок и анкет.</p>
<h2 class="wp-block-heading">Почему биржи помечают выходы CoinJoin</h2>
<ul>
<li>Регуляторные требования: <a href="fincen-2019-guidance.html">руководство FinCEN 2019 года</a> прямо классифицирует миксеры как операторов перевода средств, поэтому биржи обязаны документировать депозиты после миксинга.</li>
<li>Сигналы от аналитики: Chainalysis и аналогичные провайдеры помечают выходы CoinJoin и миксеры как «высокий риск». В их отчете за 2022 год отмечены рекордные объемы использования CoinJoin, что подтолкнуло биржи ужесточить мониторинг.</li>
<li>Санкционное давление: взаимодействие с адресами из списка SDN (Blender, Sinbad, Tornado Cash) обязывает биржи немедленно блокировать операции или подавать отчеты SAR.</li>
</ul>
<h2 class="wp-block-heading">Примеры последних лет</h2>
<p>Пользователи неоднократно сообщали о заморозках после отправки средств после миксинга на крупные площадки, такие как Binance, OKX и Coinbase. Публичные заявления последних лет подтверждают, что биржи иногда приостанавливают такие операции для ручной проверки, особенно после обновления моделей кластеризации у провайдеров аналитики. Конкретные пороги различаются в зависимости от площадки, но общий паттерн остается неизменным: как только депозит совпадает с помеченным кластером, обычная обработка может быть немедленно остановлена.</p>
<h2 class="wp-block-heading">Как снизить риск</h2>
<ol>
<li>Делайте паузу между миксингом и депозитом. Ожидание в несколько дней и промежуточные транзакции без KYC снижают вероятность прямой связки.</li>
<li>Ведите учет операций. Если биржа задаст вопросы, предоставьте ID транзакций и доказательства легального происхождения средств.</li>
<li>Проверяйте свои UTXO заранее через <a href="aml-check.html">AML-чекер BitMixList</a>, чтобы понимать, как комплаенс-отдел, скорее всего, оценит депозит до отправки.</li>
<li>Изучайте условия использования. Некоторые биржи полностью запрещают использование инструментов приватности; при нарушении этого правила они могут заморозить средства на неопределенный срок.</li>
<li>Самое безопасное операционное правило — разделение: не миксуйте монеты, которые планируете в ближайшее время заводить на централизованные биржи.</li>
<li>Если конечная цель — ликвидность на бирже, заранее продумайте происхождение средств и маршрут, а не импровизируйте после того, как средства уже были помечены.</li>
</ol>
<h2 class="wp-block-heading">Источники</h2>
<ul>
<li><a href="https://www.chainalysis.com/blog/crypto-mixer-criminal-volume-2022/" target="_blank" rel="noopener">Chainalysis: использование миксеров достигает исторического максимума (2022)</a></li>
<li><a href="https://home.treasury.gov/news/press-releases/jy0768" target="_blank" rel="noopener">OFAC о санкциях против Blender.io (2022)</a></li>
</ul>
<p>Используйте для миксинга только законно полученные средства и учитывайте, что централизованные площадки могут запрашивать дополнительную документацию, даже если ваша цель — легитимная приватность.</p>
