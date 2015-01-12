(function () {
    'use strict';

    angular.module('petFinder').controller('DetailCtrl', DetailCtrl);

    DetailCtrl.$inject = ['$stateParams', 'pfPet', 'pfLabelConst'];

    function DetailCtrl($stateParams, pfPet, pfLabelConst) {
        var vm = this;
        pfPet.findById($stateParams.pet, $stateParams.status, $stateParams.id).then(function (data) {
            vm.pet = data.pet;
            vm.category = data.category;
            vm.label = pfLabelConst;
            vm.map = {
                center: [data.pet.lat, data.pet.lng],
                point: data.point
            };
            if (!!vm.pet.images) {
                vm.mainImage = vm.pet.images[0].source_original;
            } else {
                vm.mainImage = 'images/default.png';
            }
        }, function (error) {
            vm.detailError = error;
        });

        vm.changeMainImage = function (src) {
            vm.mainImage = src;
        };
    }
})();
