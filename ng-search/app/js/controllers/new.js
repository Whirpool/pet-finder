(function () {
    'use strict';

    angular.module('petFinder').controller('NewCtrl', NewCtrl);

    NewCtrl.$inject = ['$scope', '$filter', '$location', 'pfPet', 'pfImage', 'pfRelation', 'pfLabelConst'];

    function NewCtrl($scope, $filter, $location, pfPet, pfImage, pfRelation, pfLabelConst) {
        var vm = this,
            request = {};

        vm.label = pfLabelConst;
        vm.relations = pfRelation.get();
        vm.form = {
            errors: {
                show: false
            },
            common: {},
            pet: {
                breeds: []
            },
            status: {},
            category: {},
            images: {
                data: [],
                error: '',
                maxFiles: 5
            },
            date: {
                picker: new Date(),
                max: new Date()
            }

        };

        vm.addImage = addImage;
        vm.deleteImage = deleteImage;
        vm.openDatePicker = openDatePicker;
        vm.submitForm = submitForm;
        vm.setBreed = setBreed;
        vm.clearFormPet = clearFormPet;
        vm.clearFormStatus = clearFormStatus;

        $scope.$on('mapClicked', function (event, coords) {
            vm.form.common.location = coords;
        });

        function addImage($files) {
            angular.forEach($files, function (file) {
                pfImage.add(file).then(function (image) {
                    vm.form.images.data.push(image);
                }, function (message) {
                    vm.form.images.error = message;
                });
            });
        }

        function deleteImage(image, index) {
            pfImage.remove(image).then(function () {
                vm.form.images.data.splice(index, 1);
            })
        }

        function openDatePicker($event) {
            $event.preventDefault();
            $event.stopPropagation();
            vm.opened = true;
        }

        function submitForm() {
            angular.extend(request,
                vm.form.category,
                vm.form.common,
                vm.form.status,
                vm.form.pet);
            request.date = $filter('date')(vm.form.date.picker, ['yyyy-MM-dd']);
            pfPet.newPet(request).then(function () {
                $location.path("/list");
            }, function (errors) {
                vm.form.errors.show = true;
                vm.form.errors.message = errors.data.message;
            });
        }

        function setBreed(breed) {
            vm.form.pet.breeds.push(breed);
        }

        function clearFormPet() {
            vm.form.pet = {
                breeds: []
            };
        }

        function clearFormStatus() {
            vm.form.status = {};
        }


    }
})();
