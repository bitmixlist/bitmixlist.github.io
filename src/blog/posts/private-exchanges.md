---
slug: private-exchanges
status: published
published_at: 2025-06-01T00:00:00Z
updated_at: 2026-02-19T00:00:00Z
author: NotATether
canonical_path: private-exchanges.html
body_format: html
locales:
  en:
    title: Second-Wave Private Exchanges
    description: "Second-wave private exchanges explained: RoboSats, Haveno, Agoradesk, and UnstoppableSwap, plus how users replaced KYC-heavy exchanges after crackdowns."
  ru:
    title: Частные обменники второй волны
    description: "По мере того как регулируемые биржи ужесточали контроль над активностью, связанной с приватностью, многие пользователи перешли на более небольшие площадки, построенные вокруг…"
    body: ""
---
<p>As regulated exchanges tightened controls on privacy-linked activity, many users moved to smaller marketplaces built around reputation, escrow, and open infrastructure instead of full KYC onboarding. RoboSats, Haveno, Agoradesk, and UnstoppableSwap became core examples of this second wave.<sup><a href="https://learn.robosats.com/" target="_blank" rel="noopener">[1]</a></sup><sup><a href="https://haveno.exchange/" target="_blank" rel="noopener">[2]</a></sup><sup><a href="https://localmonero.co/blog/announcements/winding-down" target="_blank" rel="noopener">[3]</a></sup><sup><a href="https://unstoppableswap.net/" target="_blank" rel="noopener">[4]</a></sup></p>
        <p>These platforms did not try to mimic centralized exchange UX exactly. Instead, they focused on censorship resistance, low data retention, and community-operated liquidity paths that could survive policy shocks. That made them less convenient for some users, but more resilient for users who were repeatedly running into freezes, geoblocks, or intrusive source-of-funds reviews.</p>
        <h2 class="wp-block-heading" id="robosats">Robosats</h2>
        <p>RoboSats pairs Lightning-native trade flow with bond-backed escrow and Tor-only access, making it one of the most accessible privacy-first entry points for smaller BTC trades.<sup><a href="https://learn.robosats.com/docs/private/" target="_blank" rel="noopener">[1]</a></sup> The robot identity model is intentionally disposable, so users can build short-term reputation without creating a long-lived personal profile.</p>
        <p>Key operational traits include:</p>
        <ul>
          <li>Lightning-native escrow with onion messaging, avoiding on-chain fingerprints.</li>
          <li>Automated dispute resolution where moderators arbitrate based on signed chat logs.</li>
          <li>Telegram, Matrix, and Nostr mirrors to broadcast order-book health without revealing IPs.</li>
        </ul>
        <h2 class="wp-block-heading" id="haveno">Haveno</h2>
        <p>Haveno is a Monero-first Bisq-style network where trades run over Tor and settlement uses non-custodial multisig structures.<sup><a href="https://haveno.exchange/" target="_blank" rel="noopener">[2]</a></sup> Its value proposition is straightforward: minimize centralized trust while keeping the system forkable if one team disappears.</p>
        <p>Operators typically highlight:</p>
        <ol>
          <li>Built-in Monero wallets so users never expose xpubs to a third party.</li>
          <li>Public documentation on how to spin up new networks and testnets, ensuring anyone can fork the stack.</li>
          <li>Upcoming bridges to <a href="atomic-swaps.html">atomic swap</a> daemons so wallets like Feather can route straight into Haveno.</li>
        </ol>
        <h2 class="wp-block-heading" id="agoradesk">Agoradesk</h2>
        <p>Agoradesk, which expanded from the LocalMonero model, kept the classic P2P classifieds workflow: makers posted offers, buyers selected payment rails, and escrow/moderation handled disputes.<sup><a href="https://localmonero.co/blog/announcements/winding-down" target="_blank" rel="noopener">[3]</a></sup> It served users who preferred flexible fiat methods over order-book trading interfaces.</p>
        <ul>
          <li>Offered fiat rails in 200+ countries without storing passport scans—verification relied on trade history.</li>
          <li>Provided “problem coin” services where sellers accepted tainted BTC at a discount and routed it through mixes or swaps.</li>
          <li>Published transparency reports whenever accounts were seized or frozen.</li>
        </ul>
        <p>In May 2024, the team announced both LocalMonero and Agoradesk would wind down by November 2024. The full timeline, market impact, and successor channels are covered in <a href="localmonero-agoradesk-exit.html">LocalMonero / Agoradesk Exit</a>.</p>
        <h2 class="wp-block-heading" id="unstoppableswap">UnstoppableSwap</h2>
        <p>UnstoppableSwap (distributed today via eigenwallet tooling) automates BTC↔XMR atomic swaps through relays and watchtower coordination, avoiding custodial handoff risk.<sup><a href="https://unstoppableswap.net/why" target="_blank" rel="noopener">[4]</a></sup> For users and mixer operators alike, it acts as a bridge between Bitcoin-denominated flows and Monero liquidity without requiring centralized exchange accounts.</p>
        <p>The project also documents relay operation on commodity hardware, which encourages network redundancy and lowers dependence on any single operator.<sup><a href="https://unstoppableswap.net/liquidity" target="_blank" rel="noopener">[4]</a></sup></p>
        <h2 class="wp-block-heading" id="lessons">Why These Platforms Matter</h2>
        <p>Despite different architectures, second-wave private exchanges tend to share the same strategic characteristics:</p>
        <ul>
          <li><strong>Minimal data retention:</strong> No long-term storage of KYC files; disputes rely on ephemeral signatures, which platforms like RoboSats and Haveno explicitly document.<sup><a href="https://learn.robosats.com/docs/private/" target="_blank" rel="noopener">[1]</a></sup><sup><a href="https://haveno.exchange/" target="_blank" rel="noopener">[2]</a></sup></li>
          <li><strong>Open-source infrastructure:</strong> Communities can fork the code if founders disappear—mitigating <a href="tradeogre-seizure.html">single-operator risk</a>.</li>
          <li><strong>Mixer synergy:</strong> They advertise to the same users who need mixers, making it trivial to rebalance liquidity without touching banks.</li>
        </ul>
        <p>In short, these services matter because they preserve optionality when regulated venues become unreliable for privacy-focused users.</p>

<!--blog:locale:ru-->
<p>По мере того как регулируемые биржи ужесточали контроль над активностью, связанной с приватностью, многие пользователи перешли на более небольшие площадки, построенные вокруг репутации, эскроу и открытой инфраструктуры вместо полноценного KYC-онбординга. RoboSats, Haveno, Agoradesk и UnstoppableSwap стали ключевыми примерами этой второй волны. <a href="https://learn.robosats.com/" rel="noopener" target="_blank">[1]</a> <a href="https://haveno.exchange/" rel="noopener" target="_blank">[2]</a> <a href="https://localmonero.co/blog/announcements/winding-down" rel="noopener" target="_blank">[3]</a> <a href="https://unstoppableswap.net/" rel="noopener" target="_blank">[4]</a></p>
<p>Эти платформы не пытались точно копировать UX централизованных бирж. Вместо этого они делали упор на устойчивость к цензуре, минимальное хранение данных и ликвидность, управляемую сообществом, способную переживать регуляторные шоки. Это сделало их менее удобными для части пользователей, но более устойчивыми для тех, кто регулярно сталкивался с заморозками, геоблокировками или навязчивыми проверками происхождения средств.</p>
<h2 class="wp-block-heading" id="robosats">Robosats</h2>
<p>RoboSats сочетает торговые потоки на базе Lightning с эскроу на залогах и доступом только через Tor, что делает его одним из самых доступных вариантов входа с приоритетом приватности для небольших сделок в BTC <a href="https://learn.robosats.com/docs/private/" rel="noopener" target="_blank">[1]</a> Модель «робот-идентичности» изначально задумана как одноразовая, чтобы пользователи могли нарабатывать краткосрочную репутацию без создания долговременного персонального профиля.</p>
<p>Ключевые операционные особенности:</p>
<ul>
<li>Эскроу на базе Lightning с сообщениями onion, что позволяет избегать ончейн-отпечатков.</li>
<li>Автоматизированное разрешение споров, где модераторы принимают решения на основе подписанных логов чата.</li>
<li>Зеркала в Telegram, Matrix и Nostr для публикации состояния ордербука без раскрытия IP-адресов.</li>
</ul>
<h2 class="wp-block-heading" id="haveno">Haveno</h2>
<p>Haveno — это сеть в стиле Bisq с приоритетом Monero, где сделки проходят через Tor, а расчеты осуществляются с использованием некастодиальных мультиподписных схем. <a href="https://haveno.exchange/" rel="noopener" target="_blank">[2]</a> Его ценностное предложение простое: минимизировать централизованное доверие и при этом сохранить возможность форка системы, если одна команда исчезнет.</p>
<p>Операторы обычно выделяют:</p>
<ol>
<li>Встроенные кошельки Monero, благодаря которым пользователи не раскрывают xpub третьим сторонам.</li>
<li>Публичную документацию по запуску новых сетей и тестнетов, что позволяет любому форкнуть стек.</li>
<li>Планируемые мосты к демонам <a href="atomic-swaps.html">атомарных свопов</a>, чтобы кошельки вроде Feather могли напрямую работать с Haveno.</li>
</ol>
<h2 class="wp-block-heading" id="agoradesk">Agoradesk</h2>
<p>Платформа была ориентирована на пользователей, которым важнее гибкие фиатные методы, чем классические интерфейсы с ордербуком.</p>
<ul>
<li>Предлагала фиатные рельсы в более чем 200 странах без хранения паспортных данных — верификация строилась на истории сделок.</li>
<li>Предоставляла услуги для «проблемных монет», где продавцы принимали «загрязненный» BTC со скидкой и далее проводили его через миксинг или свопы.</li>
<li>Публиковала отчеты о прозрачности при каждом случае заморозки или изъятия аккаунтов.</li>
</ul>
<p>В мае 2024 года команда объявила, что и LocalMonero, и Agoradesk будут закрыты к ноябрю 2024 года. Полная хронология, влияние на рынок и альтернативные каналы описаны в <a href="localmonero-agoradesk-exit.html">LocalMonero / Agoradesk Exit</a>.</p>
<h2 class="wp-block-heading" id="unstoppableswap">UnstoppableSwap</h2>
<p>UnstoppableSwap (сегодня распространяется через инструменты EigenWallet) автоматизирует атомарные свопы BTC ↔ XMR через реле и координацию сторожевых узлов, избегая рисков кастодиальной передачи средств <a href="https://unstoppableswap.net/why" rel="noopener" target="_blank">[4]</a> Для пользователей и операторов миксеров это выступает как мост между потоками в BTC и ликвидностью Monero без необходимости использовать аккаунты на централизованных биржах.</p>
<p>Проект также документирует запуск реле на обычном оборудовании, что повышает устойчивость сети и снижает зависимость от одного оператора. <a href="https://unstoppableswap.net/liquidity" rel="noopener" target="_blank">[4]</a></p>
<h2 class="wp-block-heading" id="lessons">Почему эти платформы имеют значение</h2>
<p>Несмотря на различия в архитектуре, приватные обменные платформы второй волны обычно имеют схожие стратегические характеристики:</p>
<ul>
<li>Минимальное хранение данных: отсутствие долгосрочного хранения KYC-документов; разрешение споров опирается на временные подписи, что прямо документируется на платформах вроде RoboSats и Haveno. <a href="https://learn.robosats.com/docs/private/" rel="noopener" target="_blank">[1]</a> <a href="https://haveno.exchange/" rel="noopener" target="_blank">[2]</a></li>
<li>Открытая <strong>инфраструктура с открытым исходным кодом:</strong> сообщества могут форкнуть код, если основатели исчезнут, — снижая <a href="tradeogre-seizure.html">риски зависимости от одного оператора</a>.</li>
<li>Синергия с миксерами: они ориентированы на ту же аудиторию, которой нужны миксеры, что упрощает перераспределение ликвидности без обращения к банковской системе.</li>
</ul>
<p>В итоге такие сервисы важны, потому что сохраняют вариативность, когда регулируемые площадки становятся ненадежными для пользователей, ориентированных на приватность.</p>
