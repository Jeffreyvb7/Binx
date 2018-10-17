app.directive('chat', ['$chat', '$socket', function ($chat, $socket) {
    return {
        restrict: 'E',
        scope: {
            id: '=',
        },
        templateUrl: '/js/angular/chat/html/chat.html',
        controller: function ($scope, $element, $attrs) {

            $scope.connected = $socket.isConnected();
            $scope.usersExclude = [];
            $scope.newUserSearch = '';

            $scope.newNameErrors = [];

            $scope.ready = false;

            $socket.observeConnectionStatus().then(null, null, function (connected) {
                $scope.connected = connected;
            });

            $scope.$watch('id', function (id, oldId) {
                if(id !== oldId) $scope.ready = false;
                if (id !== null) {
                    $scope.chat = $chat.findChat(id);
                    $scope.newName = angular.copy($scope.chat.name);

                    $scope.observer = $chat.observeChat(id);

                    $scope.setup = function() {
                        if (!$scope.ready && typeof $scope.chat.fetched !== 'undefined' && $scope.chat.fetched) {
                            $scope.ready = true;


                            $scope.me = $chat.getMe(id);

                            $chat.observeMe(id).then(null, null, function (me) {
                                if (me !== 'userRemoved') {
                                    $scope.me = me;
                                }
                            });
                        }
                    };

                    if ($scope.observer !== null) {
                        $scope.setup();

                        $scope.observer.then(null, null, function (chat) {
                            if (chat === 'userRemoved') {
                                alert('You have been removed from this chat!');
                                $scope.id = null;
                            } else {
                                $scope.chat = chat;
                                $scope.newName = angular.copy($scope.chat.name);

                                $scope.setup();

                                if (id !== null) {
                                    $scope.usersExclude = chat.users.reduce(function (acc, curr) {
                                        acc.push(curr.id);
                                        return acc;
                                    }, []);
                                }
                            }
                        });
                    }

                    $scope.sendChat = function () {
                        $chat.sendMessage(id, $scope.newMessage);
                        $scope.newMessage = '';

                        return false;
                    };

                    $scope.removeUser = function (userId) {
                        var request = $chat.removeUserFromChatroom(id, userId);

                        if(request !== null) {
                            request.then(null, function(res) {
                                if(typeof res.data.message !== 'undefined') alert(res.data.message);
                            });
                        }
                    };

                    $scope.addUsers = function (users, $event) {
                        $chat.addUsersToChat(id, users)
                            .then(function () {
                                $scope.newUserSearch = '';
                                $("#addUserToChatroomModal *[data-dismiss=modal]").trigger({type: "click"});
                            });
                        return false;
                    };

                    $scope.toggleAdmin = function (userId) {
                        var request = $chat.toggleAdminInChatroom(id, userId);

                        if(request !== null) {
                            request.then(null, function(res) {
                                if(typeof res.data.message !== 'undefined') alert(res.data.message);
                            });
                        }
                    };

                    $scope.renameChat = function (newName, $event) {
                        var request = $chat.renameChat(id, newName);

                        if(request !== null) {
                            request.then(function () {
                                $("#renameChatroomModal *[data-dismiss=modal]").trigger({type: "click"});
                                $scope.newName = angular.copy($scope.chat.name);
                                $scope.newNameErrors = [];
                            }, function (res) {
                                if (res.status === 422) {
                                    $scope.newNameErrors = res.data.errors.name;
                                }
                            });
                        }

                        if (typeof $event !== 'undefined') $event.preventDefault();
                        return false;
                    };
                }
            });

            $scope.adminCount = function() {
                if(typeof $scope.chat !== 'undefined' && typeof $scope.chat.users !== 'undefined') {
                    return $scope.chat.users.filter(function(user) {
                        return user.pivot.admin;
                    }).length;
                }
                return 0;
            };
        }
    };
}]);