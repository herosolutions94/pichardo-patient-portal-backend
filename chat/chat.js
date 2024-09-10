const express = require('express');
const https = require('http');
const fs = require('fs');
const socketIO = require('socket.io');
var mysql = require("mysql");
var mysql2 = require('mysql2/promise');
var crypto = require("crypto");
const { send } = require("process");

const app = express();

// Read SSL certificate and key
// const options = {
//   key: fs.readFileSync('/etc/pki/tls/certs/staging_rentaro.key'),
//   cert: fs.readFileSync('/etc/pki/tls/certs/staging_rentaro.crt')
// };
app.use(express.urlencoded());

app.use(express.json());


// Create HTTPS server
const server = https.createServer(app);
// const server = https.createServer(options, app);
const io = socketIO(server, {

  cors: {
    origin: '*',
  },
  methods: ["GET", "POST"]


});

server.listen(3002, () => {
  console.log('Socket.io server running on port 3002 with HTTPS');
});




var dbConnect = {
  host:'localhost', user: 'root', database: 'herosol_rentro' , password : ""
}


var con = mysql.createConnection({
  host: dbConnect.host,
  user: dbConnect.user,
  password: dbConnect.password,
  database: dbConnect.database,
});



con.connect(function (err) {
  if (err) throw err;
});


function laravelDecrypt(token, base64Key) {
  // Convert the base64 key to a buffer
  const key = Buffer.from(base64Key, 'base64');

  // Decode the base64 token
  const jsonString = Buffer.from(token, 'base64').toString('utf8');
  const json = JSON.parse(jsonString);

  // Extract the IV, encrypted value, and MAC
  const iv = Buffer.from(json.iv, 'base64');
  const encryptedValue = Buffer.from(json.value, 'base64');

  // Decrypt the value using AES-256-CBC
  const decipher = crypto.createDecipheriv('aes-256-cbc', key, iv);
  let decrypted = decipher.update(encryptedValue, 'base64', 'utf8');
  decrypted += decipher.final('utf8');
  if(decrypted){
    return decrypted.split("-")
  }
  else{
    return false;
  }
  
}
const base64Key = 'QIPd+QBtdoqOTIh6pxd90I+H7aK7qyuV3qrxM3lSYLw=';



const users=[]
io.on('connection', (socket) => {
  console.log('a user connected.......');
  
  socket.on("registerUser", (data) => {
    token = data.userId;
    try {
      const token_arr = laravelDecrypt(token, base64Key);
      if(token_arr?.length > 0){
        var obj = {
          user_id: token_arr[0],
          socket: socket.id,
        };
        users.push(obj);
        console.log(users);
      }
     
    } catch (error) {
      console.error('Decryption failed:', error.message);
    }
    
  });


  socket.on('disconnect', () => {
    console.log('user disconnected......');
  });
});


var iv = "K8ULM7TS36HMMECG";

