(function () {
    'use strict';

    angular.module('petFinder').controller('NewCtrl', NewCtrl);

    NewCtrl.$inject = ['$scope', '$filter', '$location', 'pfData', 'pfMap', 'pfImage'];

    function NewCtrl($scope, $filter, $location, pfData, pfMap, pfImage) {
        $scope.tab.map.active = true;
        $scope.showErrors = false;
        $scope.images = {
            data: [],
            error: '',
            maxFiles: 5
        };
        $scope.date = {
            picker: new Date(),
            max: new Date()
        };

        $scope.onFileSelect = function($files) {
            angular.forEach($files, function (file) {
                pfImage.add(file).then(function(image) {
                    $scope.images.data.push(image);
                }, function (message) {
                    $scope.images.error = message;
                });
            });
        };

        $scope.deleteImage = function(image, index) {
            pfImage.remove(image).then(function() {
                $scope.images.data.splice(index, 1);
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

        $scope.submitForm = function() {
            $scope.model.formNew.date = $filter('date')($scope.date.picker, ['dd-MM-yyyy']);
            pfData.newPet($scope.model.formNew).then(function() {
                $scope.model.formNew = {};
                $location.path( "/list" );
            }, function (errors) {
                $scope.showErrors = true;
                $scope.errors = errors.data.message;
            });
        }
    }
})();
