'use strict';

angular.module('app').
controller('StartCtrl', ['$scope', '$location', 'User', function($scope, $location, User) {
  $scope.user = { lang: 'en' };

  $scope.start = function(valid) {
    if (!valid) return;

    User.start($scope.user).then(function() {
      if (!User.getIsGuest()) {
        $location.path('/quiz');
      }
    }, function(resp) {
      1;
    });
  }
}]);
