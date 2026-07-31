---
slug: tumblebit
status: published
published_at: 2025-02-12T00:00:00Z
updated_at: 2026-02-19T00:00:00Z
author: NotATether
canonical_path: tumblebit.html
body_format: html
locales:
  en:
    title: "TumbleBit Explained: Trust-Minimized Bitcoin Mixing"
    description: "TumbleBit explained: learn how this trust-minimized Bitcoin mixing protocol uses multisig escrow, blind signatures, and RSA puzzles to break transaction linkability."
  ru:
    title: "Объяснение TumbleBit: миксинг Биткоина с минимальным доверием"
    description: "TumbleBit был предложен в 2016 году Ethan Heilman и его коллегами как «анонимный платежный хаб, совместимый с Биткоином и не требующий доверия»."
    body: ""
---
<p><a href="https://eprint.iacr.org/2016/575.pdf" target="_blank" rel="noopener">TumbleBit</a> was proposed in 2016 by Ethan Heilman and colleagues as an &#8220;untrusted Bitcoin-compatible anonymous payment hub.&#8221; Instead of pooling deposits like a custodial mixer, TumbleBit uses cryptographic puzzles so a central &#8220;tumbler&#8221; cannot link the incoming coins it receives to the outgoing coins it pays. If you are comparing privacy models, treat TumbleBit as a bridge between modern <a href="enhanced-coinjoins.html">CoinJoin systems</a> and older <a href="centralized-mixers.html">custodial mixers</a>.</p>
        <h2 class="wp-block-heading" id="phases">How TumbleBit Works</h2>
        <p>The protocol has three phases:</p>
        <ol>
          <li><strong>Escrow:</strong> Users and the tumbler lock coins in a 2-of-2 multisig contract so funds cannot be stolen.</li>
          <li><strong>Payment phase:</strong> Users solve RSA puzzles issued by the tumbler; revealing the solution proves the tumbler must pay them later, but the tumbler does not learn which customer solved which puzzle.</li>
          <li><strong>Settlement:</strong> Users redeem their puzzles for fresh outputs, and the tumbler can take its fee.</li>
        </ol>
        <p>Because the puzzles are blind-signed, the tumbler cannot correlate the user who funded the escrow with the user who redeems the payment token.</p>
        <h2 class="wp-block-heading" id="implementations">Implementations</h2>
        <p>The original paper inspired <a href="https://github.com/NTumbleBit/NTumbleBit" target="_blank" rel="noopener">NTumbleBit</a> and Breeze Wallet prototypes, though none reached mass adoption before CoinJoin wallets took over. Still, TumbleBit proved that Bitcoin could support trustless mixing without soft-fork changes.</p>
        <h2 class="wp-block-heading" id="legacy">Why It Matters Now</h2>
        <p>TumbleBit&#8217;s separation of custody and anonymity still influences modern research. Its blind-signature construction appears in <a href="zerolink.html">ZeroLink</a> and <a href="blind-signatures.html">WabiSabi</a>, and its payment-channel variant foreshadowed Lightning privacy work. Even if few users run TumbleBit today, the academic model remains a benchmark.</p>
        <h2 class="wp-block-heading" id="compare">TumbleBit vs CoinJoin and Custodial Mixers</h2>
        <p>Compared with CoinJoin, TumbleBit offers a clean cryptographic story for unlinkability but depends on a dedicated hub and never reached similar wallet distribution. Compared with custodial mixers, it reduces direct trust in the operator&#8217;s accounting, yet still introduces service availability risk and implementation complexity. Use the matrix in <a href="mixer-privacy.html#privacy-tech-matrix">Mixer Privacy</a> to evaluate where TumbleBit fits in a layered operational setup.</p>
        <h2 class="wp-block-heading" id="references">Key References</h2>
        <ul>
          <li><a href="https://eprint.iacr.org/2016/575.pdf" target="_blank" rel="noopener">Heilman et al., TumbleBit (2016)</a></li>
          <li><a href="https://arxiv.org/pdf/1612.00397.pdf" target="_blank" rel="noopener">Extended arXiv version (2016)</a></li>
          <li><a href="https://github.com/NTumbleBit/NTumbleBit" target="_blank" rel="noopener">NTumbleBit implementation</a></li>
        </ul>
        <h2 class="wp-block-heading" id="faq">TumbleBit FAQ</h2>
        <h3 class="wp-block-heading">Is TumbleBit still used in production?</h3>
        <p>Mostly as a research reference. TumbleBit inspired later wallet privacy designs, but mainstream production usage shifted toward CoinJoin coordinators, layered wallet hygiene, and cross-chain flows.</p>
        <h3 class="wp-block-heading">Can TumbleBit replace all Bitcoin privacy steps?</h3>
        <p>No. It helps break deterministic input/output links, but you still need address hygiene, cautious exchange interaction, and jurisdiction-aware operating practices.</p>
        <p>As always, only mix lawfully acquired funds and follow the regulations in your jurisdiction.</p>

<!--blog:locale:ru-->
<p><a href="https://eprint.iacr.org/2016/575.pdf" rel="noopener" target="_blank">TumbleBit</a> был предложен в 2016 году Ethan Heilman и его коллегами как «анонимный платежный хаб, совместимый с Биткоином и не требующий доверия». В отличие от кастодиальных миксеров, которые объединяют депозиты, TumbleBit использует криптографические головоломки, благодаря которым центральный «тумблер» не может связать входящие монеты с исходящими выплатами. Если сравнивать модели приватности, TumbleBit можно рассматривать как промежуточное звено между современными <a href="enhanced-coinjoins.html">системами CoinJoin</a> и более старыми <a href="centralized-mixers.html">кастодиальными миксерами</a>.</p>
<h2 class="wp-block-heading" id="phases">Как работает TumbleBit</h2>
<p>Протокол состоит из трех фаз:</p>
<ol>
<li><strong>Эскроу:</strong> пользователи и тумблер блокируют средства в 2-из-2 мультиподписном контракте, чтобы исключить возможность кражи.</li>
<li>Платежная фаза: пользователи решают головоломки RSA, выданные тумблером; раскрытие решения подтверждает, что тумблер обязан выплатить средства позже, но при этом он не знает, какой пользователь решил какую задачу.</li>
<li>Расчеты: пользователи обменивают решения на новые выходы, а тумблер получает свою комиссию.</li>
</ol>
<p>Поскольку головоломки подписываются вслепую, тумблер не может сопоставить пользователя, внесшего средства в эскроу, с тем, кто затем получает выплату.</p>
<h2 class="wp-block-heading" id="implementations">Имплементация</h2>
<p>Оригинальная статья вдохновила <a href="https://github.com/NTumbleBit/NTumbleBit" rel="noopener" target="_blank">NTumbleBit</a> и прототипы кошелька Breeze, хотя ни одна из реализаций не получила массового распространения до того, как доминирующими стали кошельки с CoinJoin. Тем не менее TumbleBit доказал, что Биткоин может поддерживать миксинг без доверия без внесения изменений через софтфорк.</p>
<h2 class="wp-block-heading" id="legacy">Почему это важно сейчас</h2>
<p>Разделение кастодии и анонимности в TumbleBit по-прежнему влияет на современные исследования. Его конструкция со слепыми подписями используется в <a href="zerolink.html">ZeroLink</a> и <a href="blind-signatures.html">WabiSabi</a>, а вариант с платежными каналами предвосхитил развитие приватности в Lightning. Даже если сегодня TumbleBit используют немногие, его академическая модель остается ориентиром.</p>
<h2 class="wp-block-heading" id="compare">TumbleBit vs CoinJoin и кастодиальных миксеров</h2>
<p>По сравнению с CoinJoin, TumbleBit предлагает более чистую криптографическую модель разрыва связей, но зависит от выделенного хаба и не получил такого же распространения в кошельках. По сравнению с кастодиальными миксерами, он снижает прямое доверие к оператору в части учета средств, однако все равно создает риски доступности сервиса и добавляет сложность реализации. Используйте матрицу в разделе <a href="mixer-privacy.html#privacy-tech-matrix">«Приватность миксеров»</a>, чтобы оценить, какое место TumbleBit занимает в многоуровневой операционной стратегии.</p>
<h2 class="wp-block-heading" id="references">Ключевые источники</h2>
<ul>
<li><a href="https://eprint.iacr.org/2016/575.pdf" rel="noopener" target="_blank">Heilman et al., TumbleBit (2016)</a></li>
<li><a href="https://arxiv.org/pdf/1612.00397.pdf" rel="noopener" target="_blank">Расширенная версия arXiv (2016)</a></li>
<li><a href="https://github.com/NTumbleBit/NTumbleBit" rel="noopener" target="_blank">Имплементация NTumbleBit</a></li>
</ul>
<h2 class="wp-block-heading" id="faq">Часто задаваемые вопросы по поводу TumbleBit</h2>
<h3 class="wp-block-heading">Используется ли TumbleBit в продакшене?</h3>
<p>В основном как исследовательский ориентир. TumbleBit повлиял на более поздние решения для приватности в кошельках, но массовое применение сместилось в сторону координаторов CoinJoin, дисциплины работы с кошельками и кроссчейн-маршрутов.</p>
<h3 class="wp-block-heading">Может ли TumbleBit заменить все шаги по приватности в Биткоине?</h3>
<p>Нет. Он помогает разорвать детерминированные связи между входами и выходами, но по-прежнему необходимы гигиена адресов, осторожная работа с биржами и учет регуляторных требований.</p>
<p>Как всегда, смешивайте только законно полученные средства и соблюдайте правила вашей юрисдикции.</p>
