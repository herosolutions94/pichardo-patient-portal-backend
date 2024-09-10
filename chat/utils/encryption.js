const crypto = require('crypto');

function laravelDecrypt(token) {
  // const base64Key = 'base64:NPlPEsjJ10ihpeTG//TdhlgV+FHgKivUbmwzrrAX1yU=';
  //   // Convert the base64 key to a buffer
  //   const key = Buffer.from(base64Key, 'base64');

  //   // Decode the base64 token
  //   const jsonString = Buffer.from(token, 'base64').toString('utf8');
  //   const json = JSON.parse(jsonString);

  //   // Extract the IV, encrypted value, and MAC
  //   const iv = Buffer.from(json.iv, 'base64');
  //   const encryptedValue = Buffer.from(json.value, 'base64');
  //   // Decrypt the value using AES-256-CBC
  //   const decipher = crypto.createDecipheriv('aes-256-cbc', key, iv);
  //   let decrypted = decipher.update(encryptedValue, 'base64', 'utf8');
  //   decrypted += decipher.final('utf8');
  //   return decrypted;
  // const base64Key = 'base64:QIPd+QBtdoqOTIh6pxd90I+H7aK7qyuV3qrxM3lSYLw=';
  const base64Key = 'base64:8LVByBIzbgTqVvvEXUAHBZ6NhyS5mGEHKRiIdvVAjoE=';
  
  // Remove the 'base64:' prefix from the key
  const keyString = base64Key.replace('base64:', '');
  
  // Convert the base64 key to a buffer
  const key = Buffer.from(keyString, 'base64');

  // Decode the base64 token
  const jsonString = Buffer.from(token, 'base64').toString('utf8');
  const json = JSON.parse(jsonString);

  // Extract the IV and encrypted value
  const iv = Buffer.from(json.iv, 'base64');
  const encryptedValue = Buffer.from(json.value, 'base64');

  // Decrypt the value using AES-256-CBC
  const decipher = crypto.createDecipheriv('aes-256-cbc', key, iv);
  let decrypted = decipher.update(encryptedValue, 'base64', 'utf8');
  decrypted += decipher.final('utf8');
  
  return decrypted;
}

// const encryptedToken = 'eyJpdiI6IjV4bGU2NXZZUWJNQnd2djU3cWt0VGc9PSIsInZhbHVlIjoiZ3hpbm9RMzFHQnQvSXIxWFp3VmVHd1pXaEVXaUdidzlvcDVNbEIwZjE0VnFlUzNvd0lMRk9QUHNMSnlmNXdaRCIsIm1hYyI6Ijk0ZjNlODM3NjIwZjg0ZmExYWMzZmVkODNmYmJlNmY1MDcwZjQ2MzBkY2I2MzIwYjE2NDgyOTdmYzk4ZDMwNzciLCJ0YWciOiIifQ==';
// const base64Key = '8LVByBIzbgTqVvvEXUAHBZ6NhyS5mGEHKRiIdvVAjoE=';

// try {
//     const decryptedValue = laravelDecrypt(encryptedToken, base64Key);
//     console.log('Decrypted value:', decryptedValue);
// } catch (error) {
//     console.error('Decryption failed:', error.message);
// }

// function laravelDecrypt(token, base64Key='base64:NPlPEsjJ10ihpeTG//TdhlgV+FHgKivUbmwzrrAX1yU=') {
//   const key = Buffer.from(base64Key, 'base64');
//   const jsonString = Buffer.from(token, 'base64').toString('utf8');
//   const json = JSON.parse(jsonString);
//   const iv = Buffer.from(json.iv, 'base64');
//   const encryptedValue = Buffer.from(json.value, 'base64');
//   const decipher = crypto.createDecipheriv('aes-256-cbc', key, iv);
//   let decrypted = decipher.update(encryptedValue, 'base64', 'utf8');
//   decrypted += decipher.final('utf8');
//   if (decrypted) {
//     return decrypted.split('-');
//   } else {
//     return false;
//   }
// }
// const laravelDecrypt = (encryptedData, vbase64Key) => {
//   const base64Key='base64:8LVByBIzbgTqVvvEXUAHBZ6NhyS5mGEHKRiIdvVAjoE='
//   try {
//     // Ensure the key length is 32 bytes (256 bits)
//     const key = Buffer.from(base64Key, 'base64').slice(0, 32); // Extract first 32 bytes for key

//     const iv = Buffer.alloc(16, 0); // Initialization Vector, should match the one used during encryption

//     const decipher = crypto.createDecipheriv('aes-256-cbc', key, iv);

//     let decrypted = decipher.update(encryptedData, 'base64', 'utf8');
//     decrypted += decipher.final('utf8');

//     return JSON.parse(decrypted); // Assuming the data was JSON stringified
//   } catch (error) {
//     console.error('Decryption error:', error.message);
//     throw new Error('Decryption failed');
//   }
// };

module.exports = { laravelDecrypt };
