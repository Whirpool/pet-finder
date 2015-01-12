(function () {
    'use strict';

    angular.module('petFinder').directive('pfComment', pfComment);

    function pfComment() {
        return {
            restrict: "E",
            templateUrl: "/ng-search/app/partials/directives/pf-comment.html",
            replace: true,
            scope: {
                comments: '='
            },
            controller: function ($scope, pfPet, $stateParams) {
                var type = 'comment';
                $scope.form = {};
                $scope.sendComment = function (data) {
                    pfPet.sendMessage($stateParams.id, data, type).then(function (data) {
                        $scope.comments.push(data);
                        $scope.form.content = '';
                        $scope.form.errors = [];
                    }, function (errors) {
                        $scope.form.errors = errors;
                    })
                };
            }
        }
    }
})();
