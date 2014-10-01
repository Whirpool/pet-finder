(function () {
    'use strict';

    angular.module('petFinder').controller('SearchCtrl', SearchCtrl);

    SearchCtrl.$inject = ['$scope', '$rootScope', '$filter',  'pfData', 'pfMap', 'pfPagination'];

    function SearchCtrl($scope, $rootScope, $filter, pfData, pfMap, pfPagination) {
        $scope.error = {
            zoom: false
        };

        $scope.open = function ($event) {
            $event.preventDefault();
            $event.stopPropagation();
            $scope.opened = true;
        };

        $scope.datepicker = new Date();
        $scope.maxDate = new Date();

        $scope.submitForm = function () {
            var initParams;
            if (pfMap.isZoomValid()) {
                $scope.error.zoom = false;
                $scope.model.formSearch.date = $filter('date')($scope.datepicker, ['dd-MM-yyyy']);
                $scope.model.formSearch.location = pfMap.getBounds();
                pfData.findPet($scope.model.formSearch).then(function (data) {
                    $scope.model.pets = data;
                    $rootScope.pets =  data;
                    $scope.error.message = false;
                    $scope.tab.list.disable = false;
                    $scope.map.geoObjects = pfMap.createGeoObjects($scope.model.pets);
                    $scope.pagination.show = pfPagination.showPag($scope.model.pets.length, $scope.pagination.pageSize);
                    initParams = pfPagination.init($scope.pagination.pageSize, $scope.model.pets);
                    angular.extend($scope.pagination, initParams);
                    $scope.pagination = pfPagination.setPage($scope.pagination);

                }, function (message) {
                    $scope.error.message = message;
                });
            } else {
                $scope.error.zoom = true;
            }
        };
    }
})();
