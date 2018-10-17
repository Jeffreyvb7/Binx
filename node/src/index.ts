import * as express from 'express';
import {createServer} from 'http';
import * as sio from 'socket.io';
import {Socket} from "socket.io";
import * as moment from 'moment';
import * as _ from 'underscore';
import * as dotenv from 'dotenv';
import * as path from 'path';

dotenv.config({path: path.resolve('../.env')});

const app = express();
const http = createServer(app);
const io = sio(http);

var knex = require('knex')({
    client: 'mysql',
    connection: {
        host: process.env.DB_HOST,
        user: process.env.DB_USERNAME,
        password: process.env.DB_PASSWORD,
        database: process.env.DB_DATABASE,
        charset: 'utf8'
    }
});

var bookshelf = require('bookshelf')(knex);

var User = bookshelf.Model.extend({
    tableName: 'users',
    chatrooms: function () {
        return this.belongsToMany(Chatroom, 'chatroom_user');
    }
});

var Message = bookshelf.Model.extend({
    tableName: 'chat_messages',
    chatroom: function () {
        return this.belongsTo(Chatroom);
    },
    user: function () {
        return this.belongsTo(User);
    }
});

var Chatroom = bookshelf.Model.extend({
    tableName: 'chatrooms',
    users: function () {
        return this.belongsToMany(User, 'chatroom_user').withPivot('admin');
    },
    messages: function () {
        return this.hasMany(Message);
    }
});

app.get('**', (req, res) => {
    return res.status(403).end();
});

http.listen(process.env.SOCKET_PORT, () => {
    console.log('Server running on port ' + process.env.SOCKET_PORT);
});

io.use(function (socket: Socket, next) {
    if (socket.handshake.query && socket.handshake.query.token) {
        User.where('socket_auth_token', socket.handshake.query.token).fetch({withRelated: ['chatrooms']}).then(function (user) {
            if (user) {
                (socket as any).userId = user.attributes.id;

                user.related('chatrooms').fetch()
                    .then(chatrooms => {
                        chatrooms.forEach(chatroom => {
                            socket.join("chatroom-" + chatroom.attributes.id);
                        });
                    });

                user.set('socket_auth_token', Math.random().toString(36).substr(2, 10));
                user.save();

                console.log("\"" + user.attributes.email + "\" has connected!");
            } else {
                notAuthenticated(socket);
            }

        }).catch(function (err) {
            console.log(err);
            notAuthenticated(socket);
        });
    } else {
        notAuthenticated(socket);
    }
    next();
});

io.on('connection', (socket: Socket) => {

    socket.on('message', (data: any) => {
        if(typeof data.message !== 'undefined' && data.message.length > 0) {
            var userId: number = (socket as any).userId;

            Chatroom.where('id', data.chatId).fetch({withRelated: ['users']}).then(chatroom => {
                if (chatroom) {
                    User.where('id', userId).fetch({withRelated: ['chatrooms']})
                        .then(user => {
                            if (user) {
                                user.related('chatrooms').forEach(cr => {
                                    if (cr.get('id') == data.chatId) {
                                        var msg = new Message();
                                        msg.set('chatroom_id', data.chatId);
                                        msg.set('user_id', userId);
                                        msg.set('message', data.message);
                                        msg.set('created_at', mysqlNow());
                                        msg.set('updated_at', mysqlNow());

                                        msg.save()
                                            .then(message => {
                                                Message.where('id', message.attributes.id).fetch({withRelated: ['user']})
                                                    .then(message => {
                                                        console.log("\"" + user.attributes.email + "\" sent a message to \"" + chatroom.attributes.name + "\"");
                                                        io.to("chatroom-" + data.chatId).emit('message', message);
                                                    });
                                            });
                                    }
                                });
                            }
                        });
                }
            });
        }
    });

    socket.on('newChatroomCreated', (data: number) => {
        Chatroom.where('id', data).fetch({withRelated: ['users']})
            .then(chatroom => {
                if (chatroom) {
                    chatroom.related('users').forEach(user => {
                        var nSocket = _.findWhere(io.sockets.sockets, {userId: user.attributes.id});
                        if (typeof nSocket !== 'undefined') {
                            console.log(socket.id + ' - ' + nSocket.id);
                            if (socket.id !== nSocket.id) {
                                nSocket.emit('newChatroomCreated', chatroom.attributes.id);
                            }
                            nSocket.join('chatroom-' + chatroom.attributes.id);
                        }
                    });
                }
            });
    });

    socket.on('removedUserFromChatroom', data => {
        var uSocket = _.findWhere(io.sockets.sockets, {userId: data.userId});
        io.to("chatroom-" + data.chatroomId).emit('userRemoved', data);

        if (uSocket) uSocket.leave("chatroom-" + data.chatroomId);
    });

    socket.on('toggledUserAdminInChat', data => {
        Chatroom.where('id', data.chatroomId).fetch({withRelated: ['users']})
            .then(chatroom => {
                if (chatroom) {
                    chatroom.related('users').forEach(user => {
                        if(user.attributes.id === data.userId) {
                            io.to('chatroom-' + chatroom.attributes.id).emit('toggledUserAdminInChat', {chatroomId: chatroom.attributes.id, userId: data.userId, admin: !!user.pivot.attributes.admin});
                        }
                    });
                }
            });
    });

    socket.on('addedUsersToChatroom', data => {
        var socketsToNew:Socket[] = [];
        Chatroom.where('id', data.chatroomId).fetch({withRelated: ['users']})
            .then(chatroom => {
                if (chatroom) {
                    chatroom.related('users').forEach(user => {
                        if(data.users.includes(user.attributes.id)) {
                            var uSocket = _.findWhere(io.sockets.sockets, {userId: user.attributes.id});
                            if(uSocket) socketsToNew.push(uSocket);

                            io.to('chatroom-' + data.chatroomId).emit('addedUserToChatroom', {chatroomId: data.chatroomId, userId: user.attributes.id});
                        }
                    });

                    socketsToNew.forEach((socket:any)=> {
                        socket.join("chatroom-" + data.chatroomId);
                        socket.emit('newChatroomCreated', data.chatroomId);
                    });
                }
            });
    });

    socket.on('chatroomNameChanged', chatroomId => {
        Chatroom.where('id', chatroomId).fetch()
            .then(chatroom => {
                if (chatroom) {
                    io.to('chatroom-' + chatroom.attributes.id).emit('chatroomNameChanged', {chatroomId: chatroomId, newName: chatroom.attributes.name});
                }
            });
    });

    socket.on('disconnect', () => {
        var userId: number = (socket as any).userId;

        if (userId) {
            User.where('id', userId).fetch()
                .then(user => {
                    if (user) {
                        console.log("\"" + user.attributes.email + "\" has disconnected!");
                    }
                });
        }
    });
})

function notAuthenticated(socket: Socket) {
    console.log("Failed to authenticate: " + socket.id);
    socket.emit('unauthenticated', {});
    socket.disconnect();
}

function mysqlNow() {
    return moment(Date.now()).format('YYYY-MM-DD HH:mm:ss');
}

setInterval(() => {
}, 50);