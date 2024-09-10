var crypto = require("crypto");

const getTimeInAEST = () => {
    const options = { timeZone: 'Australia/Sydney', hour12: false };
    const now = new Date();
    const formattedDate = now.toLocaleString('en-AU', options);

    // Format to database format (assuming YYYY-MM-DD HH:MM:SS)
    const dbFormatDate = now.toISOString().slice(0, 19).replace('T', ' ');

    return dbFormatDate;
};
function doDecode(string, key = 'preciousprotection') {
    let hash = '';
    let hashedKey = crypto.createHash('sha1').update(key).digest('hex');

    let strLen = string.length;
    let keyLen = hashedKey.length;
    let j = 0;

    for (let i = 0; i < strLen; i += 2) {
        let subStr = string.substring(i, i + 2).split('').reverse().join('');
        let ordStr = parseInt(subStr, 36).toString(16);
        ordStr = parseInt(ordStr, 16);

        if (j === keyLen) {
            j = 0;
        }

        let ordKey = hashedKey.charCodeAt(j);
        j++;

        hash += String.fromCharCode(ordStr - ordKey);
    }

    let decodedHash = Buffer.from(hash, 'base64').toString('utf8');
    return decodedHash;
}


function doEncode(string, key = 'preciousprotection') {
    let hash = '';
    let encodedString = Buffer.from(string).toString('base64');
    let hashedKey = crypto.createHash('sha1').update(key).digest('hex');

    let strLen = encodedString.length;
    let keyLen = hashedKey.length;
    let j = 0;

    for (let i = 0; i < strLen; i++) {
        let ordStr = encodedString.charCodeAt(i);
        if (j === keyLen) {
            j = 0;
        }
        let ordKey = hashedKey.charCodeAt(j);
        j++;

        let sum = (ordStr + ordKey).toString(16);
        let converted = parseInt(sum, 16).toString(36);
        hash += converted.split('').reverse().join('');
    }
    return hash;
}
module.exports = { getTimeInAEST, doEncode, doDecode };
