'use strict';

petFinder.controller('SearchCtrl', ['$scope', '$filter',  'pfData', 'pfMap', 'pfPagination',  function ($scope, $filter, pfData, pfMap, pfPagination) {
    $scope.error = {
        zoom: false
    };

    $scope.open = function ($event) {
        $event.preventDefault();
        $event.stopPropagation();

        $scope.opened = true;
    };

    $scope.map.mapClick = function() {
        return true;
    };
    $scope.datepicker = new Date();
    $scope.maxDate = new Date();
    $scope.submitForm = function () {
        if (pfMap.checkZoom()) {
            $scope.error.zoom = false;
            $scope.model.formSearch.date = $filter('date')($scope.datepicker, ['dd-MM-yyyy']);
            $scope.model.formSearch.bounds = pfMap.getBounds();
            $scope.model.formSearch.zoom = pfMap.getZoom();
            pfData.sendForm('search', $scope.model.formSearch).then(function (data) {
                $scope.model.pets = data;
                $scope.tab.list.disable = false;
                $scope.map.geoObjects = pfMap.createGeoObjects($scope.model.pets);
                $scope.pagination = pfPagination.setStartSettings($scope.pagination, $scope.model.pets);
                $scope.pagination = pfPagination.setPage($scope.pagination);
            }, function (error) {
                if(error === 'zoom') {
                    $scope.error.zoom = true;
                }
                if (error === 'Not Found') {
                    $scope.tab.list.disable = true;
                    $scope.model.pets = {};
                    $scope.pagination.pagedItems = {};
                    $scope.map.geoObjects = {};
                }
            });
        } else {
            $scope.error.zoom = true;
        }
    };
}]);