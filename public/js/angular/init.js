var app = angular.module('binx', ['ngRoute', 'luegg.directives']);

app.config(['$routeProvider', '$locationProvider', '$qProvider', function ($routeProvider, $locationProvider, $qProvider) {
    $routeProvider
        .when("/chat", {
            templateUrl: "/js/angular/chat/html/index.html",
            controller: "ChatController"
        })
        .otherwise({
            redirectTo: '/chat'
        });

    $locationProvider.html5Mode({
        enabled: true,
        rewriteLinks: false
    });

    // $qProvider.errorOnUnhandledRejections(false);
}]);

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

angular.element(function() {
    angular.bootstrap(document, ['binx']);
});