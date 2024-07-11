
function cleanPGPKey(rawKey) {
    // Split the key into lines
    let lines = rawKey.split('\n');
    
    // Filter out empty lines and trim each line
    lines = lines.map(line => line.trim()).filter(line => line !== "");

    // Reassemble the key ensuring proper formatting
    let cleanedKey = lines.map((line, index) => {
        // Add a blank line after the BEGIN block
        if (line.includes('BEGIN PGP PUBLIC KEY BLOCK')) {
            return line + '\n';
        }
        // Ensure the END block is prefixed with a newline
        else if (line.includes('END PGP PUBLIC KEY BLOCK')) {
            return '\n' + line;
        }
        return line;
    }).join('\n');

    return cleanedKey;
}

// Key or Bitcoin address data array
const keys = [
    cleanPGPKey(`-----BEGIN PGP PUBLIC KEY BLOCK-----
    mQENBFtpbgYBCADJXNcLWrwxk4gRl9/DHeMpPVRqkALs10lM8YTxgpdGIYJHeDIs
    1hIo8ZnACF2XR1cvNEuL2ZcwFpWfDGzIVSc5BzS6t+nCcxuEepbpajVrRN+dPnRX
    LJT2ULiyrv+2B4+Ok/zgZ0JgM9FSDrl787MLpCDlTUksrQRqFVmhGrgyLiFyqf1G
    HEoSdMtz7pRIw5VLURhtjwwG9df0SiaVl2tH7lEK1WA+n0P4uGr1zU8J7YDB+09E
    4CpQ43cuT6S2bEnXO+0vbH9crxhN+ej12+FvtUSUPF3fK9z9miO9ccChCdT0ZKgM
    z99b8I5DgQ4ReCZIfkqeiDQoXixElf+LxjNdABEBAAG0H0phbWJsZXIuaW8gPHN1
    cHBvcnRAamFtYmxlci5pbz6JAU4EEwEIADgWIQS4pc/K9j/y2DhKaxLTsoCVbw58
    rwUCW2luBgIbAwULCQgHAgYVCgkICwIEFgIDAQIeAQIXgAAKCRDTsoCVbw58r4ry
    B/4jLxmyeHNLih7NeDhiYiQ6P0ifen1ly5Yj+hJl+iM4WLXzUG7P8rWDVWoTnkgR
    5McwxeH37kiUPnRfQSuE2v3k0flCzTtTQJN4Wx0FqWuDdgrU5A+YCRzf3CPL8g82
    dcaUacqs7u0EnQ4xE1TwTjRwEH+Jc/mVGuLzVr+KSk1AaCXrMyLCelBeTwP1jjsA
    5GSjCyi4iUzrCdD5MSStcuqJm86btrWto9E0E+EIyMr7LRZoa7Vea+zNeCzR7wJK
    w2KzMlFA619FSw2J5Od/ppQnd5gsawy3WSjH9ntOvq5MT/TXOXiCnw1QVbGEfllc
    qe7+H3/HBGil/wIXqmV8/YiVuQENBFtpbgYBCADCSfwnd2nUesiUnIUEn1BYeexV
    gfZqwmvYrI8yYoP8ITFhCY05TyZWTWnkUs5I9YsDj5ZD49itqTt4+NV6oPAZ7hYG
    hxRF+rzw9hown9nULJhNS3CMHS8xGQjDwWaxnKlVcyX0X+r4kI93G0iyGCyG80z5
    7K8q7tuEd/mjdOLuSeXf3HKrpluCg/OjvW0u2qIDHqrMCm3mNgsBhwVal8rACALr
    C22FbVHzH5mTfhDQb0eCeVa/S3RdWR/gIR2Xk3u41C4UfXmEFaSLPh2+Sk2vghjk
    /RVbFn5p9GFfjKOUuL4Lxh5FAifNQBZHPin068G2uyMLidrCmxTZ9+4K2b9pABEB
    AAGJATYEGAEIACAWIQS4pc/K9j/y2DhKaxLTsoCVbw58rwUCW2luBgIbDAAKCRDT
    soCVbw58r50IB/9lV+sDo9AoBf1vXilR6AqNI+g+qzF9QLAm6OSJjoHe7M39lteY
    ERYy09advtisDVfgU1OGmhssoOZlfzf0EKyRp/YZ5r742X7alaxMiXOpIqTc1HBE
    HSvkd5f48ujSGlOWTVySCP+RaS6dA+Zf3kwTfIe2SamU5Xo4rSIBIcb68S5oksyS
    q9Xj46RwDKMkxUxfMY9gWzw9bKzFvNi/KQ0KyoFf95lglTo4EH1GZHL1s67st/KQ
    b98iNCS0kvqNg4JIB9H4yjzwf8eY1BtiOU0kjWmSu+7ryLLevIaPGs4BZ8TUWwK7
    RS0HUHz1rAugGKJfrOQvfr8dx+QKy/XDlQrB
    -----END PGP PUBLIC KEY BLOCK-----`),
    "1CrywjDEzzpEMxdWzCDgtmZ3Tr57XrnANV",
    "1AnonyMix35XkzRusC7FAzwi9KKggnyg5b",
    "1HsM2JbyKnqwcYvEm1kLMNwJtqb6uxSczd"
    // Add other keys or addresses as necessary
];

function extractBitcoinSignedMessage(data, defaultAddress = '') {
    // Regex to extract message
    const messageRegex = /-----BEGIN BITCOIN SIGNED MESSAGE-----\s*([\s\S]*?)\s*-----BEGIN SIGNATURE-----/;
    const messageMatch = data.match(messageRegex);
    const body = messageMatch ? messageMatch[1].trim() : '';

    // Regex to extract signature
    const signatureRegex = /-----BEGIN SIGNATURE-----\s*([\s\S]*?)\s*-----END BITCOIN SIGNED MESSAGE-----/;
    const signatureMatch = data.match(signatureRegex);
    const signature = signatureMatch ? signatureMatch[1].trim() : '';

    // Attempt to extract address from the content above the signature
    let addressRegex = /Address:\s*(\S+)/;
    let addressMatch = data.match(addressRegex);
    const address = addressMatch ? addressMatch[1].trim() : defaultAddress;

    return { body, signature, address };
}

// Detailed object for each mixer including the type of verification and the index to keys array
const mixerDetails = {
    'whir.to': { type: 'none' },
    'royalmix.io': { type: 'none' },
    'mixer.money': { type: 'pgp', keyIndex: 0 },
    'mixtum.io': { type: 'pgp', keyIndex: 0 },
    'webmixer.io': { type: 'pgp', keyIndex: 0 },
    'mixerdream.com': { type: 'pgp', keyIndex: 0 },
    'thormixer.io': { type: 'pgp', keyIndex: 0 },
    'mixy.money': { type: 'pgp', keyIndex: 0 },
    'mixtura.money': { type: 'pgp', keyIndex: 0 },
    'bitmixer.online': { type: 'pgp', keyIndex: 0 },
    'coinomize.biz': {
        type: 'bitcoin',
        keyIndex: 1,
        customHandler: function(message) {
            return extractBitcoinSignedMessage(message, '1CrywjDEzzpEMxdWzCDgtmZ3Tr57XrnANV');
        }
    },
    'anonymizer.com': {
        type: 'bitcoin',
        keyIndex: 2,
        customHandler: function(message) {
            const address = message.match(/-----START SIGNING BITCOIN ADDRESS-----(.*?)-----END SIGNING BITCOIN ADDRESS-----/s)[1].trim();
            const body = message.match(/-----START LETTER OF GUARANTEE-----(.*?)-----END LETTER OF GUARANTEE-----/s)[1].trim();
            const signature = message.match(/-----START DIGITAL SIGNATURE-----(.*?)-----END DIGITAL SIGNATURE-----/s)[1].trim();
            return { body, signature, address };
        }
    },
    'mixero.io': {
        type: 'bitcoin',
        keyIndex: 3,
        customHandler: function(message) {
            const signature = message.match(/-----START SIGNATURE-----(.*?)-----END SIGNATURE-----/s)[1].trim();
            const body = message.match(/-----START LETTER OF GUARANTEE-----(.*?)-----END LETTER OF GUARANTEE-----/s)[1].trim();
            const address = message.split('\n')[0].split(' ')[8];
            return { body, signature, address };
        }
    },
};

function vrVerify(vrMsg) {
    if (!vrMsg)
        return;

    var addr = null;

    vrAddr = vrMsg.address;
    vrSig = vrMsg.signature;
    vrMsg = vrMsg.body;

    
    const result = btcMsgVerifier.verifyMessage(vrAddr, vrSig, vrMsg);
    
    if (result) {
        addr = vrAddr;
    }
    return addr

}

// Event listener for the verification button
document.getElementById('verifyButton').addEventListener('click', function() {
    try {
        const selectedMixer = document.getElementById('mixerSelect').value;4
        const mixerInfo = mixerDetails[selectedMixer];
        const message = document.getElementById('messageTextArea').value;

        if (!mixerInfo || mixerInfo.type === 'none') {
            alert('No verification method available for this mixer.');
            return;
        }

        let verificationData;
        if (mixerInfo.customHandler) {
            verificationData = mixerInfo.customHandler(message);
        } else {
            verificationData = {
                body: message,
                address: keys[mixerInfo.keyIndex]
            };
        }

        if (mixerInfo.type === 'pgp') {
            const publicKey = keys[mixerInfo.keyIndex];
            
            options = {
                message: window.openpgp.cleartext.readArmored(verificationData.body), // parse armored message
                publicKeys: window.openpgp.key.readArmored(publicKey).keys   // for verification
            };
            window.openpgp.verify(options).then(function(verified) {
                validity = verified.signatures[0].valid; // true
                if (validity) {
                    alert('Genuine letter of guarantee. Fingerprint: ' + verified.signatures[0].keyid.toHex().toUpperCase());
                }
                else {
                    alert('Invalid letter of guarantee!');
                }
            }).catch(error => {
                console.error('An error occured during PGP verification:', error);
                alert('an error occured during PGP verification.');
            });
        } else if (mixerInfo.type === 'bitcoin') {
            const isValid = vrVerify(verificationData);
            if (isValid) {
                alert('Genuine letter of guarantee. Address: ' + verificationData.address);
            } else {
                alert('Invalid letter of guarantee!');
            }
        }
    }
    catch (error) {
        console.error('Verification failed:', error);
        alert('Verification failed.');
    }
});