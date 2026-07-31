---
# HARDWIRED: legacy root HTML is source of truth; not a blog post
slug: decentralized-mixers
status: draft
published_at: 2025-02-12T00:00:00Z
updated_at: 2026-02-19T00:00:00Z
author: NotATether
canonical_path: decentralized-mixers.html
body_format: html
locales:
  en:
    title: "Decentralized Mixers & CoinJoin Wallets"
    description: "Decentralized mixer guide: how non-custodial CoinJoin wallets work, what user responsibilities remain, and why exchanges still flag some outputs."
  ru:
    title: Децентрализованные миксеры и кошельки с CoinJoin
    description: "Руководство по децентрализованным миксерам: как работают некастодиальные кошельки CoinJoin, какие обязанности остаются у пользователя и почему биржи всё ещё помечают некоторые выходы."
    body: ""
---
<p>Decentralized mixers are designed for one core goal: improve Bitcoin privacy without surrendering custody to a service operator. Instead of sending funds into someone else's reserve, users coordinate a shared transaction and sign only their own inputs. That distinction matters. It removes the biggest single point of failure in custodial workflows and reduces the chance that one seized backend can expose every participant in one step.</p>
        <p>That said, non-custodial does not mean effortless. Decentralized privacy tools shift more responsibility to the user: better coin control, cleaner post-mix behavior, and stricter wallet hygiene. If those habits are weak, the protocol can work correctly and you can still leak your identity afterward.</p>
        <h2 class="wp-block-heading" id="p2p-models">Peer-to-Peer Models</h2>
        <p><a href="https://joinmarket.me/" target="_blank" rel="noopener">JoinMarket</a> uses a maker/taker market where liquidity providers earn fees for contributing to rounds. Wasabi follows ZeroLink/WabiSabi-style coordination with stronger coordinator privacy controls, while Whirlpool-style systems keep the wallet non-custodial even when coordination is centralized. The common thread is that users authorize their own inputs and keep private keys local, so custody never migrates to a tumbler backend.</p>
        <p>In practice, these tools differ mostly in liquidity architecture, coordinator trust assumptions, and UX. Advanced users often rotate between models depending on current liquidity, censorship conditions, and exchange policy pressure.</p>
        <h2 class="wp-block-heading" id="user-responsibility">User Responsibilities</h2>
        <p>Because there is no operator managing your privacy end to end, user behavior becomes the dominant risk factor. You need to isolate change, avoid address reuse, and prevent remixed outputs from merging back into tagged history. The <a href="https://arxiv.org/abs/2109.10229" target="_blank" rel="noopener">2021 CoinJoin analysis</a> showed this clearly: many observed privacy failures came from post-mix behavior, not protocol breakage.</p>
        <p>For most users, the right mental model is simple: CoinJoin is a strong privacy layer, not a one-click invisibility button. Pair it with disciplined spending and route planning, especially if funds may eventually touch KYC exchanges.</p>
        <h2 class="wp-block-heading" id="reg-watch">Regulators Still Watch</h2>
        <p>Regulators and law enforcement still monitor decentralized coordination infrastructure even when funds are not custodially held. The <a href="https://www.justice.gov/usao-sdny/pr/founders-and-ceo-cryptocurrency-mixing-service-arrested-and-charged-money-laundering" target="_blank" rel="noopener">2024 Samourai prosecution</a> reinforced that legal scrutiny can target coordination layers, service operators, and surrounding infrastructure in addition to classic custodial mixers.</p>
        <p>Exchange treatment remains a separate challenge. Many venues use analytics screening that flags some CoinJoin-related outputs, which is why wallet teams now include post-mix spending guidance and staged-routing tools. For users, this means privacy planning must include both protocol behavior and downstream exchange policy behavior.</p>
        <h2 class="wp-block-heading" id="sources">Key References</h2>
        <ul>
          <li><a href="https://arxiv.org/abs/2109.10229" target="_blank" rel="noopener">Adoption and Actual Privacy of CoinJoin Implementations (2021)</a></li>
          <li><a href="https://www.justice.gov/usao-sdny/pr/founders-and-ceo-cryptocurrency-mixing-service-arrested-and-charged-money-laundering" target="_blank" rel="noopener">DOJ charges Samourai Wallet founders (2024)</a></li>
          <li><a href="https://www.chainalysis.com/blog/crypto-mixer-criminal-volume-2022/" target="_blank" rel="noopener">Chainalysis: Mixer usage reaches all-time highs (2022)</a></li>
        </ul>
        <p>Decentralized mixers remove custodial dependency, but they reward disciplined operators and punish sloppy habits. Use them with lawful funds, track your UTXOs carefully, and treat post-mix behavior as part of the privacy model.</p>

<!--blog:locale:ru-->
<p>Децентрализованные миксеры создаются с одной основной целью: повысить приватность Биткоина без передачи контроля над средствами оператору сервиса. Вместо отправки средств в чей-то резерв пользователи координируют совместную транзакцию и подписывают только свои собственные входы. Это различие имеет большое значение. Оно устраняет крупнейшую единую точку отказа в кастодиальных схемах и снижает вероятность того, что один изъятый сервер сможет сразу раскрыть всех участников.</p>
<p>Тем не менее, некастодиальность не означает простоту. Децентрализованные инструменты приватности перекладывают больше ответственности на пользователя: более аккуратное управление монетами, корректное поведение после микширования и более строгую гигиену кошелька. Если эти привычки слабые, протокол может работать правильно, но вы всё равно можете раскрыть свою личность позже.</p>
<h2 class="wp-block-heading">P2P-модели</h2>
<p><a href="https://joinmarket.me/" target="_blank" rel="noopener">JoinMarket</a> использует модель мейкеров и тейкеров, где поставщики ликвидности получают комиссию за участие в раундах. Wasabi следует модели координации в стиле ZeroLink/WabiSabi с более сильными механизмами защиты приватности координатора, тогда как системы типа Whirlpool сохраняют некастодиальный характер кошелька даже при централизованной координации. Общим для всех является то, что пользователи подписывают только свои собственные входы и хранят приватные ключи локально, поэтому контроль над средствами никогда не передается серверному сервису микширования.</p>
<p>На практике эти инструменты в основном различаются архитектурой ликвидности, уровнем доверия к координатору и пользовательским опытом. Продвинутые пользователи часто переключаются между различными моделями в зависимости от текущей ликвидности, условий цензуры и давления со стороны политик криптобирж.</p>
<h2 class="wp-block-heading">Ответственность пользователя</h2>
<p>Поскольку нет оператора, который управляет вашей приватностью от начала до конца, поведение пользователя становится главным фактором риска. Необходимо изолировать выходы сдачи, избегать повторного использования адресов и не допускать объединения ремикшированных выходов с уже отмеченной историей транзакций. <a href="https://arxiv.org/abs/2109.10229" target="_blank" rel="noopener">Анализ CoinJoin 2021 года</a> ясно это показал: многие случаи потери приватности происходили из-за поведения пользователей после миксинга, а не из-за проблем самого протокола.</p>
<p>Для большинства пользователей правильная ментальная модель довольно проста: CoinJoin — это мощный слой приватности, а не кнопка «полной невидимости» в один клик. Его стоит сочетать с дисциплинированным подходом к тратам и планированию маршрута средств, особенно если в будущем эти средства могут попасть на KYC-биржи.</p>
<h2 class="wp-block-heading">Регуляторы все еще наблюдают</h2>
<p>Регуляторы и правоохранительные органы по-прежнему отслеживают децентрализованную координационную инфраструктуру даже в тех случаях, когда средства не находятся на кастодиальном хранении. <a href="https://www.justice.gov/usao-sdny/pr/founders-and-ceo-cryptocurrency-mixing-service-arrested-and-charged-money-laundering" target="_blank" rel="noopener">Преследование Samourai в 2024 году</a> показало, что юридическое внимание может быть направлено на координационные уровни, операторов сервисов и сопутствующую инфраструктуру, а не только на классические кастодиальные миксеры.</p>
<p>Отношение криптобирж остаётся отдельной проблемой. Многие площадки используют аналитические системы, которые помечают некоторые выходы, связанные с CoinJoin, поэтому команды кошельков теперь добавляют рекомендации по тратам после миксинга и инструменты поэтапной маршрутизации средств. Для пользователей это означает, что планирование приватности должно учитывать как поведение самого протокола, так и политику криптобирж в отношении таких транзакций.</p>
<h2 class="wp-block-heading">Ключевые источники</h2>
<ul>
<li><a href="https://arxiv.org/abs/2109.10229" target="_blank" rel="noopener">Adoption and Actual Privacy of CoinJoin Implementations (2021)</a></li>
<li><a href="https://www.justice.gov/usao-sdny/pr/founders-and-ceo-cryptocurrency-mixing-service-arrested-and-charged-money-laundering" target="_blank" rel="noopener">DOJ выдвигает обвинения основателям Samourai Wallet (2024)</a></li>
<li><a href="https://www.chainalysis.com/blog/crypto-mixer-criminal-volume-2022/" target="_blank" rel="noopener">Chainalysis: использование миксеров достигает исторического максимума (2022)</a></li>
</ul>
<p>Децентрализованные миксеры устраняют зависимость от кастодиального хранения, но вознаграждают дисциплинированных пользователей и наказывают за небрежные привычки. Используйте их с законными средствами, внимательно отслеживайте свои UTXO и рассматривайте поведение после миксинга как часть модели приватности.</p>
