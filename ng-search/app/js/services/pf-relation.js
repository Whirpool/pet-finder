(function () {
    'use strict';

    angular.module('petFinder').factory('pfRelation', pfRelation);

    pfRelation.$inject = ['$http'];

    function pfRelation($http) {
        var relations;

        function getRelations() {
            return relations;
        }

        function setRelations(data) {
            relations = data;
        }

        return {
            load: function () {
                return $http({
                    url: '/api/relation',
                    method: 'GET'
                }).success(function (response) {
                    setRelations(response.lookup);
                });
            },
            get: getRelations
        }
    }
})();


