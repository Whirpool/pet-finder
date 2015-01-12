'use strict';

petFinder.factory('pfData', ['$http', '$q', '$filter', '$rootScope', 'pfRelation', function ($http, $q, $filter, $rootScope, pfRelation) {
    return {
        sendForm: function (form, query) {
            var deferred = $q.defer(),
                self = this;
            $http({
                url: 'api/' + form,
                method: "POST",
                data: query,
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).success(function (response) {
                if (response.success === true) {
                    response.data.forEach(function (pet) {
                        self.filterResponseData(pet);
                    });
                    deferred.resolve(response.data);
                } else {
                    deferred.reject(response.error);
                }
            }).error(function (error) {
                console.log(error);
            });
            return deferred.promise;
        },

        getDetail: function (id) {
            var pet = null,
                deferred = $q.defer(),
                self = this;

            $http({
                url: 'api/view',
                method: "GET",
                params: {
                    id: id
                }
            }).success(function (response) {
                if (response.success === true) {
                    pet = self.filterResponseData(response.data);
                    deferred.resolve(pet);
                } else {
                    deferred.reject(response.error);
                }
            });

            return deferred.promise;
        },

        filterResponseData: function (pet) {
            var relation = pfRelation();

            pet['date'] = $filter('date')(pet['date'] * 1000, 'dd-MM-yyyy');
            pet['date_create'] = $filter('date')(pet['date_create'] * 1000, 'dd-MM-yyyy');
            pet['date_update'] = $filter('date')(pet['date_update'] * 1000, 'dd-MM-yyyy');
            pet['pet_id'] = $filter('list')(pet['pet_id'], relation.pet);
            pet['age_id'] = $filter('list')(pet['age_id'], relation.age);
            pet['sex'] = $filter('list')(pet['sex'], relation.sex);

            return pet;
        },

        deleteImage: function (img) {
            var deferred = $q.defer();
            $http({
                url: 'api/file/delete',
                method: "POST",
                data: img
            }).success(function (response) {
                if (response.success) {
                    deferred.resolve(response);
                } else {
                    deferred.reject(response);
                }
            });
            return deferred.promise;
        },

        sendMessage: function (id, data, type) {
            var deferred = $q.defer();
            $http({
                url: 'api/message/new',
                method: "POST",
                data: {
                    id: id,
                    content: data,
                    type: type
                }
            }).success(function (response) {
                if (response.success) {
                    deferred.resolve(response.data);
                } else {
                    deferred.reject(response.errors);
                }
            });
            return deferred.promise;
        }
    }
}]);