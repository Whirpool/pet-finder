'use strict';

petFinder.controller('NewCtrl', ['$scope', '$filter', 'pfData', 'pfMap',  '$location', '$upload', function ($scope, $filter, pfData, pfMap, $location, $upload) {
    $scope.tab.map.active = true;
    $scope.showErrors = false;
    $scope.images = [];

    $scope.onFileSelect = function($files) {
        for (var i = 0; i < $files.length; i++) {
            var file = $files[i];
            $scope.upload = $upload.upload({
                url: 'api/file',
                method: 'POST',
                file: file
            }).success(function(response) {
                if(response.success) {
                    $scope.images.push(response.data);
                } else {
                    $scope.fileError = response.error;
                }
            });
        }
    };

    $scope.deleteImage = function (image, index) {
        pfData.deleteImage(image).then(function () {
            $scope.images.splice(index, 1);
        })
    };

    $scope.map.mapClick = function(event) {
        $scope.model.formNew = pfMap.setCoords(event, $scope.model.formNew);
    };

    $scope.open = function ($event) {
        $event.preventDefault();
        $event.stopPropagation();

        $scope.opened = true;
    };

    $scope.datepicker = new Date();
    $scope.maxDate = new Date();

    $scope.submitForm = function () {
        $scope.model.formNew.date = $filter('date')($scope.datepicker, ['dd-MM-yyyy']);
        pfData.sendForm('new', $scope.model.formNew).then(function (response) {
            $location.path( "/list" );
        }, function (errors) {
            $scope.showErrors = true;
            $scope.errors = errors;
        });
    }
}]);