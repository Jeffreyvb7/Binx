"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var express = require("express");
var http_1 = require("http");
var sio = require("socket.io");
var moment = require("moment");
var _ = require("underscore");
var dotenv = require("dotenv");
var path = require("path");
dotenv.config({ path: path.resolve('../.env') });
var app = express();
var http = http_1.createServer(app);
var io = sio(http);
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
app.get('**', function (req, res) {
    return res.status(403).end();
});
http.listen(process.env.SOCKET_PORT, function () {
    console.log('Server running on port ' + process.env.SOCKET_PORT);
});
io.use(function (socket, next) {
    if (socket.handshake.query && socket.handshake.query.token) {
        User.where('socket_auth_token', socket.handshake.query.token).fetch({ withRelated: ['chatrooms'] }).then(function (user) {
            if (user) {
                socket.userId = user.attributes.id;
                user.related('chatrooms').fetch()
                    .then(function (chatrooms) {
                    chatrooms.forEach(function (chatroom) {
                        socket.join("chatroom-" + chatroom.attributes.id);
                    });
                });
                user.set('socket_auth_token', Math.random().toString(36).substr(2, 10));
                user.save();
                console.log("\"" + user.attributes.email + "\" has connected!");
            }
            else {
                notAuthenticated(socket);
            }
        }).catch(function (err) {
            console.log(err);
            notAuthenticated(socket);
        });
    }
    else {
        notAuthenticated(socket);
    }
    next();
});
io.on('connection', function (socket) {
    socket.on('message', function (data) {
        if (typeof data.message !== 'undefined' && data.message.length > 0) {
            var userId = socket.userId;
            Chatroom.where('id', data.chatId).fetch({ withRelated: ['users'] }).then(function (chatroom) {
                if (chatroom) {
                    User.where('id', userId).fetch({ withRelated: ['chatrooms'] })
                        .then(function (user) {
                        if (user) {
                            user.related('chatrooms').forEach(function (cr) {
                                if (cr.get('id') == data.chatId) {
                                    var msg = new Message();
                                    msg.set('chatroom_id', data.chatId);
                                    msg.set('user_id', userId);
                                    msg.set('message', data.message);
                                    msg.set('created_at', mysqlNow());
                                    msg.set('updated_at', mysqlNow());
                                    msg.save()
                                        .then(function (message) {
                                        Message.where('id', message.attributes.id).fetch({ withRelated: ['user'] })
                                            .then(function (message) {
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
    socket.on('newChatroomCreated', function (data) {
        Chatroom.where('id', data).fetch({ withRelated: ['users'] })
            .then(function (chatroom) {
            if (chatroom) {
                chatroom.related('users').forEach(function (user) {
                    var nSocket = _.findWhere(io.sockets.sockets, { userId: user.attributes.id });
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
    socket.on('removedUserFromChatroom', function (data) {
        var uSocket = _.findWhere(io.sockets.sockets, { userId: data.userId });
        io.to("chatroom-" + data.chatroomId).emit('userRemoved', data);
        if (uSocket)
            uSocket.leave("chatroom-" + data.chatroomId);
    });
    socket.on('toggledUserAdminInChat', function (data) {
        Chatroom.where('id', data.chatroomId).fetch({ withRelated: ['users'] })
            .then(function (chatroom) {
            if (chatroom) {
                chatroom.related('users').forEach(function (user) {
                    if (user.attributes.id === data.userId) {
                        io.to('chatroom-' + chatroom.attributes.id).emit('toggledUserAdminInChat', { chatroomId: chatroom.attributes.id, userId: data.userId, admin: !!user.pivot.attributes.admin });
                    }
                });
            }
        });
    });
    socket.on('addedUsersToChatroom', function (data) {
        var socketsToNew = [];
        Chatroom.where('id', data.chatroomId).fetch({ withRelated: ['users'] })
            .then(function (chatroom) {
            if (chatroom) {
                chatroom.related('users').forEach(function (user) {
                    if (data.users.includes(user.attributes.id)) {
                        var uSocket = _.findWhere(io.sockets.sockets, { userId: user.attributes.id });
                        if (uSocket)
                            socketsToNew.push(uSocket);
                        io.to('chatroom-' + data.chatroomId).emit('addedUserToChatroom', { chatroomId: data.chatroomId, userId: user.attributes.id });
                    }
                });
                socketsToNew.forEach(function (socket) {
                    socket.join("chatroom-" + data.chatroomId);
                    socket.emit('newChatroomCreated', data.chatroomId);
                });
            }
        });
    });
    socket.on('chatroomNameChanged', function (chatroomId) {
        Chatroom.where('id', chatroomId).fetch()
            .then(function (chatroom) {
            if (chatroom) {
                io.to('chatroom-' + chatroom.attributes.id).emit('chatroomNameChanged', { chatroomId: chatroomId, newName: chatroom.attributes.name });
            }
        });
    });
    socket.on('disconnect', function () {
        var userId = socket.userId;
        if (userId) {
            User.where('id', userId).fetch()
                .then(function (user) {
                if (user) {
                    console.log("\"" + user.attributes.email + "\" has disconnected!");
                }
            });
        }
    });
});
function notAuthenticated(socket) {
    console.log("Failed to authenticate: " + socket.id);
    socket.emit('unauthenticated', {});
    socket.disconnect();
}
function mysqlNow() {
    return moment(Date.now()).format('YYYY-MM-DD HH:mm:ss');
}
setInterval(function () {
}, 50);
//# sourceMappingURL=index.js.map