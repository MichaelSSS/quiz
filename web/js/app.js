'use strict';

// Declare app level module
angular.module('app', [ 'ngRoute' ]).
config(['$routeProvider', function($routeProvider) {
  $routeProvider
  .when('/', { template:'', controller: 'IndexCtrl' })
  .when('/quiz', { templateUrl: 'views/quiz.html', controller: 'QuizCtrl' })
  .when('/start', { templateUrl: 'views/start.html', controller: 'StartCtrl' })
  .when('/finish', { templateUrl: 'views/finish.html', controller: 'FinishCtrl' })
  .otherwise({redirectTo: '/'});
}]);
