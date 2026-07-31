---
slug: zerolink
status: published
published_at: 2025-02-12T00:00:00Z
updated_at: 2026-02-19T00:00:00Z
author: NotATether
canonical_path: zerolink.html
body_format: html
locales:
  en:
    title: ZeroLink Privacy Framework
    description: "ZeroLink explained: the Bitcoin privacy framework behind Chaumian CoinJoin, toxic change handling, and the design path that later shaped Wasabi and WabiSabi."
  ru:
    title: Фреймворк приватности ZeroLink
    description: "ZeroLink — это фреймворк повышения взаимозаменяемости (fungibility), предложенный в 2017 году Адамом Фичсором (nopara73), который превратил разрозненные идеи приватности в конкретную архитектуру кошелька."
    body: ""
---
<p><a href="https://github.com/nopara73/ZeroLink" target="_blank" rel="noopener">ZeroLink</a> is a 2017 fungibility framework from Adam Ficsor (nopara73) that turned a loose set of privacy ideas into a concrete wallet architecture. Instead of treating CoinJoin as a single transaction trick, ZeroLink defines the full operational flow: coordinator interaction, credential handling, fee logic, output registration, and post-mix wallet behavior.</p>
        <p>That broader scope is why ZeroLink still matters. It gave developers a structured way to build privacy wallets that were usable by normal people, not just protocol specialists, and it directly influenced early HiddenWallet and Wasabi design choices.</p>
        <h2 class="wp-block-heading" id="pillars">ZeroLink Pillars</h2>
        <p>At a high level, ZeroLink is built on three practical principles that work together. If one part is missing, the privacy benefit drops quickly in real-world usage.</p>
        <ul>
          <li><strong>CoinJoin coordination:</strong> Users register inputs with a coordinator, obtain blind-signed credentials, and redeem them for anonymized outputs.</li>
          <li><strong>Wallet hygiene:</strong> Mandatory fresh addresses, randomized fees, and separate change outputs.</li>
          <li><strong>Toxic change policy:</strong> Wallets automatically quarantine change and avoid merging it back into mixed UTXOs.</li>
        </ul>
        <h2 class="wp-block-heading" id="wasabi">From ZeroLink to Wasabi</h2>
        <p>Wasabi Wallet became the first widely known implementation path in 2018. In 2021, the team moved to <a href="https://wasabiwallet.io/wabisabi.pdf" target="_blank" rel="noopener">WabiSabi</a>, which replaced simpler credential mechanics with KVAC-based logic that supports more flexible denominations. That change improved usability and pool composition while keeping the same core goal: break deterministic links without exposing users to custodial risk.</p>
        <p>Independent analysis, including work such as <a href="https://arxiv.org/abs/2109.10229" target="_blank" rel="noopener">Stutz et al. (2021)</a>, helped test how these designs behave under real transaction conditions rather than ideal protocol assumptions.</p>
        <h2 class="wp-block-heading" id="legacy">Why ZeroLink Still Matters</h2>
        <p>ZeroLink remains a baseline checklist for evaluating collaborative Bitcoin privacy tools. When a wallet claims stronger privacy, the practical questions still come from this framework: how are credentials blinded, how is change isolated, what metadata is retained, and where can user behavior accidentally collapse anonymity sets?</p>
        <p>Even where implementations differ, the ZeroLink model continues to shape how developers reason about coordinator trust, wallet defaults, and user safety. It is less a historical artifact and more a reference architecture that keeps showing up in newer protocol and wallet work.</p>
        <h2 class="wp-block-heading" id="references">References</h2>
        <ul>
          <li><a href="https://github.com/nopara73/ZeroLink/blob/master/ZeroLink.md" target="_blank" rel="noopener">ZeroLink specification (2017)</a></li>
          <li><a href="https://wasabiwallet.io/wabisabi.pdf" target="_blank" rel="noopener">WabiSabi white paper (2021)</a></li>
          <li><a href="https://arxiv.org/abs/2109.10229" target="_blank" rel="noopener">Adoption and Actual Privacy of CoinJoin Implementations (2021)</a></li>
        </ul>
        <p>ZeroLink is a privacy framework, not a legal shield. Use CoinJoin tooling lawfully and follow the regulations that apply in your jurisdiction.</p>

<!--blog:locale:ru-->
<p><a href="https://github.com/nopara73/ZeroLink" rel="noopener" target="_blank">ZeroLink</a> — это фреймворк повышения взаимозаменяемости (fungibility), предложенный в 2017 году Адамом Фичсором (nopara73), который превратил разрозненные идеи приватности в конкретную архитектуру кошелька. Вместо того чтобы рассматривать CoinJoin как разовый трюк с транзакцией, ZeroLink описывает полный операционный процесс: взаимодействие с координатором, работу с учетными данными, логику комиссий, регистрацию выходов и поведение кошелька после миксинга.</p>
<p>Именно этот более широкий подход объясняет, почему ZeroLink остается актуальным. Он дал разработчикам структурированную модель для создания кошельков приватности, пригодных для обычных пользователей, а не только для специалистов по протоколам, и напрямую повлиял на ранние решения в HiddenWallet и Wasabi.</p>
<h2 class="wp-block-heading" id="pillars">Основные принципы ZeroLink</h2>
<p>На высоком уровне ZeroLink строится на трех практических принципах, которые работают вместе. Если один из элементов отсутствует, уровень приватности в реальных условиях быстро снижается.</p>
<ul>
<li><strong>Координация CoinJoin:</strong> пользователи регистрируют входы у координатора, получают слепо подписанные учетные данные и используют их для получения анонимизированных выходов.</li>
<li><strong>Гигиена кошелька:</strong> обязательное использование новых адресов, рандомизация комиссий и раздельные выходы сдачи.</li>
<li>Политика «токсичной сдачи»: кошельки автоматически изолируют сдачу и избегают ее смешивания с уже перемешанными UTXO.</li>
</ul>
<h2 class="wp-block-heading" id="wasabi">От ZeroLink к Wasabi</h2>
<p>Wasabi Wallet стал первым широко известным примером реализации этой модели в 2018 году. В 2021 году команда перешла на <a href="https://wasabiwallet.io/wabisabi.pdf" rel="noopener" target="_blank">WabiSabi</a> — механизм, который заменил более простую схему учетных данных на логику на базе KVAC и позволил использовать более гибкие номиналы. Это улучшило удобство использования и структуру пулов, сохранив при этом ключевую цель: разрывать детерминированные связи без кастодиальных рисков.</p>
<p>Независимые исследования, включая работы вроде Stutz и соавт. (2021), позволили проверить, как такие конструкции ведут себя в реальных условиях транзакций, а не только в рамках идеализированных протокольных предположений.</p>
<h2 class="wp-block-heading" id="legacy">Почему ZeroLink все еще имеет значение</h2>
<p>ZeroLink остается базовым чек-листом для оценки совместных инструментов приватности в Биткоин. Когда кошелек заявляет о более высокой приватности, практические вопросы по-прежнему формулируются в рамках этого фреймворка: как реализовано ослепление учетных данных, как изолируется сдача, какие метаданные сохраняются и где поведение пользователя может случайно разрушить анонимное множество.</p>
<p>Даже при различиях в реализациях модель ZeroLink продолжает формировать подход разработчиков к вопросам доверия к координатору, настройкам кошелька по умолчанию и безопасности пользователей. Это уже не просто исторический артефакт, а референсная архитектура, которая снова и снова проявляется в новых протоколах и кошельках.</p>
<h2 class="wp-block-heading" id="references">Ссылки</h2>
<ul>
<li><a href="https://github.com/nopara73/ZeroLink/blob/master/ZeroLink.md" rel="noopener" target="_blank">Спецификация ZeroLink (2017)</a></li>
<li><a href="https://wasabiwallet.io/wabisabi.pdf" rel="noopener" target="_blank">WabiSabi white paper (2021)</a></li>
<li><a href="https://arxiv.org/abs/2109.10229" rel="noopener" target="_blank">Распространение и фактическая приватность реализаций CoinJoin (2021)</a></li>
</ul>
<p>ZeroLink — это фреймворк приватности, а не юридическая защита. Используйте инструменты CoinJoin законно и соблюдайте требования, действующие в вашей юрисдикции.</p>
