const scamMixersUrl = 'https://gist.githubusercontent.com/ZenulAbidin/511d531980c44051cfafd11b2e3c9dda/raw/scamwhammer-mixers.txt';
const legitMixersUrl = 'https://gist.githubusercontent.com/ZenulAbidin/afb490c0441f29eec0fdc8ceb695a13f/raw/scamwhammer-mixers-good.txt';
const SCAMWHAMMER_IS_RU =
    ((document.documentElement?.lang || '').toLowerCase().startsWith('ru')) ||
    window.location.pathname.includes('/ru/');
const scamWhammerText = (en, ru) => (SCAMWHAMMER_IS_RU ? ru : en);

let scamMixers = [];
let legitMixers = [];

async function loadScamMixers() {
    try {
        const response = await fetch(scamMixersUrl);
        if (!response.ok) throw new Error('Failed to fetch scam mixers list');
        const text = await response.text();
        scamMixers = text.split('\n').map(line => line.trim().toLowerCase()).filter(line => line);
    } catch (error) {
        console.error('Error loading scam mixers:', error);
        alert(scamWhammerText('Error loading scam mixers list. Please try again later.', 'Ошибка загрузки списка мошеннических миксеров. Попробуйте позже.'));
    }
}

async function loadLegitMixers() {
    try {
        const response = await fetch(legitMixersUrl);
        if (!response.ok) throw new Error('Failed to fetch legit mixers list');
        const text = await response.text();
        legitMixers = text.split('\n').map(line => line.trim().toLowerCase()).filter(line => line);
    } catch (error) {
        console.error('Error loading legit mixers:', error);
        alert(scamWhammerText('Error loading legit mixers list. Please try again later.', 'Ошибка загрузки списка проверенных миксеров. Попробуйте позже.'));
    }
}

// Load both mixer lists when the script runs
Promise.all([loadScamMixers(), loadLegitMixers()]);

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
            alert(scamWhammerText('Invalid URL format. Please enter a valid URL (e.g., anonymixer.com or bitcloak4rkfygal.onion).', 'Неверный формат URL. Введите корректный адрес, например anonymixer.com или bitcloak4rkfygal.onion.'));
            return;
        }
    }

    // Remove 'www.' prefix if present
    domain = domain.replace(/^www\./, '');

    // Ensure both mixer lists are loaded
    if (scamMixers.length === 0 || legitMixers.length === 0) {
        await Promise.all([loadScamMixers(), loadLegitMixers()]);
        if (scamMixers.length === 0 || legitMixers.length === 0) {
            alert(scamWhammerText('Unable to check URL due to failure in loading mixer lists.', 'Невозможно проверить URL: списки миксеров не загрузились.'));
            return;
        }
    }

    // Check if the domain is in the scam list
    if (scamMixers.includes(domain)) {
        alert(
            scamWhammerText(
                `Warning: ${domain} is a known SCAM or SEIZED crypto mixer. Avoid using this service!`,
                `Внимание: ${domain} известен как СКАМ или ИЗЪЯТЫЙ криптомиксер. Не используйте этот сервис!`
            )
        );
    }
    // Check if the domain is in the legit list
    else if (legitMixers.includes(domain)) {
        alert(scamWhammerText(`${domain} is a LEGITIMATE crypto mixer.`, `${domain} — ЛЕГИТИМНЫЙ криптомиксер.`));
    }
    // If not found in either list
    else {
        alert(
            scamWhammerText(
                `The URL ${domain} is not recognized in our database. Exercise caution and verify its legitimacy before using.`,
                `URL ${domain} не найден в нашей базе. Соблюдайте осторожность и проверьте его перед использованием.`
            )
        );
    }

    // Clear the input field
    document.getElementById('urlInput').value = '';
}
