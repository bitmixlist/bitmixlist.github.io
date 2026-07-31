---
slug: blender-sanctions
status: published
published_at: 2025-02-12T00:00:00Z
updated_at: 2026-02-19T00:00:00Z
author: NotATether
canonical_path: blender-sanctions.html
body_format: html
locales:
  en:
    title: Blender Sanctions
    description: "Blender.io sanctions case study: why OFAC designated the mixer in 2022, how the action connected to Lazarus-linked flows, and what it changed for mixer operators."
  ru:
    title: Санкции против Blender
    description: "Разбор санкций против Blender.io: почему OFAC внесло миксер в санкционный список в 2022 году, как это было связано с потоками Lazarus и что изменилось для операторов миксеров."
    body: ""
---
<p>Blender.io matters in enforcement history because it was the first Bitcoin mixer formally sanctioned by OFAC. On May 6, 2022, the U.S. Treasury designated the service after alleging that it helped launder funds tied to North Korea's Lazarus Group, including proceeds from the Ronin bridge exploit. That decision changed the policy conversation immediately: mixers were no longer treated only as AML-risk services, but as sanctions-risk infrastructure when linked to state-backed hacking campaigns.</p>
        <p>The case also marked a shift in operational risk for everyone around mixer ecosystems. Once OFAC publicly lists service addresses, the effect propagates across exchanges, payment processors, wallets, and compliance vendors. Even users with lawful intent can feel second-order consequences when risk systems tighten around related clusters and counterparties.</p>
        <h2 class="wp-block-heading" id="timeline">Timeline</h2>
        <p>The progression from service activity to sanctions followed a pattern now common in later actions:</p>
        <ul>
          <li><strong>2017–2021:</strong> Blender is active in public forums and underground channels, promoting custodial mixing features.</li>
          <li><strong>March 2022:</strong> investigators tie portions of post-hack laundering activity to Blender-linked infrastructure.</li>
          <li><strong>May 6, 2022:</strong> OFAC adds Blender to the SDN list and publishes associated addresses and warning language.</li>
          <li><strong>Late 2022 onward:</strong> market observers connect successor activity to Sinbad, later followed by the <a href="sinbad-sanctions.html">2023 Sinbad sanctions</a>.</li>
        </ul>
        <p>For operators, the important point is that enforcement pressure does not reset when branding changes. Address intelligence, infrastructure overlap, and partner records often outlive domain names.</p>
        <h2 class="wp-block-heading" id="findings">OFAC Findings</h2>
        <p>OFAC's public framing emphasized that Blender allegedly processed major illicit flows tied to sanctioned actors and other criminal proceeds. The designation narrative highlighted three recurring concerns:</p>
        <ul>
          <li>Routing value from high-profile Lazarus-linked thefts and related laundering chains.</li>
          <li>Handling additional criminal proceeds beyond one single incident.</li>
          <li>Relying on counterparties and processors with weak or non-existent AML controls.</li>
        </ul>
        <p>Another practical takeaway was that the listing did not stop at core hot wallets. Publicly attributed addresses linked to operations and promotion were also surfaced, showing how marketing activity can become evidentiary context during sanctions actions.</p>
        <h2 class="wp-block-heading" id="lessons">Lessons &amp; Compliance</h2>
        <ul>
          <li><strong>Rebrands do not erase exposure:</strong> sanctions risk can carry forward across overlapping infrastructure and flows.</li>
          <li><strong>Public promotion creates records:</strong> forum posts, ad wallets, and payout trails can later support attribution work.</li>
          <li><strong>SDN effects propagate fast:</strong> once listed, related UTXOs and counterparties face freeze/reporting pressure at compliant venues.</li>
        </ul>
        <p>If you operate or integrate privacy infrastructure, treat sanctions controls as core operations, not an afterthought. Screen inbound flows, log policy decisions, and keep evidence of SDN-blocking controls. For user-side checks, the <a href="aml-check.html">BitMixList AML Checker</a> helps surface obvious exposure before funds reach regulated endpoints.</p>
        <h2 class="wp-block-heading" id="references">References</h2>
        <ul>
          <li><a href="https://home.treasury.gov/news/press-releases/jy0768" target="_blank" rel="noopener">U.S. Treasury sanctions Blender.io (2022)</a></li>
          <li><a href="https://www.fiod.nl/fiod-takes-large-crypto-currency-mixer-off-the-air/" target="_blank" rel="noopener">FIOD press release on Sinbad seizure (2023)</a></li>
          <li><a href="https://www.reuters.com/technology/us-sanctions-virtual-currency-mixer-sinbad-2023-11-29/" target="_blank" rel="noopener">Reuters coverage of Sinbad sanctions</a></li>
        </ul>

<!--blog:locale:ru-->
<p>Blender.io занимает важное место в истории регулирования, поскольку это был первый Биткоин-миксер, официально включённый в санкционный список OFAC. 6 мая 2022 года Министерство финансов США внесло сервис в санкционный перечень, заявив, что он помогал отмывать средства, связанные с северокорейской группировкой Lazarus Group, включая доходы от взлома моста Ronin. Это решение сразу изменило политическую дискуссию: миксеры начали рассматривать не только как сервисы с AML-рисками, но и как инфраструктуру санкционных рисков, когда они связаны с государственно поддерживаемыми хакерскими операциями.</p>
<p>Этот случай также изменил операционные риски для всей экосистемы вокруг миксеров. Когда OFAC публично публикует адреса сервиса, эффект распространяется на биржи, платёжные процессоры, кошельки и поставщиков комплаенс-аналитики. Даже пользователи с законными целями могут столкнуться со вторичными последствиями, когда системы оценки рисков начинают жёстче реагировать на связанные кластеры адресов и контрагентов.</p>
<h2 class="wp-block-heading">Хронология</h2>
<p>Развитие событий — от работы сервиса до введения санкций — следовало модели, которая позже стала типичной и для других случаев:</p>
<ul>
<li>2017–2021: Blender активно присутствует на публичных форумах и в подпольных каналах, продвигая функции кастодиального миксинга.</li>
<li>Март 2022: следователи связывают часть операций по отмыванию средств после взломов с инфраструктурой, связанной с Blender.</li>
<li>6 мая 2022: OFAC включает Blender в список SDN и публикует связанные адреса вместе с предупреждениями.</li>
<li>С конца 2022 года: наблюдатели рынка связывают возможную деятельность-преемника с сервисом <a href="sinbad-sanctions.html">Sinbad</a>, после чего в 2023 году вводятся санкции и против Sinbad.</li>
</ul>
<p>Для операторов важный вывод заключается в том, что давление со стороны властей не исчезает при смене бренда. Данные об адресах, пересечение инфраструктуры и записи партнёрских сервисов часто сохраняются гораздо дольше, чем сами доменные имена.</p>
<h2 class="wp-block-heading">Выводы OFAC</h2>
<p>Публичная позиция OFAC подчёркивала, что Blender, по утверждению ведомства, обрабатывал значительные объёмы незаконных средств, связанных с находящимися под санкциями участниками и другими преступными доходами. В обосновании санкций выделялись три повторяющиеся проблемы:</p>
<ul>
<li>Маршрутизация средств, полученных в результате крупных краж, связанных с группировкой Lazarus, и последующих цепочек отмывания.</li>
<li>Обработка других преступных доходов, не ограниченных одним конкретным инцидентом.</li>
<li>Использование контрагентов и платёжных процессоров со слабыми или отсутствующими AML-процедурами.</li>
</ul>
<p>Ещё один практический вывод заключался в том, что санкции затронули не только основные горячие кошельки сервиса. Публично были указаны и другие адреса, связанные с операционной деятельностью и продвижением сервиса, что показывает, как маркетинговая активность может становиться частью доказательной базы при введении санкций.</p>
<h2 class="wp-block-heading">Уроки и комплаенс</h2>
<ul>
<li>Ребрендинг не устраняет риски: санкционные риски могут сохраняться из-за пересечения инфраструктуры и потоков средств.</li>
<li>Публичное продвижение оставляет следы: сообщения на форумах, рекламные кошельки и цепочки выплат позже могут использоваться для установления связи и атрибуции.</li>
<li>Эффект списка SDN распространяется быстро: после включения в список связанные UTXO и контрагенты сталкиваются с давлением в виде заморозок и обязательной отчётности на регулируемых площадках.</li>
</ul>
<p>Если вы управляете или интегрируете инфраструктуру приватности, относитесь к санкционному контролю как к ключевой части операционной деятельности, а не как к второстепенной задаче. Проверяйте входящие потоки средств, фиксируйте решения по политике и сохраняйте доказательства работы механизмов блокировки адресов из списка SDN. Для пользовательских проверок инструмент <a href="aml-check.html">AML-чекер BitMixList</a> помогает выявить очевидные риски до того, как средства попадут на регулируемые площадки.</p>
<h2 class="wp-block-heading">Источники</h2>
<ul>
<li><a href="https://home.treasury.gov/news/press-releases/jy0768" target="_blank" rel="noopener">Санкции Казначейства США против Blender.io (2022)</a></li>
<li><a href="https://www.fiod.nl/fiod-takes-large-crypto-currency-mixer-off-the-air/" target="_blank" rel="noopener">Пресс-релиз FIOD о конфискации Sinbad (2023)</a></li>
<li><a href="https://www.reuters.com/technology/us-sanctions-virtual-currency-mixer-sinbad-2023-11-29/" target="_blank" rel="noopener">Материал Reuters о санкциях против Sinbad</a></li>
</ul>
