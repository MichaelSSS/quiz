'use strict';

angular.module('app').
controller('IndexCtrl', ['$scope', '$location', 'User', function($scope, $location, User) {
    if (User.getIsGuest()) {
      $location.path('/start');
    } else {
      $location.path('/quiz');
    }
}]);
