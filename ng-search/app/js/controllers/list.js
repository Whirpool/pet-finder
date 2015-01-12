(function () {
    'use strict';

    angular.module('petFinder').controller('ListCtrl', ListCtrl);

    ListCtrl.$inject = ['$scope', '$filter', 'pfPet', 'pfMap', 'pfLabelConst', 'pfRelation'];

    function ListCtrl($scope, $filter, pfPet, pfMap, pfLabelConst, pfRelation) {
        var vm = this;
        vm.petLocale = {
            one: 'Найден один питомец',
            few: 'Найдено {} питомца',
            many: 'Найдено {} питомцев',
            other: 'Найдено {}'
        };
        vm.pets = pfPet.getPets();
        vm.url = $filter('category')(pfPet.getCategory());
        $scope.$emit('listCtrlLoaded');
    }
})();

