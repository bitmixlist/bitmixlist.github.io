---
slug: bitcoin-fog-sterlingov
status: published
published_at: 2025-02-12T00:00:00Z
updated_at: 2026-02-19T00:00:00Z
author: NotATether
canonical_path: bitcoin-fog-sterlingov.html
body_format: html
locales:
  en:
    title: "Bitcoin Fog & Roman Sterlingov"
    description: "Bitcoin Fog case study: how investigators linked Roman Sterlingov to a long-running mixer, what evidence supported the 2024 verdict, and lessons for custodial services."
  ru:
    title: Bitcoin Fog и Роман Стерлингов
    description: "Кейс Bitcoin Fog: как следователи связали Романа Стерлингова с давно работавшим миксером, какие доказательства легли в основу приговора 2024 года и какие уроки это дает кастодиальным сервисам."
    body: ""
---
<p>Bitcoin Fog is one of the landmark mixer prosecutions because it covered a very long operating window and forced courts to evaluate how far modern blockchain forensics can go when combined with conventional records. According to U.S. prosecutors, the service started in 2011, processed very large volume over time, and became a go-to custodial tumbler for users who wanted distance from traceable Bitcoin flows. By the time the case reached trial, the argument was no longer just about one service. It was about whether historical on-chain activity, infrastructure payments, and account records could be stitched into a criminal attribution narrative years later.</p>
        <h2 class="wp-block-heading" id="how-it-worked">How Bitcoin Fog Operated</h2>
        <p>Operationally, Bitcoin Fog followed the classic custodial model: users sent BTC to service-controlled wallets, the operator applied fees, then returned different outputs after delays and batching. That approach can reduce straightforward input-output linkage for users, but it creates a central point of custody, a central operations layer, and a central legal target. Fog marketed privacy protections, including delay controls and no-log style claims, yet the model still required customers to trust that backend behavior matched public messaging.</p>
        <p>That gap between marketing and infrastructure is where many custodial services fail under scrutiny. Once investigators can subpoena providers tied to hosting, payments, domains, or account recovery channels, even older operations can become legible again. This is exactly why many current privacy workflows now emphasize non-custodial alternatives such as CoinJoin and <a href="monero-privacy-alternative.html">Monero bridge strategies</a>.</p>
        <h2 class="wp-block-heading" id="investigation">How Investigators Built The Case</h2>
        <p>Authorities described a blended methodology rather than one single silver bullet, combining on-chain analysis with traditional legal process:</p>
        <ul>
          <li><strong>Blockchain clustering:</strong> heuristic tracing linked historical mixer flows with known exchange activity, including older platform records.</li>
          <li><strong>Subpoena-driven metadata:</strong> email, payment, and infrastructure records were used to connect service operations to the defendant.</li>
          <li><strong>Controlled test activity:</strong> investigators reportedly ran deposits and tracked outputs to points where identity-linked records could be requested.</li>
          <li><strong>Corroborating travel/timing data:</strong> movement records were presented as context around key operational events.</li>
        </ul>
        <p>For the wider ecosystem, the key takeaway is that courts accepted this multi-source evidentiary approach, reinforcing that blockchain analytics are strongest when paired with off-chain records rather than treated as standalone proof.</p>
        <h2 class="wp-block-heading" id="timeline">Arrest, Trial, and Defense Arguments</h2>
        <p>Sterlingov was arrested in April 2021 and fought the case through trial, with the defense challenging attribution quality and forensic reliability. The government position focused on long-term operational linkage: infrastructure payments, records, and traced flow patterns were presented as a coherent ownership story rather than independent coincidences.</p>
        <p>In March 2024, a federal jury found him guilty on major counts including money laundering conspiracy and unlicensed money transmission. In November 2024, sentencing included 150 months in prison, supervised release, and large forfeiture orders. In practical terms, the result aligned with the tougher posture seen across related cases such as <a href="bestmixer-seizure.html">Bestmixer</a> and later actions in the broader DOJ timeline.</p>
        <h2 class="wp-block-heading" id="lessons">Lessons For Custodial Services</h2>
        <ul>
          <li><strong>Historical data ages slowly:</strong> old transactions can still become actionable when new records or counterparties appear.</li>
          <li><strong>Jurisdiction is layered:</strong> offshore hosting does not neutralize risk when infrastructure, customers, or payment rails intersect high-enforcement regions.</li>
          <li><strong>Intent signals matter:</strong> marketing, clientele profile, and operational behavior can be used together to argue facilitation.</li>
          <li><strong>Users absorb operator risk:</strong> if one service holds custody, one seizure or conviction can expose years of downstream users to review pressure in <a href="exchange-freezes.html">exchange freeze workflows</a>.</li>
        </ul>
        <h2 class="wp-block-heading" id="sources">Key References</h2>
        <ul>
          <li><a href="https://www.justice.gov/usao-dc/pr/jury-finds-russian-swedish-operator-bitcoin-fog-guilty-running-darknet-cryptocurrency" target="_blank" rel="noopener">Jury finds Sterlingov guilty (March 2024)</a></li>
          <li><a href="https://www.justice.gov/usao-dc/pr/operator-bitcoin-fog-sentenced-more-12-years-prison-running-notorious-darknet" target="_blank" rel="noopener">Sentencing press release (Nov 2024)</a></li>
        </ul>

<!--blog:locale:ru-->
<p>Bitcoin Fog — одно из знаковых уголовных дел против криптомиксеров, поскольку оно охватывало очень длительный период работы сервиса и заставило суды оценить, насколько далеко может зайти современная блокчейн-криминалистика в сочетании с обычными записями и документами. По версии прокуратуры США, сервис начал работу в 2011 году, со временем обработал очень большой объём транзакций и стал популярным кастодиальным тумблером для пользователей, которые хотели дистанцироваться от отслеживаемых потоков Биткоина. К моменту, когда дело дошло до суда, вопрос уже касался не только одного сервиса. Речь шла о том, можно ли спустя годы объединить историческую активность в блокчейне, платежи за инфраструктуру и данные аккаунтов в единую доказательственную картину.</p>
<h2 class="wp-block-heading">Как работал Bitcoin Fog</h2>
<p>С операционной точки зрения Bitcoin Fog работал по классической кастодиальной модели: пользователи отправляли BTC на кошельки, контролируемые сервисом, оператор удерживал комиссию, а затем возвращал другие выходы после задержек и пакетирования транзакций. Такой подход может усложнять прямое сопоставление входов и выходов для пользователей, но одновременно создаёт центральную точку хранения средств, центральный операционный слой и центральную юридическую цель. Fog рекламировал механизмы приватности, включая настройки задержек и заявления в духе «no logs», однако модель всё равно требовала, чтобы клиенты доверяли тому, что реальная работа сервера соответствует публичным заявлениям.</p>
<p>Именно этот разрыв между маркетинговыми заявлениями и реальной инфраструктурой часто становится проблемой для кастодиальных сервисов при расследованиях. Как только следователи получают возможность направлять запросы провайдерам, связанным с хостингом, платежами, доменами или каналами восстановления аккаунтов, даже старые операции могут снова стать понятными и прослеживаемыми. Именно поэтому во многих современных стратегиях приватности всё чаще делают упор на некастодиальные альтернативы, такие как CoinJoin и <a href="monero-privacy-alternative.html">мосты через Monero</a>.</p>
<h2 class="wp-block-heading">Как следователи строили дело</h2>
<p>Власти описывали комбинированную методологию, а не один «волшебный» метод: анализ блокчейна использовался вместе с традиционными юридическими процедурами.</p>
<ul>
<li>Кластеризация блокчейна: с помощью эвристического анализа связывались исторические потоки средств через миксер с известной активностью на биржах, включая данные старых платформ.</li>
<li>Метаданные, полученные по юридическим запросам: записи электронной почты, платёжные данные и инфраструктурные журналы использовались для связывания работы сервиса с обвиняемым.</li>
<li>Контролируемые тестовые операции: следователи, как сообщалось, проводили тестовые депозиты и отслеживали выходящие средства до точек, где можно было запросить данные, связанные с личностью.</li>
<li>Дополнительные данные о перемещениях и времени: сведения о поездках и временных совпадениях представлялись как контекст вокруг ключевых операционных событий.</li>
</ul>
<p>Для всей экосистемы главный вывод заключается в том, что суды приняли такой многоисточниковый подход к доказательствам. Это подтвердило, что аналитика блокчейна наиболее эффективна тогда, когда она сочетается с оффчейн-данными, а не рассматривается как единственное доказательство сама по себе.</p>
<h2 class="wp-block-heading">Арест, суд и аргументы защиты</h2>
<p>Стерлингов был арестован в апреле 2021 года и оспаривал обвинения вплоть до судебного разбирательства, при этом защита ставила под сомнение точность атрибуции и надёжность криминалистического анализа. Позиция обвинения строилась на долгосрочной операционной связи: платежи за инфраструктуру, различные записи и прослеженные потоки средств были представлены как единая картина владения и управления сервисом, а не как набор случайных совпадений.</p>
<p>В марте 2024 года федеральное жюри признало его виновным по основным пунктам обвинения, включая сговор с целью отмывания денег и осуществление денежных переводов без лицензии. В ноябре 2024 года суд назначил наказание в виде 150 месяцев лишения свободы, последующего надзорного периода и крупных решений о конфискации имущества. На практике этот результат соответствует более жёсткой линии, которая прослеживается и в других делах, таких как <a href="bestmixer-seizure.html">Bestmixer</a>, а также в более поздних действиях в общей хронологии дел Министерства юстиции США.</p>
<h2 class="wp-block-heading">Уроки для кастодиальных сервисов</h2>
<ul>
<li>Исторические данные устаревают медленно: даже давние транзакции могут стать основанием для расследования, когда появляются новые записи или новые связанные стороны.</li>
<li>Юрисдикции накладываются друг на друга: офшорный хостинг не устраняет риски, если инфраструктура, клиенты или платёжные каналы пересекаются с регионами с жёстким правоприменением.</li>
<li>Сигналы намерения имеют значение: маркетинг, профиль клиентской базы и операционная практика могут рассматриваться вместе как признаки содействия противоправной деятельности.</li>
<li>Пользователи принимают на себя риск оператора: если один сервис хранит средства пользователей, одна конфискация или обвинительный приговор может привести к тому, что операции многих пользователей за прошлые годы попадут под проверки и <a href="exchange-freezes.html">заморозку средств на биржах</a>.</li>
</ul>
<h2 class="wp-block-heading">Ключевые ссылки</h2>
<ul>
<li><a href="https://www.justice.gov/usao-dc/pr/jury-finds-russian-swedish-operator-bitcoin-fog-guilty-running-darknet-cryptocurrency" target="_blank" rel="noopener">Присяжные признали Стерлингова виновным (март 2024)</a></li>
<li><a href="https://www.justice.gov/usao-dc/pr/operator-bitcoin-fog-sentenced-more-12-years-prison-running-notorious-darknet" target="_blank" rel="noopener">Пресс-релиз о приговоре (ноябрь 2024)</a></li>
</ul>
