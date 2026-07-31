---
slug: fatf-travel-rule
status: published
published_at: 2025-02-12T00:00:00Z
updated_at: 2026-02-19T00:00:00Z
author: NotATether
canonical_path: fatf-travel-rule.html
body_format: html
locales:
  en:
    title: FATF Travel Rule Guidance
    description: "FATF Travel Rule explainer: what VASPs must collect, how exchange screening works, and why privacy users face extra checks when funds touch regulated rails."
  ru:
    title: Руководство FATF (Financial Action Task Force) по Travel Rule
    description: "Объяснение Travel Rule FATF: что должны собирать VASP, как работает биржевая проверка и почему пользователи, ориентированные на приватность, сталкиваются с дополнительным контролем при взаимодействии средств с регулируемыми VASP."
    body: ""
---
<p>The FATF Travel Rule is one of the main reasons exchange-facing crypto flows now trigger heavier compliance checks. In June 2019, FATF extended Recommendation 16 to virtual asset service providers (VASPs), which means exchanges and custodial providers must exchange sender and recipient identity data for qualifying transfers. In practice, this turns many crypto transfers into messaging events between compliance systems, not just blockchain broadcasts.</p>
        <p>For privacy users, the practical distinction is simple: self-custody by itself is not the same thing as a reporting obligation, but the moment funds interact with regulated VASP rails, identity and screening requirements usually apply.</p>
        <h2 class="wp-block-heading" id="requirements">What VASPs Must Collect</h2>
        <ul>
          <li>Originator name, account (wallet) identifier, and physical address or national ID.</li>
          <li>Beneficiary name and wallet identifier.</li>
          <li>Screening against sanctions/terror financing lists before executing the transfer.</li>
        </ul>
        <p>When both sides are VASPs, this information must be exchanged securely and fast enough to satisfy transaction monitoring requirements. Most operators now handle this through dedicated Travel Rule APIs and vendor middleware.</p>
        <h2 class="wp-block-heading" id="impact">Impact On Mixers & Privacy Wallets</h2>
        <ul>
          <li>Exchanges now treat deposits from mixers or CoinJoin coordinators as &#8220;unhosted wallet&#8221; transfers, triggering enhanced due diligence.</li>
          <li>Some jurisdictions (e.g., Singapore, Switzerland) require VASPs to confirm the owner of the destination wallet before releasing funds.</li>
          <li>Chain-analysis vendors built Travel-Rule compliance modules that flag mixer-linked UTXOs automatically.</li>
        </ul>
        <h2 class="wp-block-heading" id="global">Implementation Status</h2>
        <p>Implementation is now widespread, but not identical across countries. Many jurisdictions adopted FATF-aligned requirements through local AML updates, while threshold details, verification methods, and enforcement style still vary by regulator. In the U.S., existing MSB frameworks carried much of the early burden, with additional proposals such as the dedicated mixing-service reporting track covered in <a href="fincen-mixing-rulemaking.html">our FinCEN rulemaking page</a>.</p>
        <h2 class="wp-block-heading" id="takeaways">Takeaways For Privacy Users</h2>
        <p>Assume any transfer touching a regulated exchange will pass through Travel Rule screening and may trigger enhanced due diligence. Keep lawful source-of-funds records, maintain clear wallet labels, and avoid sending freshly mixed outputs directly into high-surveillance VASP flows when alternatives exist.</p>
        <h2 class="wp-block-heading" id="sources">References</h2>
        <ul>
          <li><a href="https://www.fatf-gafi.org/en/publications/Fatfrecommendations/Guidance-rba-virtual-assets-vasps.html" target="_blank" rel="noopener">FATF Guidance for a Risk-Based Approach to Virtual Assets and VASPs (2019)</a></li>
          <li><a href="https://www.mas.gov.sg/regulation/explainers/payment-services-act" target="_blank" rel="noopener">MAS explainer on Singapore’s implementation</a></li>
        </ul>

<!--blog:locale:ru-->
<p>Правило Travel Rule FATF — одна из ключевых причин, почему криптопотоки, взаимодействующие с биржами, теперь проходят более жесткие комплаенс-проверки. В июне 2019 года FATF распространила Recommendation 16 на VASP (Virtual Asset Service Providers), что означает: биржи и кастодиальные провайдеры обязаны обмениваться данными об отправителе и получателе для подпадающих под правило переводов. На практике это превращает многие криптотранзакции в события обмена сообщениями между комплаенс-системами, а не просто в блокчейн-трансляции.</p>
<p>Для пользователей, ориентированных на приватность, практическое различие простое: самостоятельное хранение само по себе не равно обязательству по отчетности, но в момент, когда средства взаимодействуют с регулируемыми VASP, обычно включаются требования по идентификации и скринингу.</p>
<h2 class="wp-block-heading">Что VASP обязаны получать и передавать</h2>
<ul>
<li>Имя отправителя (originator), идентификатор аккаунта (кошелька) и физический адрес или национальный ID.</li>
<li>Имя получателя (beneficiary) и идентификатор кошелька.</li>
<li>Проверка по санкционным спискам / спискам финансирования терроризма до выполнения перевода.</li>
<li>Когда обе стороны — VASP, эта информация должна передаваться безопасно и достаточно быстро, чтобы соответствовать требованиям мониторинга транзакций. Большинство операторов сейчас реализуют это через специализированные Travel Rule API и промежуточное ПО поставщиков.</li>
</ul>
<h2 class="wp-block-heading">Влияние на миксеры и приватные кошельки</h2>
<ul>
<li>Биржи теперь рассматривают депозиты из миксеров или CoinJoin-координаторов как переводы с некастодиальных кошельков, что запускает усиленную должную проверку.</li>
<li>В некоторых юрисдикциях (например, Сингапур, Швейцария) VASP обязаны подтверждать владельца кошелька назначения перед отправкой средств.</li>
<li>Поставщики ончейн-аналитики внедрили модули соблюдения Travel Rule, которые автоматически помечают UTXO, связанные с миксерами.</li>
</ul>
<h2 class="wp-block-heading">Статус имплементации</h2>
<p>Имплементация сейчас широко распространена, но не одинакова в разных странах. Многие юрисдикции внедрили требования, согласованные с FATF, через локальные обновления AML, однако пороговые значения, методы верификации и стиль правоприменения по-прежнему различаются в зависимости от регулятора. В США существующие рамки MSB взяли на себя значительную часть ранней нагрузки, при этом дополнительные инициативы — такие как отдельный трек отчетности для сервисов микширования — рассматриваются на нашей странице о <a href="fincen-mixing-rulemaking.html">нормотворчестве FinCEN</a>.</p>
<h2 class="wp-block-heading">Ключевые выводы для пользователей, ориентированных на приватность</h2>
<p>Исходите из того, что любой перевод, взаимодействующий с регулируемой биржей, пройдет проверку по Travel Rule и может запустить усиленную проверку. Храните подтверждения легального происхождения средств, поддерживайте понятную маркировку кошельков и по возможности избегайте отправки недавно смешанных выходов напрямую в высоконаблюдаемые VASP-потоки, если есть альтернативы.</p>
<h2 class="wp-block-heading">Источники</h2>
<ul>
<li><a href="https://www.fatf-gafi.org/en/publications/Fatfrecommendations/Guidance-rba-virtual-assets-vasps.html" target="_blank" rel="noopener">Руководство FATF по риск-ориентированному подходу к виртуальным активам и VASP (2019)</a></li>
<li><a href="https://www.mas.gov.sg/regulation/explainers/payment-services-act" target="_blank" rel="noopener">Разъяснение MAS по имплементации в Сингапуре</a></li>
</ul>
