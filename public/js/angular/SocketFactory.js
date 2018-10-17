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

app.factory('$socket', function ($rootScope, $q) {
    var socket = null;
    var connectDefer = $q.defer();

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

    $socket.observeConnectionStatus = function() {
        return connectDefer.promise;
    };

    $socket.isConnected = function() {
        return socket.connected;
    };

    $socket.getConnection = function () {
        return socket;
    };

    $socket.connect($rootScope.sat);

    socket.on('unauthenticated', function () {
        $rootScope.fetchAuthToken(function (err, sat) {
            if (!err) $socket.connect(sat);
        });
    });

    socket.on('connect', function () {
        connectDefer.notify(true);
    });

    socket.on('disconnect', function () {
        connectDefer.notify(false);
    });

    return $socket;
});