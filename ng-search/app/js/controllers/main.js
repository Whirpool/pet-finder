(function () {
    'use strict';

    angular.module('petFinder').controller('MainCtrl', MainCtrl);

    MainCtrl.$inject = ['$scope', 'pfRelation', 'pfPagination', 'pfMap', 'pfHeader'];

    function MainCtrl($scope, pfRelation, pfPagination, pfMap, pfHeader) {
        $scope.pagination = {};
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

        $scope.model.header    = pfHeader;
        $scope.model.relations = pfRelation.get();

        $scope.map.afterInit = function ($map) {
            pfMap.init($map);
        };

        $scope.$watch('pagination.currentPage', function () {
            pfPagination.setPage();
            $scope.pagination = pfPagination.getPagination();
        }, true);
    }
})();

