
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
    "1HsM2JbyKnqwcYvEm1kLMNwJtqb6uxSczd",
    cleanPGPKey(`-----BEGIN PGP PUBLIC KEY BLOCK-----

    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
    R450ZlKgrSUKbGo=
    =HeGd
    -----END PGP PUBLIC KEY BLOCK-----`),
    cleanPGPKey(`-----BEGIN PGP PUBLIC KEY BLOCK-----

    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
    TuA=
    =hrrW
    -----END PGP PUBLIC KEY BLOCK-----`),
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
    'royalmix.io': { type: 'pgp', keyIndex: 4 },
    'jokermix.to': { type: 'pgp', keyIndex: 5 },
    'mixer.money': { type: 'pgp', keyIndex: 0 },
    'mixtum.io': { type: 'pgp', keyIndex: 0 },
    'webmixer.io': { type: 'pgp', keyIndex: 0 },
    'mixerdream.com': { type: 'pgp', keyIndex: 0 },
    'thormixer.io': { type: 'pgp', keyIndex: 0 },
    'mixy.money': { type: 'pgp', keyIndex: 0 },
    'mixtura.money': { type: 'pgp', keyIndex: 0 },
    'bitmixer.online': { type: 'pgp', keyIndex: 0 },
    'swamplizard.io': { type: 'pgp', keyIndex: 0 },
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

function replaceExceptFirst(str, search, replace) {
    const firstIndex = str.indexOf(search);
    if (firstIndex === -1) {
        return str;
    }
    const beforeFirst = str.slice(0, firstIndex + search.length);
    const afterFirst = str.slice(firstIndex + search.length);
    const afterFirstReplaced = afterFirst.replace(new RegExp(search, 'g'), replace);
    return beforeFirst + afterFirstReplaced;
}

function replaceBeforePGPSignature(str, search, replace) {
    const pgpSignature = "BEGIN PGP SIGNATURE";
    const pgpIndex = str.indexOf(pgpSignature);
    if (pgpIndex === -1) {
        return replaceExceptFirst(str, search, replace);
    }
    const beforePGP = str.slice(0, pgpIndex);
    const afterPGP = str.slice(pgpIndex);
    const replacedBeforePGP = replaceExceptFirst(beforePGP, search, replace);
    return replacedBeforePGP + afterPGP;
}

function replaceExceptFirst(str, search, replace) {
    const firstIndex = str.indexOf(search);
    if (firstIndex === -1) {
        return str;
    }
    const beforeFirst = str.slice(0, firstIndex + search.length);
    const afterFirst = str.slice(firstIndex + search.length);
    const afterFirstReplaced = afterFirst.replace(new RegExp(search, 'g'), replace);
    return beforeFirst + afterFirstReplaced;
}

// Event listener for the verification button
document.getElementById('verifyButton').addEventListener('click', function() {
    try {
        const selectedMixer = document.getElementById('mixerSelect').value;4
        const mixerInfo = mixerDetails[selectedMixer];
        let message = document.getElementById('messageTextArea').value;

        if (!mixerInfo || mixerInfo.type === 'none') {
            alert('No verification method available for this mixer.');
            return;
        }

        if (selectedMixer === 'royalmix.io') {
            // Remove blank lines in old royalmix message format
            message = replaceBeforePGPSignature(message, '\n\n', '\n')
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