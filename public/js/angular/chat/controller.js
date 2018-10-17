app.controller("ChatController", ['$rootScope', '$scope', '$http', '$socket', '$chat', function ($rootScope, $scope, $http, $socket, $chat) {
    $scope.$chat = $chat;

    $scope.chats = [];
    $scope.selectedChat = null;

    $scope.selectChat = function (id) {
        $scope.selectedChat = id;

    };

    $chat.observeChats().then(null, null, function (chats) {
        $scope.chats = chats;
    });

    $scope.testLog = function(val) {
        console.log('List: ' + val.reduce((acc, cur) => (acc.length !== 0 ? acc + " - " : "") + cur, ''));
    };

    $scope.createNewChatroom = function(input, $event) {
        $chat.createNewChatroom(input)
            .then(function(res) {
                $('#newChatroomModal').modal('hide');
                $('.modal-backdrop').hide();
            }, function(res) {
                alert('Failure');
            });

        $event.preventDefault();
        return false;
    };
}]);