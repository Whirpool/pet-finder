'use strict';

petFinder.directive('pfComment', function () {
    return {
        restrict: "E",
        templateUrl: "ng-search/app/partials/directives/pf-comment.html",
        replace: true,
        scope: {
            comments: '='
        },
        controller: function ($scope, pfData, $stateParams) {
            $scope.form = {};
            $scope.sendComment = function (data) {
                var type = 'comment';
                pfData.sendMessage($stateParams.id, data, type).then(function (data) {
                    $scope.comments.push(data);
                    $scope.form.content = '';
                    $scope.form.errors = [];
                }, function (errors) {
                    $scope.form.errors = errors;
                })
            };
        }
    }
});