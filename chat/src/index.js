

import express from 'express';
import https from 'https';
import http from 'http';
import { fileURLToPath } from 'url';
import { dirname, join } from 'path';
import fs from 'fs';
import chatController from "./controllers/chatController.js";
import { Server } from "socket.io";
import cors from "cors";
import bodyParser from "body-parser";

const app = express();
const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);


// Configure your routes and middleware
app.get('/', (req, res) => {
    res.send('Hello, HTTPS!');
});

// Read the SSL certificate and private key files
const privateKey = fs.readFileSync('/home/ssl/privkey.pem', 'utf8');
const certificate = fs.readFileSync('/home/ssl/fullchain.pem', 'utf8');

// Create the HTTPS server


//const privateKey = fs.readFileSync('/home/ssl/privkey.pem', 'utf8');/
//const certificate = fs.readFileSync('/home/ssl/fullchain.pem', 'utf8');
const credentials = { key: privateKey, cert: certificate };
const httpsServer = https.createServer(credentials, app);
//let httpsServer = http.createServer(app);

// app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

app.set("view engine", "ejs");
let pathUrl = join(__dirname, "views");

app.set("views", pathUrl);

app.get("/messages/:senderId/:receiverId", (req, res) => {
    console.log('test');
    res.render("room", { senderId: req.params.senderId, receiverId: req.params.receiverId });
});


/*app.get("/group/:senderId/:groupId", (req, res) => {
    res.render("grouproom", { senderId: req.params.senderId, groupId: req.params.groupId });
});

app.get("/separate/:senderId/:type", (req, res) => {
    res.render("separate", { senderId: req.params.senderId, type: req.params.type });
});*/

/* app.get("/createqrcode", async (req, res) => {
  
   let fileName="WYJ7WNVD_1695023129685.png";
  
   let output=await chatController.uploadFileToS3(fileName,fileName)
   
}); */

/*app.get("/sendemailtest", async () => {

    //("------------------------------------------------------------------");
    const res = await chatController.sendEmailSendgrid("123");


});
app.get("/testdbconnection", async (req, res) => {

    //("------------------------------------------------------------------");
    const result = await chatController.testdbconnection("123");
    res.send(result)
    console.log(result)

});*/

const io = new Server(httpsServer, {
    cors: {
        origin: '*',
    }
});
let userIdArray = []
let roomWiseData = [];
let userListArray = [];
let joinRoom = [];
let newMessageArray = [];
let connectArray = [];
let groupDetailArray = [];
let joinGroupRoom = [];
let NEWMESSAGEGROUPArray = [];
let chatDetailArray = [];
let timeForNewRequest = 1;
io.on('connection', (socket) => {

    socket.on('CONNECT', async (params) => {

        console.log(params.senderId, '-----------------');
        const result = await chatController.onlineOrOffline(params.senderId, "online", socket.id);
        io.to(socket.id).emit('CONNECT_RESPONSE', result)
    });


    socket.on('THREADS_LIST', async (params) => {
        // params = JSON.parse(params);
        const result = await chatController.threadList(params.senderId, (params.search) ? params.search : '');
        io.to(socket.id).emit('THREADS_LIST_RESPONSE', result)
    });

    socket.on('CHAT_LIST', async (params) => {
        // params = JSON.parse(params);
        const result = await chatController.chatList(params.senderId, params.roomId, params.page);
        socket.join('Room_' + params.roomId);
        io.to(socket.id).emit('CHAT_LIST_RESPONSE', result)
    });

    socket.on('SEND_MESSAGE', async (params) => {
        //params = JSON.parse(params);
        const result = await chatController.sendMessage(params.senderId, params.receiveId, params.roomId, params.message, params.messageType, params.messageFile, params.tipId);

        io.to('Room_' + params.roomId).emit('SEND_MESSAGE_RESPONSE', result)

        const result1 = await chatController.getUser(params.receiveId);
        let data = JSON.parse(result1);
        if (data.status == true) {
            const result2 = await chatController.threadList(params.receiveId, '');
            io.to(data.data.data.socket_id).emit('THREADS_LIST_RESPONSE', result2)
        }


        const result3 = await chatController.getUser(params.senderId);
        let data1 = JSON.parse(result3);
        if (data1.status == true) {
            const result3 = await chatController.threadList(params.senderId, '');
            io.to(data1.data.data.socket_id).emit('THREADS_LIST_RESPONSE', result3)
        }



    });

    socket.on('READ_MESSAGE', async (params) => {
        //params = JSON.parse(params);
        const result = await chatController.readMessage(params.senderId, params.roomId, params.messageId, params.type);
        io.to('Room_' + params.roomId).emit('READ_MESSAGE_RESPONSE', result)
    });

    socket.on('DELETE_MESSAGE', async (params) => {
        //params = JSON.parse(params);
        const result = await chatController.deleteMessage(params.senderId, params.roomId, params.messageId, params.type);
        io.to('Room_' + params.roomId).emit('DELETE_MESSAGE_RESPONSE', result)
    });

    socket.on('disconnect', async (senderId) => {
        // let userId = userIdArray[socket.id];
        await chatController.onlineOrOffline(0, "offline", socket.id);
    });


    socket.on('disconnected', () => {
        console.log(1);
        //  let userId = userIdArray[socket.id]

    })//*/
});

// Start the HTTPS server
const port = 3120;
httpsServer.listen(port, () => {
    //   console.log(`HTTPS server is running on port ${port}`);
});