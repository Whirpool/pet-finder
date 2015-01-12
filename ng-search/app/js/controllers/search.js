(function () {
    'use strict';

    angular.module('petFinder').controller('SearchCtrl', SearchCtrl);

    SearchCtrl.$inject = ['$filter', '$state', 'pfMap', 'pfRelation', 'pfLabelConst'];

    function SearchCtrl($filter, $state, pfMap, pfRelation, pfLabelConst) {
        var vm = this,
            request = {};

        vm.label = pfLabelConst;
        vm.relations = pfRelation.get();
        vm.form = {
            error: {
                zoom: false
            },
            category: {},
            common: {},
            status: {},
            pet: {
                breeds: []
            },
            date: {
                picker: new Date(),
                max: new Date()
            }
        };

        vm.openDatePicker = openDatePicker;
        vm.submitForm = submitForm;
        vm.setBreed = setBreed;
        vm.clearFormPet = clearFormPet;
        vm.clearFormStatus = clearFormStatus;

        function openDatePicker($event) {
            $event.preventDefault();
            $event.stopPropagation();
            vm.opened = true;
        }

        function submitForm() {
            if (pfMap.isZoomValid()) {
                vm.form.error.zoom = false;
                angular.extend(request,
                    vm.form.category,
                    vm.form.common,
                    vm.form.status,
                    vm.form.pet,
                    pfMap.getLocation()
                );
                request.date = $filter('date')(vm.form.date.picker, ['dd-MM-yyyy']);
                pfMap.clearGeoObjects();
                $state.go('form.search.list', request);
            } else {
                vm.form.error.zoom = true;
            }
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
