(function () {
    'use strict';

    angular.module('petFinder').controller('NewCtrl', NewCtrl);

    NewCtrl.$inject = ['$scope', '$filter', '$location', 'pfData', 'pfMap', 'pfImage'];

    function NewCtrl($scope, $filter, $location, pfData, pfMap, pfImage) {
        $scope.tab.map.active = true;
        $scope.showErrors = false;
        $scope.images = [];

        $scope.onFileSelect = function($files) {
            angular.forEach($files, function (file) {
                pfImage.add(file).then(function(image) {
                    $scope.images.push(image.data);
                }, function (message) {
                    $scope.fileError = message;
                });
            });

        };

        $scope.deleteImage = function(image, index) {
            pfImage.remove(image).then(function() {
                $scope.images.splice(index, 1);
            })
        };

        $scope.map.mapClick = function(event) {
            var coords;
            if($location.path() === '/new') {
                coords = pfMap.setCoords(event);
                angular.extend($scope.model.formNew, coords);
            }
        };

        $scope.open = function($event) {
            $event.preventDefault();
            $event.stopPropagation();
            $scope.opened = true;
        };

        $scope.datepicker = $scope.maxDate = new Date();

        $scope.submitForm = function() {
            $scope.model.formNew.date = $filter('date')($scope.datepicker, ['dd-MM-yyyy']);
            pfData.newPet($scope.model.formNew).then(function() {
                $location.path( "/list" );
            }, function (errors) {
                $scope.showErrors = true;
                $scope.errors = errors;
            });
        }
    }
})();
