'use strict';

angular.module('app')
.factory('User', ['$q', 'API', function($q, API) {
  var User = {};

  var isGuest = true;

  User.getIsGuest = function() {
    return isGuest;
  };

  User.start = function(data) {
    var defer = $q.defer();

    API.post('quiz/start', data).then(function(res) {
      isGuest = false;
      User.challenge = res.challenge;
      User.answers = res.answers;
      defer.resolve();
    }, defer.reject);

    return defer.promise;
  };

  User.submitAnswer = function(challenge, answer) {
    return API.post('quiz/submit', { challenge: challenge, answer: answer });
  }

  User.finish = function() {
    isGuest = true;
  }

  return User;
}]);
