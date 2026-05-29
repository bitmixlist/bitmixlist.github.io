const scamMixersUrl = 'https://gist.githubusercontent.com/ZenulAbidin/511d531980c44051cfafd11b2e3c9dda/raw/scamwhammer-mixers.txt';
const legitMixersUrl = 'https://gist.githubusercontent.com/ZenulAbidin/afb490c0441f29eec0fdc8ceb695a13f/raw/scamwhammer-mixers-good.txt';
const SCAMWHAMMER_IS_RU =
    ((document.documentElement?.lang || '').toLowerCase().startsWith('ru')) ||
    window.location.pathname.includes('/ru/');
const scamWhammerText = (en, ru) => (SCAMWHAMMER_IS_RU ? ru : en);

function getScamWhammerStatusElement() {
    const root = document.getElementById('scamwhammer');
    if (!root) return null;

    let status = document.getElementById('scamWhammerStatus');
    if (!status) {
        status = document.createElement('div');
        status.id = 'scamWhammerStatus';
        status.className = 'vg-status';
        status.setAttribute('role', 'status');
        status.setAttribute('aria-live', 'polite');

        const form = document.getElementById('urlForm');
        if (form && form.parentNode) {
            form.insertAdjacentElement('afterend', status);
        } else {
            root.appendChild(status);
        }
    }

    return status;
}

function setScamWhammerStatus(message, type = 'info') {
    const status = getScamWhammerStatusElement();
    if (!status) return;

    status.textContent = message;
    status.className = `vg-status vg-status--${type}`;
    status.setAttribute('role', type === 'error' ? 'alert' : 'status');
    status.setAttribute('aria-live', type === 'error' ? 'assertive' : 'polite');
    status.hidden = false;
}

function clearScamWhammerStatus() {
    const status = getScamWhammerStatusElement();
    if (!status) return;

    status.textContent = '';
    status.hidden = true;
}

let scamMixers = [];
let legitMixers = [];
let scamWhammerListsPromise = null;

async function loadScamMixers() {
    try {
        const response = await fetch(scamMixersUrl);
        if (!response.ok) throw new Error('Failed to fetch scam mixers list');
        const text = await response.text();
        scamMixers = text.split('\n').map(line => line.trim().toLowerCase()).filter(line => line);
    } catch (error) {
        console.error('Error loading scam mixers:', error);
        setScamWhammerStatus(
            scamWhammerText(
                'Error loading scam mixers list. Please try again later.',
                'Ошибка загрузки списка мошеннических миксеров. Попробуйте позже.'
            ),
            'error'
        );
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
        setScamWhammerStatus(
            scamWhammerText(
                'Error loading legit mixers list. Please try again later.',
                'Ошибка загрузки списка проверенных миксеров. Попробуйте позже.'
            ),
            'error'
        );
    }
}

function ensureScamWhammerLists() {
    if (!scamWhammerListsPromise) {
        scamWhammerListsPromise = Promise.all([loadScamMixers(), loadLegitMixers()]);
    }

    return scamWhammerListsPromise;
}

function normalizeScamWhammerDomain(value) {
    const input = (value || '').trim().toLowerCase();
    if (!input) return '';

    try {
        const parsed = new URL(input.startsWith('http') ? input : `http://${input}`);
        return parsed.hostname.replace(/^www\./, '').replace(/\.$/, '');
    } catch (e) {
        const onionMatch = input.match(/([a-z0-9-]+\.onion)(?:[/:?#]|$)/i);
        if (onionMatch) {
            return onionMatch[1].toLowerCase();
        }

        return input.split('/')[0].replace(/^www\./, '').replace(/\.$/, '');
    }
}

async function checkUrl(event) {
    event.preventDefault();
    clearScamWhammerStatus();

    const urlInput = document.getElementById('urlInput').value.trim().toLowerCase();
    let domain = normalizeScamWhammerDomain(urlInput);
    if (!domain) {
        setScamWhammerStatus(
            scamWhammerText(
                'Invalid URL format. Please enter a valid URL (e.g., anonymixer.com or bitcloak4rkfygal.onion).',
                'Неверный формат URL. Введите корректный адрес, например anonymixer.com или bitcloak4rkfygal.onion.'
            ),
            'error'
        );
        return;
    }

    // Ensure both mixer lists are loaded
    if (scamMixers.length === 0 || legitMixers.length === 0) {
        await ensureScamWhammerLists();
        if (scamMixers.length === 0 || legitMixers.length === 0) {
            setScamWhammerStatus(
                scamWhammerText(
                    'Unable to check URL due to failure in loading mixer lists.',
                    'Невозможно проверить URL: списки миксеров не загрузились.'
                ),
                'error'
            );
            return;
        }
    }

    // Check if the domain is in the scam list
    if (scamMixers.includes(domain)) {
        setScamWhammerStatus(
            scamWhammerText(
                `Warning: ${domain} is a known SCAM or SEIZED crypto mixer. Avoid using this service!`,
                `Внимание: ${domain} известен как СКАМ или ИЗЪЯТЫЙ криптомиксер. Не используйте этот сервис!`
            ),
            'error'
        );
    }
    // Check if the domain is in the legit list
    else if (legitMixers.includes(domain)) {
        setScamWhammerStatus(
            scamWhammerText(`${domain} is a LEGITIMATE crypto mixer.`, `${domain} — ЛЕГИТИМНЫЙ криптомиксер.`),
            'success'
        );
    }
    // If not found in either list
    else {
        setScamWhammerStatus(
            scamWhammerText(
                `The URL ${domain} is not recognized in our database. Exercise caution and verify its legitimacy before using.`,
                `URL ${domain} не найден в нашей базе. Соблюдайте осторожность и проверьте его перед использованием.`
            ),
            'warning'
        );
    }

    // Clear the input field
    document.getElementById('urlInput').value = '';
}

function setInlineDomainStatus(status, message, type = 'info') {
    if (!status) return;

    status.textContent = message;
    status.className = `vg-status vg-status--${type}`;
    status.setAttribute('role', type === 'error' ? 'alert' : 'status');
    status.setAttribute('aria-live', type === 'error' ? 'assertive' : 'polite');
    status.hidden = false;
}

function setupInlineOfficialDomainChecks() {
    document.querySelectorAll('[data-service-domain-check]').forEach(function(form) {
        const input = form.querySelector('[data-domain-input]');
        const status = form.querySelector('[data-domain-status]');
        let officialDomains = [];

        try {
            officialDomains = JSON.parse(form.getAttribute('data-official-domains') || '[]')
                .map(normalizeScamWhammerDomain)
                .filter(Boolean);
        } catch (error) {
            officialDomains = [];
        }

        if (!input || !status || officialDomains.length === 0) {
            return;
        }

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const domain = normalizeScamWhammerDomain(input.value);

            if (!domain) {
                setInlineDomainStatus(
                    status,
                    scamWhammerText('Enter a domain to check.', 'Введите домен для проверки.'),
                    'error'
                );
                return;
            }

            if (officialDomains.includes(domain)) {
                setInlineDomainStatus(status, scamWhammerText('Official domain', 'Официальный домен'), 'success');
            } else {
                setInlineDomainStatus(status, scamWhammerText('Not an official domain', 'Не официальный домен'), 'error');
            }
        });
    });
}

if (document.getElementById('scamwhammer')) {
    ensureScamWhammerLists();
}

setupInlineOfficialDomainChecks();
