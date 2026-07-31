---
slug: helix-larry-harmon
status: published
published_at: 2025-02-12T00:00:00Z
updated_at: 2026-02-19T00:00:00Z
author: NotATether
canonical_path: helix-larry-harmon.html
body_format: html
locales:
  en:
    title: Helix / Larry Harmon Case Summary
    description: "Helix and Larry Harmon case explained: timeline, DOJ and FinCEN actions, guilty plea, $60M penalty, and why this case shaped bitcoin mixer enforcement."
  ru:
    title: Краткое изложение дела Helix / Ларри Хармона
    description: "Объяснение дела Хеликса и Ларри Хармона: сроки, действия Министерства юстиции и FinCEN, признание вины, штраф в размере 60 миллионов долларов и то, почему это дело повлияло на правоприменение биткоин-миксеров."
    body: ""
---
<p>Helix operated from 2014 to 2017 as a custodial bitcoin mixer linked to Larry Harmon&#8217;s darknet search engine, Grams. The pitch was simple: send in BTC, pay a service fee, and receive different coins back so the original on-chain trail would be harder to follow. In court filings, US authorities described that flow as laundering infrastructure for darknet sales, not a neutral privacy tool, and that framing became central to everything that followed.</p>
        <p>According to DOJ and FinCEN statements, Helix processed hundreds of thousands of transactions and moved more than $300&nbsp;million tied in large part to AlphaBay activity. That scale matters because it turned the case into more than a one-off prosecution. Investigators used Helix to establish a template they could later apply in other mixer cases, which is why it still appears in most summaries of the broader <a href="crackdown.html">crackdown timeline</a>.</p>

        <h2 class="wp-block-heading" id="timeline">Timeline &amp; Background</h2>
        <p>The timeline is important because agencies did not treat Helix as a sudden enforcement event. They built a record over multiple years, then paired criminal and civil actions at roughly the same time. That one-two structure later showed up again in other high-profile mixer investigations.</p>
        <ul>
          <li><strong>2014:</strong> Harmon launches Helix alongside Grams, marketing the service to users who wanted to break visible links between source and destination coins.</li>
          <li><strong>2017:</strong> After AlphaBay is dismantled, Helix winds down and Harmon shifts attention to Coin Ninja, a wallet and payments project.</li>
          <li><strong>February&nbsp;2020:</strong> DOJ unseals the indictment and alleges Helix handled about 356,000 bitcoin transactions; FinCEN in parallel announces a combined $60&nbsp;million civil penalty tied to Helix and Coin Ninja.</li>
          <li><strong>August&nbsp;2021:</strong> Harmon pleads guilty to money-laundering conspiracy, and the court orders forfeiture of cryptocurrency, cash, and other property while requiring continued cooperation.</li>
        </ul>

        <h2 class="wp-block-heading" id="charges">Criminal Charges &amp; Evidence</h2>
        <p>The criminal case alleged that Harmon operated Helix as an unlicensed money-transmitting business and knowingly helped users move proceeds tied to darknet crime. Prosecutors did not rely on one data point. They combined technical records, platform messaging, and financial movement into a narrative that jurors and regulators could follow without needing deep blockchain expertise.</p>
        <p>Key evidence highlighted in public filings included:</p>
        <ul>
          <li>Server logs tying Helix deposit addresses to AlphaBay vendor accounts and to ransomware payouts.</li>
          <li>Marketing copy explicitly offering to route user coins in ways that suggested deliberate evasion of AML controls.</li>
          <li>Exchange-side financial records that gave investigators a fiat off-ramp trail, not just blockchain-only evidence.</li>
        </ul>
        <p>FinCEN pointed to its <a href="fincen-2019-guidance.html">2019 guidance</a> to argue that custodial mixing businesses were already on notice: if you take control of customer funds, you are expected to register and run a real AML program. In other words, the agency treated this as a compliance failure with clear prior warning, not a gray area discovered after the fact.</p>

        <h2 class="wp-block-heading" id="penalty">FinCEN Penalty &amp; Compliance Lessons</h2>
        <p>FinCEN's civil action split the penalty into $40&nbsp;million linked to Helix and $20&nbsp;million linked to Coin Ninja. The order described missing or inadequate controls across the board: no meaningful suspicious-activity process, weak sanctions controls, and public-facing claims that downplayed legal exposure. That combination made the penalty read less like a paperwork issue and more like a full risk-management breakdown.</p>
        <p>The cooperation terms in the consent order also mattered. They signaled that the case was part of a wider investigative pipeline, similar to themes seen later in cases such as <a href="sinbad-sanctions.html">Sinbad/Blender</a>. For privacy-tool operators, Helix is still a practical warning that messaging, logs, and operational choices can all become evidence if a service is treated as custodial mixing.</p>
        <p>Lessons for privacy builders:</p>
        <ul>
          <li><strong>Marketing language can be fatal.</strong> Public promises about cleaning coins or bypassing controls are easy for prosecutors to frame as intent.</li>
          <li><strong>US nexus brings MSB expectations.</strong> A custodial mixer model is treated as money transmission, which triggers registration and AML duties.</li>
          <li><strong>Operational data remains discoverable.</strong> Years after shutdown, logs and exchange records can still drive cases and support freezes (<a href="exchange-freezes.html">see the tracker</a>).</li>
        </ul>

        <h2 class="wp-block-heading" id="impact">Impact on Later Crackdowns</h2>
        <p>Helix became a reference point for later enforcement against custodial mixers, including actions that followed against services like Bestmixer, ChipMixer, and eXch. Agencies showed they could combine undercover activity, subpoena returns from exchanges, seized infrastructure data, and archived marketing claims into one coherent theory of the case. That blend lowered the barrier for future prosecutions because the evidentiary playbook was already tested.</p>
        <p>The case is also regularly cited when regulators discuss unregistered money transmission in crypto. Even when facts differ across defendants, Helix remains the historical example used to explain why authorities believe some mixer operations are not just privacy software but regulated financial services. That is why this case still connects directly to later policy warnings, including the <a href="fbi-non-custodial-warning.html">FBI 2024 advisory</a>.</p>

        <h2 class="wp-block-heading" id="sources">Primary References</h2>
        <ul>
          <li><a href="https://www.justice.gov/archives/opa/pr/ohio-resident-charged-operating-darknet-based-bitcoin-mixer-which-laundered-over-300-million" target="_blank" rel="noopener">DOJ indictment of Larry Harmon (2020)</a></li>
          <li><a href="https://www.fincen.gov/news/news-releases/fincen-fines-founder-offline-crypto-mixer-helix-and-coin-ninja-60-million" target="_blank" rel="noopener">FinCEN civil penalty announcement (2020)</a></li>
          <li><a href="https://www.infosecurity-magazine.com/news/coin-ninja-ceo-operated-helix-grams/" target="_blank" rel="noopener">Infosecurity coverage of Helix</a></li>
        </ul>

<!--blog:locale:ru-->
<p>Helix работал с 2014 по 2017 год как кастодиальный Биткоин-миксер, связанный с даркнет-поисковиком Grams, созданным Ларри Хармоном. Суть сервиса была простой: отправляешь BTC, платишь комиссию и получаешь другие монеты, чтобы исходный ончейн-трек было сложнее отследить. В судебных материалах власти США описали эту модель как инфраструктуру для отмывания средств от даркнет-продаж, а не как нейтральный инструмент приватности — и именно такая трактовка стала ключевой для последующих кейсов.</p>
<p>По данным DOJ и FinCEN, через Helix прошло сотни тысяч транзакций и более $300 млн, значительная часть которых была связана с активностью AlphaBay. Масштаб имеет значение: это превратило дело не просто в единичное преследование. Следователи использовали кейс Helix как шаблон, который затем применяли к другим делам о миксерах — поэтому он до сих пор фигурирует в большинстве разборов общей <a href="crackdown.html">хронологии регуляторного давления</a>.</p>
<h2 class="wp-block-heading">Хронология и предыстория</h2>
<p>Хронология важна, потому что ведомства не рассматривали Helix как внезапный кейс. Они формировали доказательную базу на протяжении нескольких лет, а затем синхронизировали уголовные и гражданские меры почти одновременно. Такая «двухходовая» модель позже повторялась и в других резонансных расследованиях миксеров.</p>
<ul>
<li>2014: Хармон запускает Helix вместе с Grams, продвигая сервис для пользователей, желающих разорвать видимую связь между источником и получателем монет.</li>
<li>2017: После закрытия AlphaBay Helix сворачивает работу, и Хармон переключается на Coin Ninja — проект кошелька и платежной инфраструктуры.</li>
<li>Февраль 2020: DOJ раскрывает обвинение и заявляет, что через Helix прошло около 356 000 транзакций Биткоин; параллельно FinCEN объявляет гражданский штраф в размере $60 млн, связанный с Helix и Coin Ninja.</li>
<li>Август 2021: Хармон признает вину в сговоре с целью отмывания средств, суд постановляет конфискацию криптоактивов, наличных и другого имущества, а также требует дальнейшего сотрудничества.</li>
</ul>
<h2 class="wp-block-heading">Уголовные обвинения и доказательства</h2>
<p>В уголовном деле утверждалось, что Хармон управлял Helix как незарегистрированным провайдером денежных переводов и сознательно помогал пользователям перемещать средства, связанные с даркнет-преступностью. Прокуроры не опирались на один источник данных: они объединили технические логи, коммуникацию на платформе и финансовые потоки в последовательную картину, понятную присяжным и регуляторам без глубокой экспертизы в блокчейне.</p>
<p>Ключевые доказательства, отмеченные в публичных материалах:</p>
<ul>
<li>Серверные логи, связывающие адреса депозитов Helix с аккаунтами продавцов AlphaBay и выплатами выкупа программ-вымогателей.</li>
<li>Маркетинговые материалы, прямо обещающие маршрутизацию средств таким образом, что это указывает на сознательное обход AML-контролей.</li>
<li>Финансовые записи со стороны бирж, давшие следствию фиатный оффрамп, а не только ончейн-доказательства.</li>
</ul>
<p>FinCEN ссылался на свое <a href="fincen-2019-guidance.html">руководство 2019 года</a>, утверждая, что кастодиальные миксинговые сервисы уже были уведомлены: если вы берете средства клиентов под контроль, вы обязаны регистрироваться и внедрять полноценную AML-программу. Иными словами, ведомство рассматривало ситуацию как нарушение комплаенса при наличии четкого предварительного уведомления, а не как серую зону, выявленную постфактум.</p>
<h2 class="wp-block-heading">Штраф FinCEN и комплаенс-выводы</h2>
<p>Гражданское дело FinCEN разделило штраф на $40 млн, связанных с Helix, и $20 млн — с Coin Ninja. В постановлении отмечались системные провалы: отсутствие полноценной процедуры выявления подозрительной активности, слабые санкционные контроли и публичные заявления, занижающие юридические риски. В совокупности это выглядело не как формальная ошибка, а как полный сбой системы управления рисками.</p>
<p>Условия сотрудничества в соглашении также имели значение. Они показали, что дело было частью более широкой цепочки расследований — аналогичные паттерны позже проявились в кейсах вроде <a href="sinbad-sanctions.html">Sinbad/Blender</a>. Для операторов инструментов приватности Helix до сих пор остается практическим предупреждением: маркетинг, логи и операционные решения могут стать доказательствами, если сервис квалифицируется как кастодиальный миксинг.</p>
<h2 class="wp-block-heading">Выводы для разработчиков инструментов приватности:</h2>
<ul>
<li>Маркетинговые формулировки могут быть критичными. Публичные обещания «очистки» монет или обхода контролей легко интерпретируются как умысел.</li>
<li>Связь с США означает требования уровня MSB. Кастодиальная модель миксера рассматривается как перевод средств, что влечет регистрацию и AML-обязанности.</li>
<li>Операционные данные остаются доступными для расследований. Даже спустя годы после закрытия логи и биржевые записи могут использоваться в делах и приводить к заморозке средств (<a href="exchange-freezes.html">см. tracker</a>).</li>
</ul>
<h2 class="wp-block-heading">Влияние на последующие репрессии</h2>
<p>Helix стал ориентиром для последующих действий против кастодиальных миксеров, включая кейсы против сервисов вроде Bestmixer, ChipMixer и eXch. Ведомства показали, что могут объединять операции под прикрытием, ответы на повестки от бирж, данные из изъятой инфраструктуры и архивные маркетинговые материалы в единую и последовательную доказательную модель. Такая комбинация снизила порог для будущих дел, поскольку доказательная «методичка» уже была отработана.</p>
<p>Этот кейс также регулярно упоминается, когда регуляторы обсуждают незарегистрированную деятельность по переводу средств в крипте. Даже если факты в разных делах отличаются, Helix остается историческим примером, с помощью которого объясняется позиция властей: некоторые миксинговые сервисы рассматриваются не как просто ПО для приватности, а как регулируемые финансовые услуги. Именно поэтому этот кейс напрямую связан с более поздними регуляторными сигналами, включая <a href="fbi-non-custodial-warning.html">предупреждение ФБР 2024 года</a>.</p>
<h2 class="wp-block-heading">Основные источники</h2>
<ul>
<li><a href="https://www.justice.gov/archives/opa/pr/ohio-resident-charged-operating-darknet-based-bitcoin-mixer-which-laundered-over-300-million" target="_blank" rel="noopener">Обвинительное заключение DOJ против Ларри Хармона (2020)</a></li>
<li><a href="https://www.fincen.gov/news/news-releases/fincen-fines-founder-offline-crypto-mixer-helix-and-coin-ninja-60-million" target="_blank" rel="noopener">Объявление FinCEN о гражданском штрафе (2020)</a></li>
<li><a href="https://www.infosecurity-magazine.com/news/coin-ninja-ceo-operated-helix-grams/" target="_blank" rel="noopener">Освещение дела Helix изданием Infosecurity</a></li>
</ul>
