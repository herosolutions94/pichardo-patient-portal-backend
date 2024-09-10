const pool = require('../../config/database');
const { doEncode } = require('../../utils/helper');

const users = []; // Global users array

async function fetchData(tableName, whereArray = []) {
  try {
    let query = `SELECT * FROM ??`;
    let params = [tableName];

    if (whereArray.length > 0) {
      query += ` WHERE `;
      const conditions = whereArray.map(() => `?? = ?`);
      query += conditions.join(' AND ');

      whereArray.forEach(cond => {
        params.push(cond.column); // Column name
        params.push(cond.value);  // Column value
      });
    }

    const [results] = await pool.query(query, params);
    return results;
  } catch (error) {
    console.error(`Error fetching data from ${tableName}:`, error.message);
    throw error;
  }
}

async function insertData(tableName, data) {
  try {
    const [result] = await pool.query(`INSERT INTO ?? SET ?`, [tableName, data]);
    return result;
  } catch (error) {
    console.error(`Error inserting data into ${tableName}:`, error.message);
    throw error;
  }
}

const registerUser = (userId, socketId) => {
  const existingUser = users.find(user => user.user_id === userId && user.socket === socketId);
// console.log(userId)
  if (!existingUser) {
    const user = { user_id: userId, socket: socketId };
    users.push(user);
    // console.log('Users:', users);
  } else {
    // console.log('User already registered:', existingUser);
  }
};
const receiveMessage = (messageData, io) => {
  console.log("called", users)
  users.forEach((user) => {
    if (parseInt(user.user_id) === parseInt(messageData?.receiverId)) {
      console.log("true");
      io.to(user.socket).emit("receive-message", {
        messageId: messageData?.messageId,
        msg: messageData.message,
        senderId: messageData.senderId,
        messageDate: messageData?.currentTime,
        convoId: messageData?.convoId,
        encoded_id: messageData?.convoId,
        user_thumb: messageData?.senderDp,
        user_name: messageData?.senderName,
        files: messageData.files,
        msg_type: "you"
      });
    }
  });
}
const postReceivedNotification = (data, io) => {
  console.log("model data",data,users)
  users.forEach((user) => {
    if (parseInt(user.user_id) === parseInt(data?.mem_id)) {
      console.log("true");
      io.to(user.socket).emit("receive-notification", data);
    }
  });
  // io.emit('notification', data);
}
const postReceiveMessageNotification = (data, io) => {
  console.log("model data",data,users)
  users.forEach((user) => {
    if (parseInt(user.user_id) === parseInt(data?.mem_id)) {
      console.log("true");
      io.to(user.socket).emit("receive-message-notification", data);
    }
  });
  // io.emit('notification', data);
}

module.exports = { fetchData, insertData, registerUser, receiveMessage, users,postReceiveMessageNotification,postReceivedNotification };
