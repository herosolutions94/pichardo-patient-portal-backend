const mysql = require('mysql2/promise');

const dbConnect = {
  host: 'localhost',
  user: 'root',
  database: 'rentaro',
  password: 'W2v^3qNIB%Ie2SAX',
};
// const dbConnect = {
//   host: 'localhost',
//   user: 'root',
//   database: 'herosol_rentro',
//   password: '',
// };

const pool = mysql.createPool(dbConnect);  // Using connection pool

module.exports = pool;
