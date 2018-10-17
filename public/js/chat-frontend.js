var app = angular.module('binx', ['ngRoute']);

app.config(function ($routeProvider, $locationProvider) {
    $routeProvider
        .when("/chat", {
            templateUrl: "/chat/temp/index",
            controller: "ChatController"
        })
        .otherwise({
            redirectTo: '/chat'
        });

    $locationProvider.html5Mode(true);
});

app.run(['$rootScope', '$http', function ($rootScope, $http) {
    $rootScope.sat = socketAuthToken;
    $rootScope.userId = userId;

    $rootScope.fetchAuthToken = function (cb) {
        $http.get('/chat/token')
            .then(function (res) {
                $rootScope.sat = res.data;
                cb(null, res.data);
            }, function (res) {
                console.log('Failed to retrieve new authentication token.');
                cb(res, null);
            });
    };
}]);

app.controller("ChatController", ['$rootScope', '$scope', '$http', '$socket', function ($rootScope, $scope, $http, $socket) {
    var socket = $socket($scope);

    $rootScope.selectedChat = null;

    $rootScope.chats = [];

    $http.get('/chat/retrieve')
        .then(function (res) {
            res.data.forEach(function (chat) {
                chat.latest = chat.latest[0];
                chat.messages = [chat.latest];
                $rootScope.chats.push(chat);
            });
        }, function (res) {
            alert('Couldn\'t retrieve chatrooms');
        });

    $rootScope.selectChat = function (id) {
        $rootScope.selectedChat = id;
    };

    socket.on('newMessage', function (message) {
        $rootScope.chats.forEach(function (chat) {
            if (chat.id == message.chatroom_id) {
                chat.latest = message;
            }
        });
    });
}]);

app.directive('chat', ['$http', '$socket', function ($http, $socket) {
    return {
        restrict: 'E',
        scope: {
            id: '@',
        },
        templateUrl: '/chat/temp/chat',
        controller: function ($scope, $element, $attrs) {
            var socket = $socket($scope);

            $scope.connected = socket.socket.connected;

            $attrs.$observe('id', function (id) {
                $http.get('/chat/' + id)
                    .then(function (res) {
                        $scope.chat = res.data;
                    }, function (res) {
                        alert('ERROR');
                    });

                socket.on('newMessage', function (data) {
                    if (data.chatroom_id == id) {
                        $scope.chat.messages.push(data);
                    }
                });
            });

            $scope.sendChat = function (chatId, message) {
                $scope.newMessage = '';

                socket.emit('message', {chatId: chatId, message: message});

                return false;
            };

            socket.on('connect', function() {
                $scope.connected = true;
            });

            socket.on('disconnect', function() {
                $scope.connected = false;
            });
        }
    };
}]);

app.filter('trust', ['$sce', function ($sce) {
    return function (value) {
        return $sce.trustAsHtml(value);
    }
}]);

var ScopedSocket = function (socket, $rootScope) {
    this.socket = socket;
    this.$rootScope = $rootScope;
    this.listeners = [];
};

ScopedSocket.prototype.removeAllListeners = function () {
    for (var i = 0; i < this.listeners.length; i++) {
        var details = this.listeners[i];
        this.socket.removeListener(details.event, details.fn);
    }
};

ScopedSocket.prototype.on = function (event, callback) {
    var socket = this.socket;
    var $rootScope = this.$rootScope;

    var wrappedCallback = function () {
        var args = arguments;
        $rootScope.$apply(function () {
            callback.apply(socket, args);
        });
    };

    this.listeners.push({event: event, fn: wrappedCallback});

    socket.on(event, wrappedCallback);
};

ScopedSocket.prototype.emit = function (event, data, callback) {
    var socket = this.socket;
    var $rootScope = this.$rootScope;

    socket.emit(event, data, function () {
        var args = arguments;
        $rootScope.$apply(function () {
            if (callback) {
                callback.apply(socket, args);
            }
        });
    });
};

app.factory('$socket', function ($rootScope) {
    var socket = null;

    var $socket = function (scope) {
        var scopedSocket = new ScopedSocket(socket, $rootScope);
        scope.$on('$destroy', function () {
            scopedSocket.removeAllListeners();
        });
        return scopedSocket;
    };

    $socket.connect = function (token) {
        socket = io.connect(socketURL, {
            query: {token: token}
        });
    };

    $socket.connect($rootScope.sat);

    socket.on('unauthenticated', function () {
        $rootScope.fetchAuthToken(function (err, sat) {
            if (!err) $socket.connect(sat);
        });
    });

    return $socket;
});

app.filter('latestChat', function () {
    return function (input) {
        return input.slice().sort(function (a, b) {
            var ad = new Date(a.latest.created_at).getTime();
            var bd = new Date(b.latest.created_at).getTime();

            return (ad === bd ? 0 : (ad < bd ? 1 : -1));
        });
    };
});
