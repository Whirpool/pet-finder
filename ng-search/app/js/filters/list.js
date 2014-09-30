(function () {
    'use strict';

    angular.module('petFinder').filter('list', list);

    function list() {
        return function (value, relation) {
            return relation[value-1]['name'];
        }
    }
})();
