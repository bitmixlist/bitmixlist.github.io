---
# HARDWIRED: legacy root HTML is source of truth; not a blog post
slug: treasury-mixer-sanctions
status: draft
published_at: 2025-02-12T00:00:00Z
updated_at: 2025-02-12T00:00:00Z
author: NotATether
canonical_path: treasury-mixer-sanctions.html
body_format: html
locales:
  en:
    title: 2022 Treasury Sanctions On Mixers
    description: "A look at the U.S. Treasury sanctions on Blender.io and Tornado Cash in 2022, why they happened, and what it means for Bitcoin privacy tools."
  ru:
    title: Санкции Министерства финансов США против миксеров (2022)
    description: "2022 год стал моментом, когда Управление по контролю за иностранными активами (OFAC) Министерства финансов США официально включило миксеры в список специально обозначенных лиц и организаций (SDN)."
    body: ""
---
<p>2022 was the year the U.S. Treasury’s Office of Foreign Assets Control (OFAC) formally put mixers on the Specially Designated Nationals (SDN) list. The actions against Blender.io and Tornado Cash signaled that privacy infrastructure can be sanctioned just like banks if it allegedly facilitates nation-state hacking.</p>
        <h2 class="wp-block-heading" id="blender">Blender.io (May 2022)</h2>
        <p>OFAC’s <a href="https://home.treasury.gov/news/press-releases/jy0768" target="_blank" rel="noopener">press release</a> accused Blender.io of laundering more than $20 million for North Korea’s Lazarus Group following the Axie Infinity Ronin bridge exploit. Treasury listed dozens of deposit and withdrawal addresses and prohibited U.S. persons from providing funds, goods, or services to the mixer.</p>
        <p>The designation also highlighted how the Treasury works with blockchain analytics firms. Investigators traced the stolen Ronin funds through Blender, then froze them at compliant exchanges. That public attribution made Blender radioactive overnight.</p>
        <h2 class="wp-block-heading" id="tornado">Tornado Cash (August 2022)</h2>
        <p>In August, OFAC <a href="https://home.treasury.gov/news/press-releases/jy0916" target="_blank" rel="noopener">sanctioned Tornado Cash</a>, listing smart contract addresses on Ethereum, BSC, and other chains. Treasury alleged that Tornado processed $455 million for Lazarus and hundreds of millions more for ransomware crews.</p>
        <p>Because Tornado is a non-custodial smart contract, the sanctions triggered unprecedented side effects: GitHub removed the repository, Infura/Alchemy blocked RPC calls, and Circle froze USDC held inside the pools. Developers Alexander Pertsev and Roman Storm were arrested in Europe and the U.S., respectively, leading to ongoing court challenges.</p>
        <h2 class="wp-block-heading" id="compliance">Compliance Takeaways</h2>
        <ul>
          <li>Check the SDN list before interacting with any mixer or privacy service; the list now contains dozens of crypto addresses.</li>
          <li>If you operate infrastructure (RPC endpoints, hosting, wallets), implement controls to block sanctioned addresses or risk secondary sanctions.</li>
          <li>Devs should document their lack of control over immutable smart contracts and consider geofencing U.S. IP addresses.</li>
        </ul>
        <h2 class="wp-block-heading" id="aftershock">Aftermath</h2>
        <p>The sanctions forced privacy projects to reassess their threat models. Custodial mixers tightened KYC screening or shut down entirely, and CoinJoin coordinators began monitoring SDN updates to avoid relaying sanctioned coins. Even Bitcoin-only services that never touched Tornado Cash felt the effect as exchanges expanded their screening lists to include any CoinJoin or mixer reference.</p>
        <p>Legal battles are still playing out. A Fifth Circuit decision in 2024 narrowed OFAC’s Tornado Cash ban, but the Treasury hasn’t removed the addresses, and the criminal cases against the developers continue.</p>
        <h2 class="wp-block-heading" id="sources">Sources</h2>
        <ul>
          <li><a href="https://home.treasury.gov/news/press-releases/jy0768" target="_blank" rel="noopener">OFAC sanctions Blender.io (May 6, 2022)</a></li>
          <li><a href="https://home.treasury.gov/news/press-releases/jy0916" target="_blank" rel="noopener">OFAC sanctions Tornado Cash (Aug 8, 2022)</a></li>
        </ul>
        <p>BitMixList publishes these updates for awareness, not to encourage sanctions evasion. Follow the law where you live.</p>

<!--blog:locale:ru-->
<p>2022 год стал моментом, когда Управление по контролю за иностранными активами (OFAC) Министерства финансов США официально включило миксеры в список специально обозначенных лиц и организаций (SDN). Действия против Blender.io и Tornado Cash показали, что инфраструктура приватности может подпадать под санкции так же, как банки, если предполагается, что она способствует государственному хакерству.</p>
<h2 class="wp-block-heading" id="blender">Blender.io (май 2022)</h2>
<p>В <a href="https://home.treasury.gov/news/press-releases/jy0768" rel="noopener" target="_blank">пресс-релизе OFAC</a> Blender.io обвинялся в отмывании более 20 миллионов долларов для северокорейской группы Lazarus после взлома моста Ronin в Axie Infinity. Министерство финансов опубликовало десятки адресов для депозитов и выводов и запретило лицам из США предоставлять средства, товары или услуги этому миксеру.</p>
<p>В решении также подчеркивалось, как Министерство финансов сотрудничает с компаниями по блокчейн-аналитике. Следователи отследили украденные средства Ronin через Blender, а затем заморозили их на комплаентных биржах. Это публичное установление связи фактически сделало Blender «токсичным» за одну ночь.</p>
<h2 class="wp-block-heading" id="tornado">Tornado Cash (август 2022)</h2>
<p>В августе OFAC <a href="https://home.treasury.gov/news/press-releases/jy0916" rel="noopener" target="_blank">ввело санкции против Tornado Cash</a>, включив адреса смарт-контрактов в сетях Ethereum, BSC и других. Министерство финансов заявило, что через Tornado прошло 455 миллионов долларов для Lazarus, а также сотни миллионов для групп, занимающихся программами-вымогателями.</p>
<p>Поскольку Tornado является некастодиальным смарт-контрактом, санкции вызвали беспрецедентные последствия: GitHub удалил репозиторий, Infura и Alchemy заблокировали запросы RPC, а Circle заморозила USDC внутри пулов. Разработчики Alexander Pertsev и Roman Storm были арестованы в Европе и США соответственно, что привело к продолжающимся судебным разбирательствам.</p>
<h2 class="wp-block-heading" id="compliance">Практические выводы для комплаенса</h2>
<ul>
<li>Проверяйте список SDN перед взаимодействием с любым миксером или сервисом приватности; теперь в нем содержатся десятки криптоадресов.</li>
<li>Если вы управляете инфраструктурой (узлы RPC, хостинг, кошельки), внедряйте механизмы блокировки санкционных адресов, иначе вы рискуете вторичными санкциями.</li>
<li>Разработчикам следует документировать отсутствие контроля над неизменяемыми смарт-контрактами и рассматривать геоблокировку IP-адресов из США.</li>
</ul>
<h2 class="wp-block-heading" id="aftershock">Последствия</h2>
<p>Санкции заставили проекты в сфере приватности пересмотреть свои модели угроз. Кастодиальные миксеры усилили KYC-проверки или полностью закрылись, а координаторы CoinJoin начали отслеживать обновления списка SDN, чтобы не обрабатывать санкционные монеты. Даже сервисы, работающие только с Биткоином и не связанные с Tornado Cash, ощутили последствия, поскольку биржи расширили свои списки проверок, включая любые упоминания CoinJoin или миксеров.</p>
<p>Юридические споры продолжаются. Решение Пятого апелляционного округа в 2024 году сузило применение санкций OFAC к Tornado Cash, но Министерство финансов не удалило адреса из списков, а уголовные дела против разработчиков остаются открытыми.</p>
<h2 class="wp-block-heading" id="sources">Источники</h2>
<ul>
<li><a href="https://home.treasury.gov/news/press-releases/jy0768" rel="noopener" target="_blank">OFAC накладывает санкции на Blender.io (6 мая 2022)</a></li>
<li><a href="https://home.treasury.gov/news/press-releases/jy0916" rel="noopener" target="_blank">OFAC накладывает санкции на Tornado Cash (8 августа 2022)</a></li>
</ul>
<p>BitMixList публикует эти обновления в информационных целях, а не для поощрения обхода санкций. Соблюдайте законы в вашей юрисдикции.</p>
