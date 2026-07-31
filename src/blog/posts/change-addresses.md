---
slug: change-addresses
status: published
published_at: 2023-12-05T04:40:14Z
updated_at: 2026-02-19T00:00:00Z
author: NotATether
canonical_path: change-addresses.html
body_format: html
locales:
  en:
    title: Bitcoin Change Addresses Explained
    description: "Bitcoin change address guide: how UTXO wallets create change outputs, why they matter for privacy, and how to avoid common wallet hygiene mistakes."
  ru:
    title: "Адреса сдачи в Биткоине: как это работает"
    description: "Руководство по адресам сдачи в Биткоине: как кошельки UTXO создают выходы сдачи, почему они важны для приватности и как избежать распространенных ошибок гигиены кошелька."
    body: ""
---
<p>Bitcoin wallets rely on change addresses because the network tracks value as discrete UTXOs, not as a single running balance. Whenever you spend less than the value of the UTXOs you selected, the remainder has to go somewhere—and best practice says it should land on a brand-new address you control. This page breaks down why change outputs exist, how modern wallets automate them, and how to audit that they are protecting your privacy rather than leaking it.</p>
        <h2 class="wp-block-heading" id="utxo-basics">Why Bitcoin Needs Change Addresses</h2>
        <p>Bitcoin’s UTXO model behaves like cash. Imagine paying for a £7 purchase with a £20 note: the cashier hands £13 back as change. On-chain, you might hold a 1.2 BTC UTXO but only want to send 0.7 BTC. Your wallet must spend the full 1.2 BTC input, create a 0.7 BTC output for the recipient, and send the ~0.5 BTC remainder (minus fees) back to you. To keep that return path private, the wallet generates a fresh “change address” inside your HD seed and directs the leftover sats there. The <a href="https://en.bitcoin.it/wiki/Change" target="_blank" rel="noopener">Bitcoin Wiki article on change</a> documents this behavior dating back to the earliest clients.</p>
        <p>Because every UTXO must be spent in full, change addresses are not optional. Without them you would either be forced to pay exact denominations or leak obvious self-transfers back to the address you originally used, which would violate the “never reuse addresses” rule covered in our <a href="address-reuse.html">address reuse explainer</a>.</p>
        <h2 class="wp-block-heading" id="anatomy">Anatomy of a Typical Transaction</h2>
        <p>A standard spend contains:</p>
        <ol>
          <li><strong>Inputs:</strong> One or more UTXOs you control (e.g., 1.2 BTC + 0.15 BTC).</li>
          <li><strong>Outputs to recipients:</strong> The amount you intend to pay (e.g., 0.7 BTC).</li>
          <li><strong>Change output:</strong> A new address from your wallet’s internal change branch that receives the leftover funds (e.g., 0.649 BTC after fees).</li>
          <li><strong>Fee:</strong> Calculated as inputs minus outputs; often deducted from the change output.</li>
        </ol>
        <p>Wallets like Sparrow, Specter, Electrum, and hardware suites from Ledger or Trezor use BIP32/BIP44 derivation paths to separate “receive” addresses from “change” addresses. You can view these branches inside advanced settings to confirm which outputs belong to you. Block explorers that highlight “change” are simply guessing; only your wallet’s seed truly proves ownership.</p>
        <h2 class="wp-block-heading" id="privacy">Privacy &amp; Hygiene Benefits</h2>
        <p>Change addresses support the same privacy goals outlined in the <a href="mixer-privacy.html">Mixer Privacy guide</a>:</p>
        <ul>
          <li><strong>Break deterministic ties.</strong> Sending change to fresh addresses prevents outsiders from assuming that multiple receipts belong to the same user.</li>
          <li><strong>Limit exposure of public keys.</strong> Each new change address stays hidden until you spend it, reducing the window for signature-based attacks.</li>
          <li><strong>Enable advanced workflows.</strong> Coordinated <a href="enhanced-coinjoins.html">CoinJoin rounds</a>, payjoins, or <a href="stealth-addresses.html">stealth receipts</a> all rely on wallets that correctly segregate change.</li>
        </ul>
        <p>When wallets reused a single “return” address (common in 2012-era software), change became the easiest way for chain-analysts to link inputs and outputs. Modern HD wallets have solved this, but you still need to double-check that change detection is turned on and that you are not manually overriding it when crafting raw transactions.</p>
        <h2 class="wp-block-heading" id="ops-2026">Operational Tips for 2026</h2>
        <ul>
          <li><strong>Let wallets pick change addresses automatically.</strong> Avoid copy/pasting an address you recognize; the wallet’s internal branch is safer.</li>
          <li><strong>Label UTXOs.</strong> Tools like Sparrow and Specter let you tag change outputs so you know when they originate from exchanges, mixers, or Lightning channel closes.</li>
          <li><strong>Mind coin selection.</strong> Combine the smallest number of inputs possible to reduce the size of change and eliminate unnecessary linkages.</li>
          <li><strong>Use reusable identifiers wisely.</strong> If you need a static way to collect funds, switch to BIP47 PayNyms, Silent Payments, or Lightning rather than disabling change. See <a href="stealth-addresses.html">Stealth Addresses</a> for details.</li>
          <li><strong>Audit merchant stacks.</strong> BTCPay, SatSale, and self-hosted processors should route change to segregated treasury accounts rather than deposit addresses customers see.</li>
        </ul>
        <p>Wallet vendors publish guides on inspecting change behavior—see resources from <a href="https://support.blockchain.com/hc/en-us/articles/4417082392724-What-are-change-addresses-and-how-do-they-work" target="_blank" rel="noopener">Blockchain.com</a>, <a href="https://support.exodus.com/support/en/articles/8598675-what-are-change-addresses" target="_blank" rel="noopener">Exodus</a>, or <a href="https://support.bitpay.com/hc/en-us/articles/115003063823-What-is-a-Bitcoin-change-address" target="_blank" rel="noopener">BitPay</a> to verify your preferred wallet’s defaults.</p>
        <h2 class="wp-block-heading" id="faq">Common Questions</h2>
        <p><strong>Do I ever need to manage change addresses manually?</strong> No. Quality wallets derive them automatically. Advanced users crafting PSBTs can specify the change path, but that is optional.</p>
        <p><strong>Why do explorers show an “extra” output in my transaction?</strong> That extra output is your change. Explorers cannot be 100% sure it belongs to you, but your wallet knows because it generated the destination key.</p>
        <p><strong>Can I send change back to the original address?</strong> Avoid it. Doing so reintroduces the privacy leaks explained on the <a href="address-reuse.html">address reuse page</a> and bypasses the protections HD wallets provide.</p>
        <p><strong>Do Lightning or account-based chains have change addresses?</strong> Lightning channels still settle on-chain using UTXOs, so change concepts apply during opens/closes. Account-based chains like Ethereum track balances differently, so they do not need change outputs.</p>
        <p>Change addresses are a silent workhorse of every Bitcoin wallet. Let the wallet rotate them, monitor your UTXOs, and pair them with disciplined spending so your leftover sats stay just as private as the coins you send to others.</p>

<!--blog:locale:ru-->
<p>Биткоин-кошельки используют адреса сдачи, потому что сеть отслеживает средства в виде отдельных UTXO, а не как единый текущий баланс. Каждый раз, когда вы тратите сумму меньше стоимости выбранных UTXO, остаток должен куда-то отправиться — и лучшая практика предполагает, что он поступает на новый адрес, который вы контролируете. Эта страница объясняет, почему появляются выходы сдачи, как современные кошельки автоматически управляют ими и как проверить, действительно ли они защищают вашу приватность, а не создают дополнительные утечки информации.</p>
<h2 class="wp-block-heading">Почему Биткоину нужны адреса сдачи</h2>
<p>Модель UTXO в Биткоине работает похоже на наличные деньги. Представьте, что вы платите за покупку на £7 купюрой £20: кассир возвращает £13 сдачи. В блокчейне ситуация похожая. У вас может быть UTXO на 1.2 BTC, но вы хотите отправить только 0.7 BTC. В этом случае ваш кошелёк должен потратить весь вход 1.2 BTC, создать выход на 0.7 BTC для получателя и отправить оставшиеся ~0.5 BTC (за вычетом комиссии) обратно вам. Чтобы сохранить приватность этого возврата, кошелёк генерирует новый «адрес сдачи» внутри вашей сид-фразы HD и направляет оставшиеся сатоши туда. <a href="https://en.bitcoin.it/wiki/Change" target="_blank" rel="noopener">Статья Bitcoin Wiki об адресах сдачи</a> описывает это поведение ещё со времён самых ранних клиентов.</p>
<p>Поскольку каждый UTXO должен расходоваться полностью, адреса сдачи не являются опциональными. Без них вам пришлось бы либо платить точными номиналами, либо раскрывать очевидные самопереводы обратно на тот же адрес, который вы использовали ранее, что нарушало бы правило «никогда не переиспользовать адреса», описанное в нашем <a href="address-reuse.html">материале о повторном использовании адресов</a>.</p>
<h2 class="wp-block-heading">Анатомия типичной транзакции</h2>
<h2 class="wp-block-heading">Стандартная транзакция расходования содержит:</h2>
<ol>
<li>Входы: один или несколько UTXO, которые вы контролируете (например, 1.2 BTC + 0.15 BTC).</li>
<li>Выходы получателям: сумма, которую вы собираетесь отправить (например, 0.7 BTC).</li>
<li>Выход сдачи: новый адрес из внутренней ветки сдачи вашего кошелька, на который отправляются оставшиеся средства (например, 0.649 BTC после вычета комиссии).</li>
<li>Комиссия: рассчитывается как разница между входами и выходами; часто вычитается из выхода сдачи.</li>
</ol>
<p>Кошельки вроде Sparrow, Specter, Electrum, а также аппаратные решения от Ledger или Trezor используют пути деривации BIP32/BIP44, чтобы разделять адреса «получения» и адреса «сдачи». Эти ветки можно увидеть в расширенных настройках, чтобы подтвердить, какие выходы принадлежат именно вам. Блокчейн-обозреватели, которые помечают «сдачу», лишь делают предположение; только сид вашего кошелька действительно подтверждает владение адресом.</p>
<h2 class="wp-block-heading">Преимущества приватности и операционной гигиены</h2>
<p>Адреса сдачи поддерживают те же цели приватности, которые описаны в <a href="mixer-privacy.html">руководстве по приватности миксеров</a>:</p>
<ul>
<li>Разрыв детерминированных связей. Отправка сдачи на новые адреса не позволяет внешним наблюдателям легко предположить, что несколько получений средств принадлежат одному и тому же пользователю.</li>
<li>Ограничение раскрытия публичных ключей. Каждый новый адрес сдачи остаётся скрытым до тех пор, пока вы не потратите средства с него, что уменьшает окно для атак, основанных на анализе подписей.</li>
<li>Поддержка продвинутых сценариев. Координированные <a href="enhanced-coinjoins.html">раунды CoinJoin</a>, PayJoin или скрытые способы получения средств зависят от кошельков, которые правильно отделяют адреса сдачи.</li>
</ul>
<p>Когда кошельки использовали один и тот же «возвратный» адрес (что было распространено в программном обеспечении примерно в 2012 году), сдача становилась самым простым способом для аналитиков блокчейна связать входы и выходы транзакций. Современные HD-кошельки решили эту проблему, однако всё равно стоит проверять, что механизм работы с адресами сдачи включён и что вы случайно не отключаете его при создании сырых транзакций.</p>
<h2 class="wp-block-heading">Операционные рекомендации на 2026 год</h2>
<ul>
<li>Позвольте кошельку автоматически выбирать адреса сдачи. Избегайте копирования и вставки знакомого адреса; внутренняя ветка кошелька безопаснее.</li>
<li>Помечайте UTXO. Инструменты вроде Sparrow и Specter позволяют отмечать выходы сдачи, чтобы вы понимали, когда они возникают, например, после переводов с бирж, миксеров или закрытия Lightning-каналов.</li>
<li>Следите за выбором монет. По возможности используйте минимальное количество входов, чтобы уменьшить размер сдачи и избежать лишних связей между транзакциями.</li>
<li>Осторожно используйте повторно применяемые идентификаторы. Если вам нужен постоянный способ получения средств, лучше перейти на BIP47 PayNyms, Silent Payments или Lightning вместо отключения механизма сдачи. Подробности см. в материале <a href="stealth-addresses.html">о скрытых адресах</a>.</li>
<li>Проверяйте инфраструктуру мерчантов. BTCPay, SatSale и собственные платёжные процессоры должны отправлять сдачу на отдельные казначейские счета, а не на адреса депозита, которые видят клиенты.</li>
<li>Разработчики кошельков публикуют руководства по проверке поведения адресов сдачи — см. материалы от <a href="https://support.blockchain.com/hc/en-us/articles/4417082392724-What-are-change-addresses-and-how-do-they-work" target="_blank" rel="noopener">Blockchain.com</a>, <a href="https://support.exodus.com/support/en/articles/8598675-what-are-change-addresses" target="_blank" rel="noopener">Exodus</a> или <a href="https://support.bitpay.com/hc/en-us/articles/115003063823-What-is-a-Bitcoin-change-address" target="_blank" rel="noopener">BitPay</a>, чтобы убедиться, какие настройки используются в вашем кошельке по умолчанию.</li>
</ul>
<h2 class="wp-block-heading">Распространённые вопросы</h2>
<p>Нужно ли в каких-то случаях управлять адресами сдачи вручную? Нет. Качественные кошельки создают их автоматически. Опытные пользователи, формирующие PSBT, могут указать путь для адреса сдачи вручную, но это не обязательно. Почему в обозревателе блокчейна в моей транзакции появляется «лишний» выход? Этот дополнительный выход — ваша сдача. Обозреватели не могут быть на 100% уверены, что он принадлежит именно вам, но ваш кошелёк знает это, потому что сам сгенерировал соответствующий ключ назначения. Можно ли отправлять сдачу обратно на исходный адрес? Лучше избегать этого. Такой подход снова создаёт утечки приватности, описанные на <a href="address-reuse.html">странице о повторном использовании адресов</a>, и обходит защитные механизмы HD-кошельков. Есть ли адреса сдачи в Lightning или в сетях с моделью аккаунтов? Каналы Lightning в конечном итоге всё равно рассчитываются в блокчейне через UTXO, поэтому концепция сдачи применяется при открытии и закрытии каналов. Сети с моделью аккаунтов, такие как Ethereum, отслеживают баланс иначе, поэтому им не нужны выходы сдачи.</p>
<p>Адреса сдачи — это незаметный, но важный механизм каждого Биткоин-кошелька. Позвольте кошельку автоматически обновлять их, следите за своими UTXO и сочетайте это с дисциплинированным расходованием средств, чтобы оставшиеся сатоши оставались столь же приватными, как и монеты, которые вы отправляете другим.</p>
