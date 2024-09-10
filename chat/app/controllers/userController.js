const { laravelDecrypt } = require('../../utils/encryption');
const { getTimeInAEST, doEncode } = require('../../utils/helper');
const { registerUser, fetchData, insertData, receiveMessage,postReceiveMessageNotification,postReceivedNotification } = require('../models/userModel');


const handleRegisterUser = (socket, data) => {
  const token_id = data.userId;
  try {
    const token = laravelDecrypt(token_id);
    if(token){
      const tokenArr=token.split('-');
      // console.log(tokenArr,'token arr')
    if (tokenArr?.length > 0) {
      registerUser(tokenArr[0], socket.id);
    }
    }
    
  } catch (error) {
    console.error('Decryption failed:', error.message);
  }
};
const handleReceiveMessageNotification =  (data,io) => {
  try {
    postReceiveMessageNotification(data,io)
  }
  catch (error) {
    console.error('Error processing notification:', error.message);
    return { success: false, message: error.message };
  }
};
const handleReceiveNotification =  (data,io) => {
  try {
    console.log("controller",data)
    postReceivedNotification(data,io)
  }
  catch (error) {
    console.error('Error processing notification:', error.message);
    return { success: false, message: error.message };
  }
};
const handleSendMessage = async (data, io) => {
  let messageData = data;
  try {
    const conversation = await fetchData('tbl_conversations', [{ column: 'id', value: messageData.conversationId }]);
    if (conversation.length === 0) throw new Error('Conversation not found');

    let receiverId = parseInt(conversation[0].receiver);
    let senderId = parseInt(conversation[0].sender);

    if (receiverId === parseInt(messageData.senderId)) {
      receiverId = senderId;
      senderId = parseInt(conversation[0].receiver);
    }

    const currentTime = getTimeInAEST();
    const msgData = {
      c_id: messageData.conversationId,
      sender: senderId,
      msg: messageData.message,
      status: 'sent',
      message_by: senderId,
      receiver: receiverId,
      created_at: currentTime,
    };

    const addMessage = await insertData('tbl_msgs', msgData);
    const messageId = addMessage.insertId;
    const files = messageData?.files;

    if (files?.length > 0) {
      for (const file of files) {
        const fileData = { msg_id: messageId, name: file?.file_name, file_name: file?.image_name };
        await insertData('tbl_msg_attachments', fileData);
      }
    }

    const userData = await fetchData('tbl_members', [{ column: 'id', value: senderId }]);

    if (userData.length === 0) throw new Error('User not found');

    const senderInfo = userData[0];
    // console.log(senderInfo)
    let usersMessageData = {
      messageId: messageId,
      // encoded_id: doEncode(messageId),
      message: messageData.message,
      senderId: messageData.senderId,
      receiverId: receiverId,
      messageDate: currentTime,
      convoId: doEncode(messageData.conversationId.toString()),
      senderDp: senderInfo.mem_image,
      senderName: senderInfo.mem_fullname,
      files: messageData.files,
      currentTime: currentTime
    }
    console.log(usersMessageData);

    receiveMessage(usersMessageData, io)

  } catch (error) {
    console.error('Error sending message:', error.message);
  }
};

module.exports = { handleRegisterUser, handleSendMessage,handleReceiveMessageNotification,handleReceiveNotification };
