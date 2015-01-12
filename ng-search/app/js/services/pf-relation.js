(function () {
    'use strict';

    angular.module('petFinder').factory('pfRelation', pfRelation);

    pfRelation.$inject = ['$http'];

    function pfRelation($http) {
        var relations;

        return {
            load: load,
            get: getRelations
        };

        function getRelations() {
            return relations;
        }

        function load() {
            return $http({
                url: '/api/relation',
                method: 'GET'
            }).success(function (response) {
                relations = response.model;
            });
        }
    }
})();


