'use strict';

angular.module('app').
controller('QuizCtrl', ['$scope', '$location', 'User', function($scope, $location, User) {
  if (User.getIsGuest()) {
    $location.path('/');
    return;
  }

  $scope.user = User;

  $scope.submit = function() {
    if (!User.currentAnswer) {
      return;
    }

    User.submitAnswer(User.challenge, User.currentAnswer).then(function(data) {
      if (data.finished) {
          User.score = data.score;
          $location.path('/finish');
      }

      if (data.status == 'error') {
        User.error = User.currentAnswer;
      } else {
        User.error = false;
        User.currentAnswer = null;
        User.challenge = data.challenge;
        User.answers = data.answers;
      }
    });
  }
}]);
