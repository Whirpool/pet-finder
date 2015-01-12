'use strict';

petFinder.directive('pfAnswers', function () {
    return {
        restrict: "E",
        templateUrl: "ng-search/app/partials/directives/pf-answers.html",
        replace: true,
        scope: {
            answers: '=',
            id: '@'
        },
        controller: function ($scope, pfData) {
            $scope.writeAnswer = false;
            $scope.form = {};
            $scope.sendAnswer = function (id, answer) {
                var type = 'answer';
                pfData.sendMessage(id, answer, type).then(function (data) {
                    $scope.answers.push(data);
                    $scope.writeAnswer = false;
                    $scope.form.errors = [];
                }, function (errors) {
                    $scope.form.errors = errors;
                })
            }
        }
    }
});