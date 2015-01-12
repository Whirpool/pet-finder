(function () {
    'use strict';

    angular.module('petFinder').controller('MainCtrl', MainCtrl);

    MainCtrl.$inject = ['$scope', '$location', 'pfMap'];

    function MainCtrl($scope, $location, pfMap) {
        var vm = this;

        vm.geoObjects = {};

        vm.initMap = initMap;
        vm.mapClick = mapClick;

        $scope.$on('listCtrlLoaded', function () {
            vm.geoObjects = pfMap.getGeoObjects();
            vm.center = pfMap.getCenter();
        });

        function initMap($map) {
            pfMap.init($map);
        }

        function mapClick(event) {
            if ($location.path() === '/new') {
                $scope.$broadcast('mapClicked', pfMap.getCoords(event));
            }
        }
    }
})();
