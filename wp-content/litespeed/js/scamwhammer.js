const scamMixersUrl = 'https://gist.githubusercontent.com/ZenulAbidin/511d531980c44051cfafd11b2e3c9dda/raw/75c3d8a34ed0b9c6ee1cb129110a83edafd4951e/scamwhammer-mixers.txt';
const legitMixersUrl = 'https://gist.githubusercontent.com/ZenulAbidin/afb490c0441f29eec0fdc8ceb695a13f/raw/a2ba930bac38b6290f6b36b78e593e5146ab790a/scamwhammer-mixers-good.txt';

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
        alert('Error loading scam mixers list. Please try again later.');
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
        alert('Error loading legit mixers list. Please try again later.');
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
            alert('Invalid URL format. Please enter a valid URL (e.g., anonymixer.com or bitcloak4rkfygal.onion).');
            return;
        }
    }

    // Remove 'www.' prefix if present
    domain = domain.replace(/^www\./, '');

    // Ensure both mixer lists are loaded
    if (scamMixers.length === 0 || legitMixers.length === 0) {
        await Promise.all([loadScamMixers(), loadLegitMixers()]);
        if (scamMixers.length === 0 || legitMixers.length === 0) {
            alert('Unable to check URL due to failure in loading mixer lists.');
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