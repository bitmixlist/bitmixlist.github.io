
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
    kg8A3Z1Q9Zp60XWqDyy1XRg2Gg==
    =uc5H
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