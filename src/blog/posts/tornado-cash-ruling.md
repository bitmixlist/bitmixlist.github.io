---
# HARDWIRED: legacy root HTML is source of truth; not a blog post
slug: tornado-cash-ruling
status: draft
published_at: 2025-02-12T00:00:00Z
updated_at: 2025-02-12T00:00:00Z
author: NotATether
canonical_path: tornado-cash-ruling.html
body_format: html
locales:
  en:
    title: Tornado Cash Court Ruling (2024)
    description: Overview of the Fifth Circuit decision addressing OFAC’s sanctions on Tornado Cash smart contracts and what it means for privacy code.
  ru:
    title: Судебное решение по делу Tornado Cash (2024)
    description: "Министерство финансов США ввело санкции против Tornado Cash в августе 2022 года, включив десятки адресов смарт-контрактов в список SDN."
    body: ""
---
<p>The U.S. Treasury sanctioned Tornado Cash in August 2022, listing dozens of smart-contract addresses on the SDN list. Coinbase-funded plaintiffs (including developers and longtime users) challenged the sanctions. In November 2024 the U.S. Court of Appeals for the Fifth Circuit agreed in part, ruling that OFAC cannot list autonomous code lacking a property interest, though human-controlled entities tied to the protocol remain fair game.</p>
        <h2 class="wp-block-heading" id="background">Case Background</h2>
        <p>Plaintiffs argued that OFAC exceeded its authority because Tornado Cash smart contracts cannot be owned, controlled, or altered by any person. They also claimed First Amendment violations, since the sanctions blocked lawful use (e.g., donating anonymously or protecting salary privacy). OFAC countered that the Tornado DAO and its multisig treasury constitute an &#8220;association&#8221; of persons that can be sanctioned. Dutch authorities reinforced that view when they <a href="https://www.infosecurity-magazine.com/news/dutch-authorities-arrest-tornado/" target="_blank" rel="noopener">arrested developer Alexey Pertsev in August 2022</a>, arguing that humans still profited from operating the relayers.</p>
        <h2 class="wp-block-heading" id="decision">What The Court Said</h2>
        <p>The Fifth Circuit drew a line: immutable smart contracts operating independently are not &#8220;property&#8221; or &#8220;persons&#8221; under IEEPA, but the Tornado DAO, treasury multisig, and developers who exercise control can still be sanctioned if they materially support designated actors. Analyses such as <a href="https://www.morganlewis.com/pubs/2024/12/fifth-circuit-rejects-ofacs-tornado-cash-sanctions" target="_blank" rel="noopener">Morgan Lewis’ summary</a> explain that OFAC must target individuals or entities, not autonomous code.</p>
        <h2 class="wp-block-heading" id="implications">Implications</h2>
        <p>The ruling does not legalize every privacy protocol. Front-ends, DAO treasuries, or developers coordinating upgrades can still be sanctioned—those actors have property interests. But it suggests that immutable code running without human control may fall outside OFAC jurisdiction. For Bitcoin mixers, the takeaway is that custodial services, coordinators, and fee-collecting businesses remain squarely at risk, whereas open-source CoinJoin code is safer if no one controls its execution.</p>
        <p>Developers should still document governance structures, avoid direct control over user funds, and plan for sanctions risk (e.g., geofencing U.S. IPs, screening donations).</p>
        <h2 class="wp-block-heading" id="sources">Sources</h2>
        <ul>
          <li><a href="https://www.morganlewis.com/pubs/2024/12/fifth-circuit-rejects-ofacs-tornado-cash-sanctions" target="_blank" rel="noopener">Morgan Lewis: Fifth Circuit Rejects OFAC Tornado Cash Sanctions</a></li>
        </ul>
        <p>Legal fights continue. Stay informed and keep your privacy tooling compliant.</p>

<!--blog:locale:ru-->
<p>Министерство финансов США ввело санкции против Tornado Cash в августе 2022 года, включив десятки адресов смарт-контрактов в список SDN. Истцы, финансируемые Coinbase (включая разработчиков и давних пользователей), оспорили эти санкции. В ноябре 2024 года Апелляционный суд США по Пятому округу частично согласился, постановив, что OFAC не может включать в санкционные списки автономный код, не имеющий имущественного интереса, хотя контролируемые людьми структуры, связанные с протоколом, по-прежнему могут подпадать под санкции.</p>
<h2 class="wp-block-heading" id="background">Предыстория дела</h2>
<p>Истцы утверждали, что OFAC превысило свои полномочия, поскольку смарт-контракты Tornado Cash не могут быть собственностью, контролироваться или изменяться каким-либо лицом. Они также ссылались на нарушение Первой поправки, поскольку санкции блокировали законное использование (например, анонимные пожертвования или защиту приватности зарплат). OFAC возразило, что Tornado DAO и его мультиподписное казначейство представляют собой «объединение» лиц, которое может подпадать под санкции. Нидерландские власти поддержали эту позицию, <a href="https://www.infosecurity-magazine.com/news/dutch-authorities-arrest-tornado/" target="_blank" rel="noopener">арестовав разработчика Alexey Pertsev в августе 2022 года</a> и заявив, что люди все же получали прибыль от работы релееров.</p>
<h2 class="wp-block-heading" id="decision">Что постановил суд</h2>
<p>Пятый апелляционный округ провел границу: неизменяемые смарт-контракты, функционирующие автономно, не являются «собственностью» или «лицами» в рамках IEEPA, однако Tornado DAO, мультиподписное казначейство и разработчики, осуществляющие контроль, по-прежнему могут подпадать под санкции, если они оказывают существенную поддержку подсанкционным субъектам. Аналитические материалы, такие как разбор Morgan Lewis, поясняют, что OFAC должно направлять санкции на физических или юридических лиц, а не на автономный код.</p>
<h2 class="wp-block-heading" id="implications">Последствия</h2>
<p>Решение не легализует все протоколы приватности. Фронтенды, казначейства DAO или разработчики, координирующие обновления, по-прежнему могут подпадать под санкции — у этих участников есть имущественные интересы. Однако оно указывает, что неизменяемый код, работающий без человеческого контроля, может находиться вне юрисдикции OFAC. Для Биткоин-миксеров вывод в том, что кастодиальные сервисы, координаторы и бизнесы, взимающие комиссии, остаются в зоне высокого риска, тогда как реализации с открытым исходным кодом CoinJoin более защищены, если никто не контролирует их исполнение.</p>
<p>Разработчикам все же следует документировать структуры управления, избегать прямого контроля над средствами пользователей и учитывать санкционные риски (например, геоблокировку IP из США и проверку пожертвований).</p>
<h2 class="wp-block-heading" id="sources">Источник</h2>
<ul>
<li><a href="https://www.morganlewis.com/pubs/2024/12/fifth-circuit-rejects-ofacs-tornado-cash-sanctions" rel="noopener" target="_blank">Morgan Lewis: Пятый апелляционный округ отклоняет санкции OFAC против Tornado Cash</a></li>
</ul>
<p>Юридические споры продолжаются. Оставайтесь в курсе и поддерживайте свои инструменты приватности в соответствии с требованиями.</p>
