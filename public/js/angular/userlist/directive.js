app.directive('userlist', ['$http', '$timeout', function($http, $timeout) {
    return {
        restrict: 'E',
        scope: {
            'ngModel': '=?',
            'exclude': '=?',
            'search': '=?'
        },
        templateUrl: '/js/angular/userlist/template.html',
        controller: function($scope) {
            $scope.searchTimer = null;
            $scope.users = null;

            $scope.ngModel = [];

            $scope.loading = false;

            $scope.excluding = [];

            $scope.$watch('exclude', function(newVal) {
                if(typeof newVal === 'string') {
                    $scope.excluding = (newVal !== '' ? newVal.split(/\s?,\s?/) : []);
                } else if(Array.isArray(newVal)) {
                    $scope.excluding = newVal;
                }
            });

            $scope.$watch('search', function(newVal) {
                if(typeof newVal === 'string') {
                    if(newVal.length <= 0) {
                        $timeout.cancel($scope.searchTimer);
                        $scope.searchTimer = null;
                        $scope.users = null;
                    } else if(newVal.length >= 3) {
                        if($scope.searchTimer != null) {
                            $timeout.cancel($scope.searchTimer);
                            $scope.searchTimer = null;
                        }

                        $scope.searchTimer = $timeout(function() {
                            $scope.loading = true;

                            $http.post('/chat/filterusers', {search: newVal, exclude: $scope.excluding})
                                .then(function(res) {
                                    $scope.users = res.data;
                                }, function(res) {
                                    alert('Failed to find users');
                                }, function() {
                                    $scope.loading = false;
                                });
                        }, 300);
                    } else if($scope.searchTimer != null) {
                        $timeout.cancel($scope.searchTimer);
                        $scope.searchTimer = null;
                    }
                }
            });

            $scope.isSelected = function(id) {
                return $scope.ngModel.includes(id);
            };

            $scope.toggleSelected = function(id) {
                if($scope.isSelected(id)) {
                    $scope.ngModel.splice($scope.ngModel.indexOf(id), 1);
                } else {
                    $scope.ngModel.push(id);
                }

                return false;
            };
        }
    };
}]);