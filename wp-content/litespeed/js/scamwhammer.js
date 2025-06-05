const legitMixers = [
    'mixer.money', 'mixtum.io', 'coinomize.biz', 'coinomize.is', 'coinomize.co',
    'anonymixer.com', 'webmixer.io', 'mixtura.money', 'mixero.io', 'bitmixer.online',
    'mixy.money', 'mixerdream.com', 'thormixer.io', 'jokermix.to', 'swamplizard.io',
    'genesismix.cx', 'okmix.io', 'okmix.me', 'okmix.cc', 'okmix.co', 'okmix.pw',
    'okmix.vip', 'okmix.biz', 'okmix.top', 'okmix.org',
    // .onion URLs for legitimate mixers
    'mixermo4pgkgep3k3qr4fz7dhijavxnh6lwgu7gf5qeltpy4unjed2yd.onion',
    'mixtumjzn2tsiusfkdhutntamspsg43kgt764qbdaxjebce4h6fcfiad.onion',
    'coino2q64k4fg3lkjsnhjeydzwykw22a56u5nf2rdfzkjuy3jbwvypqd.onion',
    'btcmixer2e3pkn64eb5m65un5nypat4mje27er4ymltzshkmujmxlmyd.onion',
    'webmix2nwd6qpq6tjkqshfivt3qqjoutl535xk2z32tgapqfn52z62yd.onion',
    '3lqpiyzlqudwxiizx6uecc6z5zr6vvxidyi6inuducc7lilsdem2mlqd.onion',
    'mixeroyubx5g5yxaucsxcd767vn2lnujuuz2dh53quwabukhrok2ekid.onion',
    'bitmixhft4cpncluhwffussk23ltvowswbe4tlrdree74oxjmz2vyqqd.onion',
    'nlljgev5y27bajfoq7os2t6qv27y24hzpnvkmfjevhz4eg5ddbrciyid.onion',
    'ipyg3uxi25nxq3qvo7we26o5s6irencdkndv7orbxibzgbuhjmgnafad.onion',
    '63tcvr7j5gju24emo3ygbxmezqmg7z2zyby27n647jmu4uzcosiduzid.onion',
    'ssw25okonfrvgv423u5n54khg2ojjeuzl65lta5bjkbjeh2ju7nu7zid.onion',
    'genesislakkmzosj47snwmjbz2ugsuoyfznfbswgpy543exhwrd2rjyd.onion',
    '6ufjzcw5tbw6zbeek3qooooh7jteehtf4i36nz43rqyks3pcazaithqd.onion',
    'wqa7jejlascugfwyrbb3shvwgaczxlkconthjyuqdb52sdn6kx57tpqd.onion'
];

// URL for the scam mixers Gist
const scamMixersUrl = 'https://gist.githubusercontent.com/ZenulAbidin/511d531980c44051cfafd11b2e3c9dda/raw/75c3d8a34ed0b9c6ee1cb129110a83edafd4951e/scamwhammer-mixers.txt';

let scamMixers = [];

async function loadScamMixers() {
    try {
        const response = await fetch(scamMixersUrl);
        if (!response.ok) throw new Error('Failed to fetch scam mixers list');
        const text = await response.text();
        scamMixers = text.split('\n').map(line => line.trim().toLowerCase()).filter(line => line);
    } catch (error) {
        console.error('Error loading scam mixers:', error);
        alert('Error loading scam mixers list. Please try again later.');
    }
}

// Load scam mixers when the script runs
loadScamMixers();

async function checkUrl(event) {
    event.preventDefault();
    const urlInput = document.getElementById('urlInput').value.trim().toLowerCase();
    let domain = urlInput;

    // Extract domain from URL if it includes protocol or path
    try {
        domain = new URL(urlInput.startsWith('http') ? urlInput : `http://${urlInput}`).hostname;
    } catch (e) {
        // Handle .onion addresses or invalid URLs
        if (urlInput.endsWith('.onion')) {
            domain = urlInput;
        } else {
            alert('Invalid URL format. Please enter a valid URL (e.g., anonymixer.com or bitcloak4rkfygal.onion).');
            return;
        }
    }

    // Remove 'www.' prefix if present
    domain = domain.replace(/^www\./, '');

    // Ensure scam mixers list is loaded
    if (scamMixers.length === 0) {
        await loadScamMixers();
        if (scamMixers.length === 0) {
            alert('Unable to check URL due to failure in loading scam mixers list.');
            return;
        }
    }

    // Check if the domain is in the scam list
    if (scamMixers.includes(domain)) {
        alert(`Warning: ${domain} is a known SCAM or SEIZED crypto mixer. Avoid using this service!`);
    }
    // Check if the domain is in the legit list
    else if (legitMixers.includes(domain)) {
        alert(`${domain} is a LEGITIMATE crypto mixer.`);
    }
    // If not found in either list
    else {
        alert(`The URL ${domain} is not recognized in our database. Exercise caution and verify its legitimacy before using.`);
    }

    // Clear the input field
    document.getElementById('urlInput').value = '';
}