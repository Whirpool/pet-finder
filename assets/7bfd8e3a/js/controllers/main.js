petFinder.controller('MainCtrl', ['$scope', '$filter', 'pfRelation', 'pfPagination', 'pfMap', 'pfHeader', function ($scope, $filter, pfRelation, pfPagination, pfMap, pfHeader) {
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
    $scope.model.header = pfHeader;
    $scope.model.relations = pfRelation();

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
        totalItems: 0
    };

    $scope.map.afterInit = function ($map) {
        pfMap.init($map);
    };

    $scope.$watch('pagination.currentPage', function (page) {
        $scope.pagination = pfPagination.setPage($scope.pagination);
    }, true);

    $scope.showPagination = function() {
        return $scope.model.pets.length > $scope.pagination.pageSize;
    };

    $scope.petLocale = {
        one: 'Найден один питомец',
        few: 'Найдено {} питомца',
        many: 'Найдено {} питомцев',
        other: 'Найдено {}'
    };
}]);