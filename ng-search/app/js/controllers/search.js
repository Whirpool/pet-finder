(function () {
    'use strict';

    angular.module('petFinder').controller('SearchCtrl', SearchCtrl);

    SearchCtrl.$inject = ['$scope', '$rootScope', '$filter',  'pfData', 'pfMap', 'pfPagination'];

    function SearchCtrl($scope, $rootScope, $filter, pfData, pfMap, pfPagination) {
        $scope.error = {
            zoom: false
        };

        $scope.date = {
            picker: new Date(),
            max: new Date()
        };

        $scope.open = function ($event) {
            $event.preventDefault();
            $event.stopPropagation();
            $scope.opened = true;
        };

        $scope.submitForm = function () {
            if (pfMap.isZoomValid()) {
                $scope.error.zoom = false;
                $scope.model.formSearch.date = $filter('date')($scope.date.picker, ['dd-MM-yyyy']);
                $scope.model.formSearch.location = pfMap.getBounds();
                pfData.findPet($scope.model.formSearch).then(function (data) {
                    $scope.model.pets = data;
                    $rootScope.pets =  data;
                    $scope.error.message = false;
                    $scope.tab.list.disable = false;
                    $scope.map.geoObjects = pfMap.createGeoObjects($scope.model.pets);
                    $scope.pagination = pfPagination.init($scope.model.pets);
                }, function (message) {
                    $scope.error.message = message;
                });
            } else {
                $scope.error.zoom = true;
            }
        };
    }
})();
