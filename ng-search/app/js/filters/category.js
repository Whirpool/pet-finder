(function () {
    'use strict';

    angular.module('petFinder').filter('category', category);

    category.$inject = ['pfCategoryConst'];

    function category(pfCategoryConst) {
        var pet,
            status;
        function convertToString (value) {
            switch (value.pet) {
                case (pfCategoryConst.cat.int):
                    pet = pfCategoryConst.cat.string;
                    break;
                case (pfCategoryConst.dog.int):
                    pet = pfCategoryConst.dog.string;
                    break;
                default:
                    pet = null;
                    break;
            }
            switch (value.status) {
                case (pfCategoryConst.find.int):
                    status = pfCategoryConst.find.string;
                    break;
                case (pfCategoryConst.lost.int):
                    status = pfCategoryConst.lost.string;
                    break;
                default:
                    status = null;
                    break;
            }
            return {pet: pet, status: status};
        }
        function convertToInt (value) {
            value.pet = value.pet.toLowerCase().replace(/\s+/g, '');
            value.status = value.status.toLowerCase().replace(/\s+/g, '');
            switch (value.pet) {
                case (pfCategoryConst.cat.string):
                    pet = pfCategoryConst.cat.int;
                    break;
                case (pfCategoryConst.dog.string):
                    pet = pfCategoryConst.dog.int;
                    break;
                default:
                    pet = null;
                    break;
            }
            switch (value.status) {
                case (pfCategoryConst.find.string):
                    status = pfCategoryConst.find.int;
                    break;
                case (pfCategoryConst.lost.string):
                    status = pfCategoryConst.lost.int;
                    break;
                default:
                    status = null;
                    break;
            }
            return {pet: pet, status: status};
        }
        return function (value) {
            if (value.pet !== 'undefined' && value.status !== 'undefined') {
                if (angular.isNumber(value.pet) && angular.isNumber(value.status)) {
                    return convertToString(value);
                } else if (angular.isString(value.pet) && angular.isString(value.status)) {
                    return convertToInt(value);
                } else {
                    //TODO: логирование
                    console.error('filter category: value is not int and not string');
                }
            } else {
                //TODO: логирование
                console.error('filter category: value is undefined');
            }
        }
    }
})();
