(function () {
    'use strict';

    angular.module('petFinder').controller('MainCtrl', MainCtrl);

    MainCtrl.$inject = ['$scope', 'pfRelation', 'pfPagination', 'pfMap', 'pfHeader'];

    function MainCtrl($scope, pfRelation, pfPagination, pfMap, pfHeader) {
        $scope.tab = {
            list: {
                disable: true,
                active: false
            },
            map: {
                disable: false,
                active: true
            }
        };

        $scope.map = {
            geoObjects: {}
        };

        $scope.model = {
            formSearch: {
                search_type: 1
            },
            formNew: {},
            pets: {}
        };

        $scope.petLocale = {
            one: 'Найден один питомец',
            few: 'Найдено {} питомца',
            many: 'Найдено {} питомцев',
            other: 'Найдено {}'
        };

        $scope.pagination = {
            pageSize: 8,
            maxSize: 5,
            currentPage: 1,
            numOfPages: 0,
            start: 0,
            end: 0,
            startItems: [],
            filteredItems: [],
            pagedItems: [],
            totalItems: 0,
            show: false
        };
        $scope.model.header    = pfHeader;
        $scope.model.relations = pfRelation.get();

        $scope.map.afterInit = function ($map) {
            pfMap.init($map);
        };

        $scope.$watch('pagination.currentPage', function (page) {
            $scope.pagination = pfPagination.setPage($scope.pagination);
        }, true);
    }
})();
