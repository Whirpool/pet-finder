(function () {
    'use strict';

    angular.module('petFinder').controller('NewCtrl', NewCtrl);

    NewCtrl.$inject = ['$scope', '$filter', '$location', 'pfData', 'pfMap', 'pfImage'];

    function NewCtrl($scope, $filter, $location, pfData, pfMap, pfImage) {
        $scope.tab.map.active = true;
        $scope.request = {
            init: {},
            data: {}
        };
        $scope.form = {
                errors: {
                    show: false
                },
                pet: {
                    date: {
                        picker: new Date(),
                        max: new Date()
                    }
                },
                type: {
                    breeds: []
                },
                status: {},
                init: {},
            images: {
                data: [],
                error: '',
                maxFiles: 5
            }

        };

        $scope.onFileSelect = function($files) {
            angular.forEach($files, function (file) {
                pfImage.add(file).then(function(image) {
                    $scope.form.images.data.push(image);
                }, function (message) {
                    $scope.form.images.error = message;
                });
            });
        };

        $scope.deleteImage = function(image, index) {
            pfImage.remove(image).then(function() {
                $scope.form.images.data.splice(index, 1);
            })
        };

        $scope.map.mapClick = function(event) {
            var coords;
            if($location.path() === '/new') {
                coords = pfMap.setCoords(event);
                $scope.form.pet.location = coords;
            }
        };

        $scope.open = function($event) {
            $event.preventDefault();
            $event.stopPropagation();
            $scope.opened = true;
        };

        $scope.submitForm = function() {
            $scope.request.init = $scope.form.init;
            angular.extend($scope.request.data,
                $scope.form.pet,
                $scope.form.status,
                $scope.form.type);
            $scope.request.data.date = $filter('date')($scope.form.pet.date.picker, ['yyyy-MM-dd']);
            pfData.newPet($scope.request).then(function() {
                $location.path( "/list" );
            }, function (errors) {
                $scope.form.errors.show = true;
                $scope.form.errors.message = errors.data.message;
            });
        };

        $scope.$watch('model.breeds', function (newVal, oldVal) {
            if(newVal !== oldVal) {
                $scope.form.type.breeds.push(newVal);
            }
        });

        $scope.$watch('form.init.type', function (newVal, oldVal) {
            if(newVal !== oldVal) {
                $scope.form.type = {
                    breeds: []
                };
            }
        });
        $scope.$watch('form.init.status', function (newVal, oldVal) {
            if(newVal !== oldVal) {
                $scope.form.status = {};
            }
        });
    }
})();
