(function () {
    'use strict';

    angular.module('petFinder').controller('SearchCtrl', SearchCtrl);

    SearchCtrl.$inject = ['$scope', '$rootScope', '$filter', 'pfData', 'pfMap', 'pfPagination'];

    function SearchCtrl($scope, $rootScope, $filter, pfData, pfMap, pfPagination) {
        $scope.request = {
            init: {},
            data: {}
        };
        $scope.form = {
            error: {
                zoom: false
            },
            init: {},
            pet: {
                date: {
                    picker: new Date(),
                    max: new Date()
                }
            },
            status: {},
            type: {
                breeds: []
            }
        };

        $scope.open = function ($event) {
            $event.preventDefault();
            $event.stopPropagation();
            $scope.opened = true;
        };

        $scope.submitForm = function () {
            if (pfMap.isZoomValid()) {
                $scope.form.error.zoom = false;
                $scope.request.init = $filter('init')($scope.form.init);
                angular.extend($scope.request.data,
                    $scope.form.pet,
                    $scope.form.status,
                    $scope.form.type);
                $scope.request.data.date = $filter('date')($scope.form.pet.date.picker, ['dd-MM-yyyy']);
                $scope.request.data.location = pfMap.getLocation();
                pfData.findPet($scope.request).then(function (data) {
                    $scope.model.pets = data;
                    $scope.model.init = $scope.request.init;
                    $rootScope.pets = data;
                    $scope.form.error.zoom = false;
                    $scope.form.error.message = false;
                    $scope.tab.list.disable = false;
                    $scope.map.geoObjects = pfMap.createGeoObjects($scope.model.pets);
                    $scope.pagination = pfPagination.init($scope.model.pets);
                }, function (message) {
                    $scope.model.pets = {};
                    $rootScope.pets = {};
                    $scope.map.geoObjects = {};
                    $scope.form.error.message = message;
                });
            } else {
                $scope.form.error.zoom = true;
            }
        };

        $scope.$watch('model.breeds', function (newVal, oldVal) {
            if (newVal !== oldVal) {
                $scope.form.type.breeds.push(newVal);
            }
        });

        $scope.$watch('form.init.type', function (newVal, oldVal) {
            if (newVal !== oldVal) {
                $scope.form.type = {
                    breeds: []
                };
            }
        });

        $scope.$watch('form.init.status', function (newVal, oldVal) {
            if (newVal !== oldVal) {
                $scope.form.status = {};
            }
        });
    }
})();
