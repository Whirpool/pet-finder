(function () {
    'use strict';

    angular.module('petFinder').directive('pfList', pfList);

    function pfList() {
        return {
            restrict: "E",
            templateUrl: "/ng-search/app/partials/directives/pf-list.html",
            replace: true,

            controller: function ($scope) {
                $scope.onMap = function (pet) {
                    $scope.tab.map.active = true;
                    $scope.map.center = [pet.lat, pet.lng];
                };
            }
        }
    }
})();
