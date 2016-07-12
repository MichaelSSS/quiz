'use strict';

angular.module('app').
controller('FinishCtrl', ['$scope', '$location', 'User', function($scope, $location, User) {
  if (User.getIsGuest()) {
    $location.path('/');
    return;
  }

  $scope.user = User;

  User.finish();
}]);
