app.filter('trust', ['$sce', function ($sce) {
    return function (value) {
        return $sce.trustAsHtml(value);
    }
}]);