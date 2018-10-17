app.service('$chat', ['$http', '$socket', '$q', '$rootScope', function ($http, $socket, $q, $rootScope) {
    var socket = $socket.getConnection();

    this.chats = [];

    var chat = {};

    var self = this;
    var defer = $q.defer();
    var ready = false;

    $http.get('/chat/retrieve')
        .then(function (res) {
            res.data.forEach(function (chat) {
                chat.messages = [];
                chat.defer = $q.defer();
                self.chats.push(chat);
            });

            defer.notify(self.chats);

            ready = true;
        }, function (res) {
            alert('Couldn\'t retrieve chatrooms');
        });

    this.getChats = function () {
        return this.chats;
    };

    this.fetchChat = function (id) {
        if (!this.hasChat(id)) {
            $http.get('/chat/' + id)
                .then(function (res) {
                    var chat = res.data;
                    chat.messages = [];
                    chat.defer = $q.defer();

                    chat.users = chat.users.map(function (user) {
                        user.defer = $q.defer();
                        return user;
                    });

                    self.chats.push(chat);

                    defer.notify(self.chats);
                });
        }
    };

    this.fetchUserInChat = function (chatroomId, userId) {
        if (this.hasChat(chatroomId) && this.getUserInChat(chatroomId, userId) == null) {
            var request = $http.get('/chat/' + chatroomId + '/user/' + userId);

            request.then(function (res) {
                var chat = self.findChat(chatroomId);
                var user = res.data;
                user.defer = $q.defer();

                chat.users.push(user);

                chat.defer.notify(chat);
            });

            return request;
        }

        return null;
    };

    this.hasChat = function (id) {
        return this.chats.reduce(function (acc, cur) {
            return cur.id === parseInt(id) || acc;
        }, false);
    };

    this.getUserInChat = function (chatroomId, userId) {
        var chat = this.findChat(chatroomId);
        if (chat !== null && typeof chat.users !== 'undefined') {
            return chat.users.filter(function (e) {
                return e.id === parseInt(userId);
            })[0];
        }

        return null;
    };

    this.findChat = function (id) {
        var chat = self.chats.find(function (chat) {
            return chat.id === parseInt(id);
        });

        if (typeof chat !== 'undefined') {
            if (!(typeof chat.fetched !== 'undefined' && chat.fetched === true)) {
                $http.get('/chat/' + id)
                    .then(function (res) {
                        chat.fetched = true;
                        chat.messages = res.data.messages;
                        chat.users = res.data.users.map(function (user) {
                            user.defer = $q.defer();

                            return user;
                        });

                        chat.defer.notify(chat);

                    }, function (res) {
                        alert('Failed to retrieve messages');
                    });
            }

            return chat;
        }

        return null;
    };

    this.observeMe = function (chatroomId) {
        var deferred = $q.defer();

        this.observeUserInChatroom(chatroomId, $rootScope.userId)
            .then(function (data) {
                deferred.resolve(data);
            }, function (data) {
                deferred.reject(data);
            }, function (data) {
                deferred.notify(data);
            });

        return deferred.promise;
    };

    this.getMe = function (chatroomId) {
        return this.getUserInChat(chatroomId, $rootScope.userId);
    };

    this.observeUserInChatroom = function (chatroomId, userId) {
        return this.getUserInChat(chatroomId, userId).defer.promise;
    };

    this.createNewChatroom = function (input) {
        var request = $http.post('/chat/store', input);

        request.then(function (res) {
            var chat = res.data;
            chat.defer = $q.defer();

            self.chats.push(chat);

            defer.notify(self.chats);

            socket.emit('newChatroomCreated', chat.id);
        });

        return request;
    };

    this.observeChats = function () {
        return defer.promise;
    };

    this.observeChat = function (id) {
        var deferred = $q.defer();

        var chat = self.findChat(id);
        if (chat !== null) {
            chat.defer.promise.then(function (data) {
                deferred.resolve(data);
            }, function (data) {
                deferred.reject(data);
            }, function (data) {
                deferred.notify(data);
            });

            return deferred.promise;
        } else return null;
    };

    this.removeUserFromChatroom = function (chatroomId, userId) {
        if (self.hasChat(chatroomId)) {
            var me = self.getUserInChat(chatroomId, $rootScope.userId);
            var user = self.getUserInChat(chatroomId, userId);

            if (me !== null && user !== null && me.pivot.admin) {
                var request = $http.delete('/chat/' + chatroomId + '/user/' + user.id);
                request.then(function (res) {
                    socket.emit('removedUserFromChatroom', {chatroomId: chatroomId, userId: userId});
                });
                return request;
            }
        }

        return null;
    };

    this.toggleAdminInChatroom = function (chatroomId, userId) {
        if (self.hasChat(chatroomId)) {
            var me = self.getUserInChat(chatroomId, $rootScope.userId);
            var user = self.getUserInChat(chatroomId, userId);

            if (me !== null && user !== null && me.pivot.admin) {
                var request = $http.post('/chat/' + chatroomId + '/user/' + user.id, {admin: !user.pivot.admin});

                request.then(function (res) {
                    socket.emit('toggledUserAdminInChat', {chatroomId: chatroomId, userId: userId});
                });

                return request;
            }
        }

        return null;
    };

    this.renameChat = function (chatroomId, newName) {
        if (self.hasChat(chatroomId) && self.getMe(chatroomId) && self.getMe(chatroomId).pivot.admin) {
            var request = $http.post('/chat/' + chatroomId, {'name': newName});

            request.then(function () {
                socket.emit('chatroomNameChanged', chatroomId);
            });

            return request;
        }

        return null;
    };

    this.addUsersToChat = function (chatroomId, users) {
        if (self.hasChat(chatroomId)) {
            var me = self.getMe(chatroomId);

            if (me !== null && me.pivot.admin) {
                var request = $http.put('/chat/' + chatroomId, {users: users});

                request.then(function (res) {
                    socket.emit('addedUsersToChatroom', {chatroomId: chatroomId, users: res.data});
                });

                return request;
            }
        }

        return null;
    };

    this.isReady = function () {
        return ready;
    };

    socket.on('message', function (message) {
        chat = self.findChat(message.chatroom_id);
        chat.latest = message;
        chat.messages.push(message);

        defer.notify(self.chats);
        chat.defer.notify(chat);
    });

    socket.on('newChatroomCreated', function (id) {
        self.fetchChat(id);
    });

    socket.on('userRemoved', function (data) {
        var user = self.getUserInChat(data.chatroomId, data.userId);

        if (user !== null) {
            if (typeof user.defer === 'undefined') console.log(user);
            user.defer.notify('userRemoved');

            var chat = self.findChat(data.chatroomId);
            chat.users.splice(chat.users.indexOf(user), 1);
            chat.defer.notify(chat);

            if (user.id === $rootScope.userId) {
                chat.defer.notify('userRemoved');
                self.chats.splice(self.chats.indexOf(self.findChat(data.chatroomId)), 1);
                defer.notify(self.chats);
            }
        }
    });

    socket.on('toggledUserAdminInChat', function (data) {
        var user = self.getUserInChat(data.chatroomId, data.userId);

        if (user !== null) {
            user.pivot.admin = data.admin;
            var chat = self.findChat(data.chatroomId);
            chat.defer.notify(chat);
            user.defer.notify(user);
        }
    });

    socket.on('addedUserToChatroom', function (data) {
        self.fetchUserInChat(data.chatroomId, data.userId);
    });

    socket.on('chatroomNameChanged', function (data) {
        if (self.hasChat(data.chatroomId)) {
            var chat = self.findChat(data.chatroomId);
            chat.name = data.newName;

            chat.defer.notify(chat);
            defer.notify(self.chats);
        }
    });

    this.sendMessage = function (id, message) {
        socket.emit('message', {chatId: id, message: message});
    };
}]);