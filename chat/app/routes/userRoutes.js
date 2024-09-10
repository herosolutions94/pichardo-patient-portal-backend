const express = require('express');
const router = express.Router();
const { handleRegisterUser, handleSendMessage,handleReceiveMessageNotification,handleReceiveNotification } = require('../controllers/userController');

const setupUserRoutes = (io,app) => {
  io.on('connection', (socket) => {
    console.log('a user connected.......');

    socket.on('registerUser', (data) => handleRegisterUser(socket, data));
    socket.on("send-message", function (data, callback) {
      handleSendMessage(data, io);
    });
    socket.on('disconnect', () => {
      console.log('user disconnected......');
    });
  });
  app.post('/receive-notification', (req, res) => {
    const data = req.body;
    console.log("route",data)
    const result =  handleReceiveNotification(data,io);
     console.log(result)
    
    res.sendStatus(200); 
  });
  app.post('/receive-message-notification', (req, res) => {
    const data = req.body;
    const result =  handleReceiveMessageNotification(data,io);    
    res.sendStatus(200); 
  });
};

module.exports = { setupUserRoutes };
