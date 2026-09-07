
import axios from 'axios';
import FormData from 'form-data';
const baseurl = 'https://telimed.health/api/';
//const baseurl = 'http://localhost/telimed_org/api/';
class ChatController {
    /*  checkRoomId=async (receiver_id, sender_id) => {
         const is_exist = await User_chat_rooms.findOne({
             where: {
                 [Op.or]: [
                     {
                         [Op.and]: {sender_id: sender_id, receiver_id: receiver_id}
                     },
                     {
                         [Op.and]: {sender_id: receiver_id, receiver_id: sender_id}
                     }
                 ],
             },
             order: [
                 ["created_at", "DESC"],
             ],
         });
       
         return JSON.stringify({
             status: true,
             message: "Room already available.",
             data: is_exist,
         });
        
 
     } */

    onlineOrOffline = async (userId, type, id) => {
        let data = new FormData();
        if (type == 'online') {
            data.append('status', 1);
            data.append('socket_id', id);
            data.append('user_id', userId);
        }
        else {
            data.append('user_id', '');
            data.append('status', 0);
            data.append('socket_id', id);
        }
        let config = {
            method: 'post',
            maxBodyLength: Infinity,
            url: baseurl + 'user-on-off',
            headers: {
                'Accept': 'application/json',
            },
            data: data
        };
        const res = await axios.request(config);
        console.log(res.data);
        if (res.data.success == true) {
            return JSON.stringify({
                status: true,
                message: res.data.message,
                data: res.data,
            });
        }
        else {
            return JSON.stringify({
                status: false,
                message: res.data.message,
                data: null,
            });
        }
    }


    threadList = async (userId, search) => {
        let data = new FormData();
        data.append('user_id', userId);
        data.append('search', search);

        let config = {
            method: 'post',
            maxBodyLength: Infinity,
            url: baseurl + 'thread-list',
            headers: {
                'Accept': 'application/json',
            },
            data: data
        };
        const res = await axios.request(config);
        if (res.data.success == true) {
            return JSON.stringify({
                status: true,
                message: res.data.message,
                data: res.data,
            });
        }
        else {
            return JSON.stringify({
                status: false,
                message: res.data.message,
                data: null,
            });
        }
    }

    chatList = async (userId, roomId, page) => {
        let data = new FormData();
        data.append('user_id', userId);
        data.append('convenience_id', roomId);
        data.append('page', page);


        let config = {
            method: 'post',
            maxBodyLength: Infinity,
            url: baseurl + 'chat-detail',
            headers: {
                'Accept': 'application/json',
            },
            data: data
        };
        const res = await axios.request(config);
        if (res.data.success == true) {
            return JSON.stringify({
                status: true,
                message: res.data.message,
                data: res.data,
            });
        }
        else {
            return JSON.stringify({
                status: false,
                message: res.data.message,
                data: null,
            });
        }
    }

    sendMessage = async (userId, receiveId, roomId, message, messageType, messageFile, tipId) => {

        console.log(userId, receiveId, roomId, message, messageType, messageFile, tipId);
        let data = new FormData();
        data.append('user_id', userId);
        data.append('to_id', receiveId);
        data.append('convenience_id', roomId);
        data.append('message', message);
        data.append('type', messageType);
        data.append('file', messageFile);
        data.append('tipe_id', tipId);



        let config = {
            method: 'post',
            maxBodyLength: Infinity,
            url: baseurl + 'send-message',
            headers: {
                'Accept': 'application/json',
            },
            data: data
        };
        const res = await axios.request(config);
        if (res.data.success == true) {
            return JSON.stringify({
                status: true,
                message: res.data.message,
                data: res.data,
            });
        }
        else {
            return JSON.stringify({
                status: false,
                message: res.data.message,
                data: null,
            });
        }
    }

    getUser = async (userId) => {
        let data = new FormData();
        data.append('user_id', userId);

        let config = {
            method: 'post',
            maxBodyLength: Infinity,
            url: baseurl + 'getUser',
            headers: {
                'Accept': 'application/json',
            },
            data: data
        };
        const res = await axios.request(config);
        if (res.data.success == true) {
            return JSON.stringify({
                status: true,
                message: res.data.message,
                data: res.data,
            });
        }
        else {
            return JSON.stringify({
                status: false,
                message: res.data.message,
                data: null,
            });
        }
    }

    readMessage = async (userId, roomId, messageId, type) => {
        let data = new FormData();
        data.append('user_id', userId);
        data.append('convenience_id', roomId);
        data.append('message_id', messageId);
        data.append('type', type);

        let config = {
            method: 'post',
            maxBodyLength: Infinity,
            url: baseurl + 'message-read',
            headers: {
                'Accept': 'application/json',
            },
            data: data
        };

        const res = await axios.request(config);
        if (res.data.success == true) {
            return JSON.stringify({
                status: true,
                message: res.data.message,
                data: res.data,
            });
        }
        else {
            return JSON.stringify({
                status: false,
                message: res.data.message,
                data: null,
            });
        }
    }

    deleteMessage = async (userId, roomId, messageId, type) => {
        let data = new FormData();
        data.append('user_id', userId);
        data.append('convenience_id', roomId);
        data.append('message_id', messageId);
        data.append('type', type);

        let config = {
            method: 'post',
            maxBodyLength: Infinity,
            url: baseurl + 'message-delete',
            headers:
            {
                'Accept': 'application/json',
            },
            data: data
        };

        const res = await axios.request(config);
        if (res.data.success == true) {
            return JSON.stringify({
                status: true,
                message: res.data.message,
                data: res.data,
            });
        }
        else {
            return JSON.stringify({
                status: false,
                message: res.data.message,
                data: null,
            });
        }
    }












    /*createRoom = async (roomId, senderId, receiverId) => {



        const [rows, fields] = await con.execute('SELECT * FROM `hirdles` WHERE ((user_id = ' + senderId + ' and friend_id=' + receiverId + ') or (user_id = ' + receiverId + ' and friend_id=' + senderId + ') )');

        if (rows.length > 0) {
            return JSON.stringify({
                status: true,
                message: "Room already available.",
                data: rows[0],
            });
        }
        else {

            const [recheck, fields1] = await con.execute('SELECT * FROM `hirdles` WHERE user_id = "' + senderId + '" and friend_id="' + receiverId + '"');
            if(recheck.length > 0)
            {
                return JSON.stringify({
                    status: true,
                    message: "Room already available.",
                    data: recheck[0],
                }); 
            }
            const [recheck1, fields2] = await con.execute('SELECT * FROM `hirdles` WHERE user_id = "' + receiverId + '" and friend_id="' + senderId + '" ');
            if(recheck1.length > 0)
            {
                return JSON.stringify({
                    status: true,
                    message: "Room already available.",
                    data: recheck[0],
                });
            }

            const [data, fields] = await con.execute('insert into  `hirdles`(user_id,friend_id,room_id,type) values ( ' + senderId + ' , ' + receiverId + ', ' + roomId + ',"user") ');

        }
        const [rows1, fields1] = await con.execute('SELECT * FROM `hirdles` WHERE ((user_id = ' + senderId + ' and friend_id=' + receiverId + ') or (user_id = ' + receiverId + ' and friend_id=' + senderId + ') )');

        if (rows1) {

            return JSON.stringify({
                status: true,
                message: "Room created successfully.",
                data: rows1[0],
            });
        } else {
            return JSON.stringify({
                status: false,
                message: "Room creating failed.",
            });
        }
    }*/

    /*sendMessage = async (roomId, senderId, receiverId, audioUrl, caption, type) => {
        try {

            const [rows, fields] = await con.execute('SELECT * FROM `block_users` WHERE  user_id=' + senderId + ' and block_user_id=' + receiverId + ' ');
            const [blockrow, fields1] = await con.execute('SELECT * FROM `block_users` WHERE  user_id=' + receiverId + ' and block_user_id=' + senderId + ' ');

            if (rows.length > 0 || blockrow.length > 0) {

                return JSON.stringify({
                    status: false,
                    message: "Blocked User",
                });
            }


            const currentDate = new Date();
            const dateTime = currentDate.toISOString().slice(0, 19);
            let qrcodeName = await this.generateRandomImageName("png");
            qrcodeName = qrcodeName;
            let buggerData = Buffer.from(audioUrl).toString("base64");
            let newUrl = "https://v1.checkprojectstatus.com/hirdle/audioPlayer/" + buggerData;
           /*  await QRCode.toFile(qrcodeName, newUrl, {
                errorCorrectionLevel: 'H', // High error correction level
                type: 'png', // Output format (you can use 'svg', 'pdf', etc.)
                margin: 2, // Margin around the QR code
            });
            let qrcodeUrl = await this.uploadFileToS3(qrcodeName, qrcodeName); */
    // let qrcodeUrl='';


    /* let sql = 'insert into  `user_chats`(room_id,sender_id,receiver_id,type,audio_url,caption,is_read,created_at_timestemp,qrcodeurl) values ( ' + roomId + ',' + senderId + ' , ' + receiverId + ',"' + type + '", "' + audioUrl + '","' + caption + '","0","' + Date.now() + '","' + newUrl + '") ';

     await con.execute(sql);




     await con.execute('update hirdles set last_message="New audio message",last_message_time="' + dateTime + '" , last_message_timestamp="' + Date.now() + '" where room_id=' + roomId + ' and type="user" ');
     const [receiverDetail, fields1d]= await con.execute("select * from users where id='"+receiverId+"'");
    
     let token=receiverDetail[0]['device_token']
     this.sendNotification("New Message Received","New Message Received",token);
     return JSON.stringify({
         status: true,
         message: "Message sent successfully",
         data: [],
     });
 } catch (error) {
     console.error(error.message);
     return JSON.stringify({
         status: false,
         message: "Something went wrong",
     });
 }
}



details = async (roomId, senderId, page = 0, search = '') => {

 let limit = 10;
 let ofset = parseInt(page) * parseInt(limit);
 const currentDate = new Date();

 const dateTime = moment().format('YYYY-MM-DD HH:mm:ss');
 let sql = 'SELECT uc.*,s.name as senderName ,s.image as senderImage,r.name as receiverName ,r.image as receiverImage,r.is_online as receiverOnline,s.is_online as senderOnline FROM `user_chats` as uc left join users as s on s.id=uc.sender_id  left join users as r on r.id=uc.receiver_id  WHERE  room_id=' + roomId;
 if (search) {
     sql = sql + ' and uc.caption like "%' + search + '%" ';
 }
 sql = sql + ' order by id desc limit ' + ofset + ', ' + limit + ' ';

 const [userChats, fields] = await con.execute(sql);

 await con.execute('update user_chats set is_read="1" , read_time="' + dateTime + '",read_timestamp="' + Date.now() + '" WHERE  room_id=' + roomId + ' and receiver_id=' + senderId + ' and is_read="0" ');


 if (userChats.length > 0) {
     let result = [];
     let dateArray = [];
     for (const iterator of userChats) {
         let tempObject = {};
         tempObject = iterator;
         // let currentDate = Date(iterator.created_at);
         let date = moment(iterator.created_at).format('YYYY-MM-DD');;
         // let date = currentDate.toISOString().slice(0, 10);

         if (dateArray.includes(date)) {
             tempObject.chatDate = '';
         }
         else {
             dateArray.push(date);
             tempObject.chatDate = date;
         }
         result.push(tempObject);
     }


     return JSON.stringify({
         status: true,
         message: "Data Available",
         data: result,
     });
 } else {
     return JSON.stringify({
         status: true,
         message: "Data Not Found!",
         data: [],
     });
 }

}
detailsold = async (roomId, senderId, receiverId) => {

 const currentDate = new Date();
 const dateTime = currentDate.toISOString().slice(0, 19);
 let sql = 'SELECT uc.*,s.name as senderName ,s.image as senderImage,r.name as receiverName ,r.image as receiverImage,r.is_online as receiverOnline,s.is_online as senderOnline FROM `user_chats` as uc left join users as s on s.id=uc.sender_id  left join users as r on r.id=uc.receiver_id  WHERE  room_id=' + roomId + ' ';

 const [userChats, fields] = await con.execute(sql);

 await con.execute('update user_chats set is_read="1" , read_time="' + dateTime + '",read_timestamp="' + Date.now() + '" WHERE  room_id=' + roomId + ' and receiver_id=' + senderId + ' and is_read="0" ');


 if (userChats.length > 0) {

     // Group the data by date without considering time
     const groupedData = userChats.reduce((result, item) => {
         const dateOnly = format(new Date(item.created_at), 'yyyy-MM-dd');
         const existingDate = result.find((entry) => entry.date === dateOnly);

         if (existingDate) {
             existingDate.chatList.push(item);
         } else {
             result.push({ date: dateOnly, chatList: [item] });
         }
         return result;
     }, []);

     return JSON.stringify({
         status: true,
         message: "Data Available",
         data: groupedData,
     });
 } else {
     return JSON.stringify({
         status: true,
         message: "Data Not Found!",
         data: [],
     });
 }

}
 

firstUser = async (userId) => {

 const [rows1, fields1]  =  await con.execute('select * FROM users WHERE id=' + userId + ' ');

 return JSON.stringify({
     status: true,
     message: "getuser successfully.",
     data: rows1[0],
 });
}

createGroup = async (roomId, senderId, groupId) => {

 const [rows, fields] = await con.execute('SELECT * FROM `hirdles` WHERE  group_id=' + groupId + ' ');


 if (rows.length > 0) {

     const [rows2, fields] = await con.execute('SELECT * FROM `hirdles` WHERE user_id = ' + senderId + ' and  group_id=' + groupId + ' ');
     if (rows2.length == 0) {
         const [data, fields] = await con.execute('insert into `hirdles` (user_id,group_id,room_id,type) values ( ' + senderId + ' , ' + groupId + ', ' + rows[0].room_id + ',"group") ');
     }
     const [rows1, fields1] = await con.execute('SELECT * FROM `hirdles` WHERE user_id = ' + senderId + ' and group_id=' + groupId + ' ');

     return JSON.stringify({
         status: true,
         message: "Room already available.",
         data: rows1[0],
     });
 }
 else {
     const [data, fields] = await con.execute('insert into `hirdles` (user_id,group_id,room_id,type) values ( ' + senderId + ' , ' + groupId + ', ' + roomId + ',"group") ');

 }
 const [rows1, fields1] = await con.execute('SELECT * FROM `hirdles` WHERE user_id = ' + senderId + ' and group_id=' + groupId + ' ');

 if (rows1) {

     return JSON.stringify({
         status: true,
         message: "Room created successfully.",
         data: rows1[0],
     });
 } else {
     return JSON.stringify({
         status: false,
         message: "Room creating failed.",
     });
 }
}
sendMessageGroup = async (roomId, senderId, groupId, audioUrl, caption, type) => {
 try {
     const currentDate = new Date();
     const dateTime = currentDate.toISOString().slice(0, 19);

     let qrcodeName = await this.generateRandomImageName("png");
     let buggerData = Buffer.from(audioUrl).toString("base64");
     let newUrl = "https://v1.checkprojectstatus.com/hirdle/audioPlayer/" + buggerData;
   /*   await QRCode.toFile(qrcodeName, newUrl, {
         errorCorrectionLevel: 'H', // High error correction level
         type: 'png', // Output format (you can use 'svg', 'pdf', etc.)
         margin: 2, // Margin around the QR code
     });
     let qrcodeUrl = await this.uploadFileToS3(qrcodeName, qrcodeName); *
     const [groupData, fields] = await con.execute('SELECT * FROM `group_members` WHERE group_id="' + groupId + '" and user_id="'+senderId+'" ');
     if(!groupData || groupData.length ==0)
     {
         return JSON.stringify({
             status: false,
             message: "You are not in this group",
         });
     }
     

     let sql = 'insert into  `group_chats`(room_id,sender_id,group_id,type,audio_url,caption,is_read,created_at_timestemp,qrcodeurl) values ( ' + roomId + ',' + senderId + ' , ' + groupId + ',"' + type + '", "' + audioUrl + '","' + caption + '","0","' + Date.now() + '","' + newUrl + '") ';


     await con.execute(sql);

     await con.execute('update hirdles set last_message="New audio message",last_message_time="' + dateTime + '" , last_message_timestamp="' + Date.now() + '" where room_id=' + roomId + ' and type="group" ');
     let updateQuery = 'update `groups` set last_message="New audio message",last_message_datetime="' + dateTime + '", last_message_timestamp="' + Date.now() + '" where id=' + groupId + '  ';


     await con.execute(updateQuery);
     return JSON.stringify({
         status: true,
         message: "Message sent successfully",
         data: [],
     });
 } catch (error) {
     console.error(error.message);
     return JSON.stringify({
         status: false,
         message: "Something went wrong",
     });
 }
}
groupDetails = async (roomId, senderId, receiverId, page, search) => {

 let limit = 10;
 let ofset = parseInt(page) * parseInt(limit);

 const currentDate = new Date();
 const dateTime = currentDate.toISOString().slice(0, 19);
 let sql = 'SELECT gc.*,s.name as senderName ,s.image as senderImage FROM `group_chats` as gc left join users as s on s.id=gc.sender_id  WHERE  room_id=' + roomId + ' ';
 if (search) {
     sql = sql + ' and uc.caption like "%' + search + '%" ';
 }
 sql = sql + ' order by id desc limit ' + ofset + ', ' + limit + ' ';


 const [userChats, fields] = await con.execute(sql);

 // await con.execute('update group_chats set is_read="1" , read_time="'+dateTime+'",read_timestamp="'+Date.now()+'" WHERE  room_id=' + roomId + ' and receiver_id=' + senderId + ' and is_read="0" ');

 


 if (userChats.length > 0) {
     let result = [];
     let dateArray = [];
     for (const iterator of userChats) {
         let tempObject = {};
         tempObject = iterator;
         // let currentDate = Date(iterator.created_at);
         let date = moment(iterator.created_at).format('YYYY-MM-DD');;
         // let date = currentDate.toISOString().slice(0, 10);
         const [membersRow, fields1] = await con.execute('SELECT gm.*,u.name as username FROM `group_members` as gm left join users as u on u.id=gm.user_id WHERE group_id="' + iterator.group_id + '" limit 4');
         let memberName = [];
         for (const iterator1 of membersRow) {
             memberName.push(iterator1.username)
         }
 
         tempObject.members = memberName.join(",");


         if (dateArray.includes(date)) {
             tempObject.chatDate = '';
         }
         else {
             dateArray.push(date);
             tempObject.chatDate = date;
         }
         result.push(tempObject);
     }



     return JSON.stringify({
         status: true,
         message: "Data Available",
         data: result,
     });
 } else {
     return JSON.stringify({
         status: true,
         message: "Data Not Found!",
         data: [],
     });
 }

}
sendMessageSeparete = async (senderId, receiverIds, audioUrl, caption, type, separateMessage) => {
 try {

     let reponseData = [];
     let reponseDataGroup = [];
     for (const iterator of receiverIds) {

         //check room
         let receiverId = iterator.receiverId;
         let userType = iterator.type;
         if (userType == "User") {
             let tempObject = await this.insertUserChat(senderId, receiverId, audioUrl, caption, type)
             reponseData.push(tempObject);
             let receiverDetail= await con.execute("select * from users where id='"+receiverId+"'");
             let token=receiverDetail[0]['device_token']
             this.sendNotification("New Message Received","New Message Received",token);

         }
         else if (userType == "Group") {

             if (separateMessage == "1") {
                 // here receiverId is a group id which come from front end in same parameter but type is group
                 const [rows, fields] = await con.execute('SELECT * FROM `group_members` WHERE group_id="' + receiverId + '" ');
                 for (const iterator1 of rows) {

                     let tempObject = await this.insertUserChat(senderId, iterator1.user_id, audioUrl, caption, type)
                     reponseData.push(tempObject);
                 }
                 let groupTempObject = await this.insertGroupChat(senderId, receiverId, audioUrl, caption, type);
                 reponseDataGroup.push(groupTempObject)

             }
             else {
                 let groupTempObject = await this.insertGroupChat(senderId, receiverId, audioUrl, caption, type);
                 reponseDataGroup.push(groupTempObject)
             }

         }

     }

     return { "user": reponseData, "group": reponseDataGroup };
 } catch (error) {
     return { "User": [], "group": [] };
 }
}
insertUserChat = async (senderId, receiverId, audioUrl, caption, type) => {

 const [rows, fields] = await con.execute('SELECT * FROM `hirdles` WHERE ((user_id = ' + senderId + ' and friend_id=' + receiverId + ') or (user_id = ' + receiverId + ' and friend_id=' + senderId + ') )');
 let roomId = '';
 if (rows.length > 0) {
     roomId = rows[0].room_id;
 }
 else {
     roomId = Math.floor(Math.random() * (9999 - 1000) + 1000) + Date.now();
     const [data, fields] = await con.execute('insert into  `hirdles`(user_id,friend_id,room_id,type) values ( ' + senderId + ' , ' + receiverId + ', ' + roomId + ',"user") ');
     const [rows1, fields1] = await con.execute('SELECT * FROM `hirdles` WHERE ((user_id = ' + senderId + ' and friend_id=' + receiverId + ') or (user_id = ' + receiverId + ' and friend_id=' + senderId + ') )');
     roomId = rows1[0].room_id;
 }
 let buggerData = Buffer.from(audioUrl).toString("base64");
 let newUrl = "https://v1.checkprojectstatus.com/hirdle/audioPlayer/" + buggerData;

 const currentDate = new Date();
 const dateTime = currentDate.toISOString().slice(0, 19);

 let sql = 'insert into  `user_chats`(room_id,sender_id,receiver_id,type,audio_url,caption,is_read,created_at_timestemp,qrcodeurl) values ( ' + roomId + ',' + senderId + ' , ' + receiverId + ',"' + type + '", "' + audioUrl + '","' + caption + '","0","' + Date.now() + '","'+newUrl+'") ';

 await con.execute(sql);

 await con.execute('update hirdles set last_message="New audio message",last_message_time="' + dateTime + '" , last_message_timestamp="' + Date.now() + '" where room_id=' + roomId + ' and type="user" ');
 let tempObject = {};
 tempObject.roomId = roomId;
 tempObject.senderId = senderId;
 tempObject.receiverId = receiverId;
 return tempObject;

}

insertGroupChat = async (senderId, groupId, audioUrl, caption, type) => {

 // check group is joined or not and if not join then join
 const [rows, fields] = await con.execute('SELECT * FROM `hirdles` WHERE  group_id=' + groupId + ' ');
 let roomId = '';
 if (rows.length > 0) {

     const [rows2, fields] = await con.execute('SELECT * FROM `hirdles` WHERE user_id = ' + senderId + ' and  group_id=' + groupId + ' ');
     if (rows2.length == 0) {
         const [data, fields] = await con.execute('insert into `hirdles` (user_id,group_id,room_id,type) values ( ' + senderId + ' , ' + groupId + ', ' + rows[0].room_id + ',"group") ');
     }
     const [rows1, fields1] = await con.execute('SELECT * FROM `hirdles` WHERE user_id = ' + senderId + ' and group_id=' + groupId + ' ');
     roomId = rows1[0].room_id;

 }
 else {
     roomId = Math.floor(Math.random() * (9999 - 1000) + 1000) + Date.now();
     const [data, fields] = await con.execute('insert into `hirdles` (user_id,group_id,room_id,type) values ( ' + senderId + ' , ' + groupId + ', ' + roomId + ',"group") ');

 }

 // send message in group

 const currentDate = new Date();
 const dateTime = currentDate.toISOString().slice(0, 19);
 
 let buggerData = Buffer.from(audioUrl).toString("base64");
 let newUrl = "https://v1.checkprojectstatus.com/hirdle/audioPlayer/" + buggerData;

 let sql = 'insert into  `group_chats`(room_id,sender_id,group_id,type,audio_url,caption,is_read,created_at_timestemp,qrcodeurl) values ( ' + roomId + ',' + senderId + ' , ' + groupId + ',"' + type + '", "' + audioUrl + '","' + caption + '","0","' + Date.now() + '","'+newUrl+'") ';
 await con.execute(sql);

 await con.execute('update hirdles set last_message="New audio message",last_message_time="' + dateTime + '" , last_message_timestamp="' + Date.now() + '" where room_id=' + roomId + ' and type="group" ');
 let updateQuery = 'update `groups` set last_message="New audio message",last_message_datetime="' + dateTime + '", last_message_timestamp="' + Date.now() + '" where id=' + groupId + '  ';
 await con.execute(updateQuery);

 let tempObject = {};
 tempObject.roomId = roomId;
 tempObject.senderId = senderId;
 tempObject.groupId = groupId;
 return tempObject;
}
userList = async (page, userId) => {
 // console.log(userId,"userList")
 let limit = 50;
 let ofset = parseInt(page) * parseInt(limit);
 let query = "select h.*,u.name as userName,u.email as userEmail,u.phone_no as userPhone,u.image as userImage,f.name as friendName,f.email as friendEmail,f.phone_no as friendPhone,f.image as friendImage,g.name as groupname,g.group_image from hirdles as h left join users as u on u.id=h.user_id left join users as f on f.id=h.friend_id left join groups as g on g.id=h.group_id where (h.user_id='" + userId + "' or h.friend_id='" + userId + "')  order by last_message_timestamp DESC limit " + ofset + ", " + limit;
 //and (h.last_message != null or h.last_message != '' )
 if(userId==8)
 {
     console.log(query)
 }
 
 const [rows, fields1] = await con.execute(query);
 let result = [];
 for (const iterator of rows) {
     let tempObject = {};
     tempObject = iterator;
     if (iterator.group_id > 0) {
         const [membersRow, fields] = await con.execute('SELECT gm.*,u.name as username FROM `group_members` as gm left join users as u on u.id=gm.user_id WHERE group_id="' + iterator.group_id + '" limit 4');
         let memberName = [];
         for (const iterator1 of membersRow) {
             memberName.push(iterator1.username)
         }

         tempObject.members = memberName.join(",");
     }
     else {
         tempObject.members = "";
     }
     let unReadMessage = 0;
     if (iterator.type == "user") {
         let senderId = iterator.user_id == userId ? iterator.friend_id : iterator.user_id;
         const [userchat, fields1] = await con.execute("select * from user_chats where sender_id='" + senderId + "' and receiver_id='" + userId + "' and is_read='0'  ");
         if (userchat.length > 0) {
             unReadMessage = 1;
         }
         
        

     }
     else {

         const [userchat, fields1] = await con.execute("select * from group_chats where group_id='" + iterator.group_id + "'  and is_read=0   ");
         if (userchat.length > 0) {
             unReadMessage = 1;
         }
     }
     tempObject.unReadMessage = unReadMessage;

     tempObject.last_message_time = await this.formatDate(moment(Number(iterator.last_message_timestamp)));
     result.push(tempObject);
 }
 // console.log(result, "result")
 if(userId==8)
 {
     console.log(result)
 }
 return JSON.stringify({
     status: true,
     message: "User list",
     data: result,
 });


}
formatDate = async (date) => {

 const now = moment();
 const diffInDays = now.diff(date, 'days');

 if (diffInDays === 0) {
     // Display time if the date is today
     return date.format('LT');
 } /* else if (diffInDays === 1) {
   return 'Yesterday';
 } *else {
     return `${diffInDays} days ago`;
 }
}
generateRandomString = async (length) => {
 let result = '';
 const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

 for (let i = 0; i < length; i++) {
     const randomIndex = Math.floor(Math.random() * characters.length);
     result += characters.charAt(randomIndex);
 }

 return result;
}

// Generate a unique random string for the image name
generateRandomImageName = async (extension) => {
 const randomString = await this.generateRandomString(8); // You can adjust the length as needed
 const timestamp = Date.now();
 return `${randomString}_${timestamp}.${extension}`;
}
uploadFileToS3 = async (filePath, fileName) => {
 try {
     // Read the file from your local filesystem

     // Configure AWS with your credentials
     const awsConfig = {
         accessKeyId: 'AKIAZ3SPCQI4I4LGNSFE',
         secretAccessKey: '2u/gmP/dOC1hAloUKXevniBT1fIGA8LJq9+nWxnj',
         region: 'us-west-1', // e.g., 'us-east-1'
     };

     const s3 = new AWS.S3(awsConfig);

     // Specify the S3 bucket and file name
     const bucketName = 'hirdle';



     const fileContent = await fs.readFile(filePath);

     // Set the S3 upload parameters
     const params = {
         Bucket: bucketName,
         Key: fileName,
         Body: fileContent,
     };

     // Upload the file to S3
     const data = await s3.upload(params).promise();

     return data.Location;
 } catch (err) {
     console.error('Error uploading to S3:', err);
 }
}
sendNotification = async (title,body,token) => {
 const serverKey = 'AAAAEM2SvxE:APA91bHQjRL0H5yc1PMNm55DhXRCkZKZECwfpvYNFbzhWtZFxxVzmGemBMmfnkxjc0sZJBGDDkRojz9kglDSM_mEh-Hzmhl-r0LEsbV-pg_CZSPpTP8hSpCoiHJuT163A54_uecra2w2'; // Replace with your Firebase Cloud Messaging server key
 const fcmUrl = 'https://v1.checkprojectstatus.com/hirdle/api/sendNoticationFromChat';
 
 const message = {
     token:token,
     body:body,
     title:title,
 };

 await axios
     .post(fcmUrl, message)
     .then((response) => {
         console.log('Successfully sent message:', response.data);
     })
     .catch((error) => {
         if (error.response) {
             console.error('Error sending message:', error.response.data);
         } else {
             console.error('Error sending message:', error.message);
         }
     });
}
sendNotificationold = async (title,body,token) => {
 const serverKey = 'AAAAEM2SvxE:APA91bHQjRL0H5yc1PMNm55DhXRCkZKZECwfpvYNFbzhWtZFxxVzmGemBMmfnkxjc0sZJBGDDkRojz9kglDSM_mEh-Hzmhl-r0LEsbV-pg_CZSPpTP8hSpCoiHJuT163A54_uecra2w2'; // Replace with your Firebase Cloud Messaging server key
 const fcmUrl = 'https://fcm.googleapis.com/fcm/send';
 console.log("token",token)
 const message = {
     notification: {
         title: title,
         body: body,
         sound: "notification.mp3",
         android: {
             notification: {
                 sound: 'notification.mp3'
             },
         },
         apns: {
             payload: {
                 aps: {
                     sound: 'notification.mp3'
                 },
             },
         },
     },
     data:{
         sound: "notification.mp3",
     },
     // You can either use 'to' with a device token or 'topic' to send to a topic.
     // Replace 'YOUR_DEVICE_TOKEN' with the actual device token or specify a 'topic'.
     to: token,
     // OR
     // topic: 'your_topic_name',
     sound: "notification.mp3",
 };

 const headers = {
     'Content-Type': 'application/json',
     Authorization: `key=${serverKey}`,
 };

 await axios
     .post(fcmUrl, message, { headers })
     .then((response) => {
         console.log('Successfully sent message:', response.data);
     })
     .catch((error) => {
         if (error.response) {
             console.error('Error sending message:', error.response.data);
         } else {
             console.error('Error sending message:', error.message);
         }
     });
}
getGroupMember= async (groupId)=>{
 const [userlist, fields1] = await con.execute("select * from group_members where group_id='" + groupId + "'  ");
 
 let result=[];
 for (const iterator of userlist) {
     result.push(iterator.user_id);
 }
 
 return result;
}
testdbconnection= async ()=>{
 const [userlist, fields1] = await con.execute("select * from group_members limit 1  ");
 
 let result=[];
 for (const iterator of userlist) {
     result.push(iterator.user_id);
 }
 
 return result;
}
requestCount=async(userId,userRequest,timeForNewRequest)=>{
 if(userRequest[userId]=='')
 {
     userRequest[userId]= new Date();
     return 1;
 }
 else{
     const currentTime = new Date();
     let  timeDifferenceInSeconds = (currentTime - userRequest[userId]) / 1000;
     if(timeDifferenceInSeconds < timeForNewRequest)
     {
         console.log("wrong time")
         return 2;
     }
     else{
         userRequest[userId]=currentTime;
         return 1;
     }
 }
}*/


}

export default new ChatController();
