(function () {
    'use strict';

    angular.module('petFinder').factory('pfData', pfData);

    pfData.$inject = ['$http', '$rootScope', '$q', '$filter', 'pfRelation'];

    function pfData($http, $rootScope, $q, $filter, pfRelation) {
        return {
            findPet: function (query) {
                var deferred = $q.defer(),
                    self = this,
                    message;
                $http({
                    url: 'api/pet/search',
                    method: "POST",
                    data: query,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (data, status, headers, config) {
                    if (status === 204) {
                        message = 'Not found';
                        deferred.reject(message);
                    } else if (data.success) {
                        data.data.forEach(function (pet) {
                            self.filterResponseData(pet);
                        });
                        deferred.resolve(data.data);
                    }
                }).error(function (data) {
                    deferred.reject(data.message);
                });
                return deferred.promise;
            },

            newPet: function (query) {
                var deferred = $q.defer();
                $http({
                    url: 'api/pet/new',
                    method: "POST",
                    data: query,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (response) {
                    deferred.resolve(response.data);
                }).error(function (response) {
                    deferred.reject(response.message);
                });
                return deferred.promise;
            },

            getDetail: function (id) {
                var pet = null,
                    find = false,
                    message = null,
                    deferred = $q.defer(),
                    self = this;

                if ($rootScope.pets !== 'undefined') {
                    angular.forEach($rootScope.pets, function (pet) {
                        if (pet.id === id) {
                            find = true;
                            deferred.resolve(pet);
                        }
                    });
                }

                if (!find) {
                    $http({
                        url: 'api/pet/view',
                        method: "GET",
                        params: {
                            id: id
                        }
                    }).success(function (data, status) {
                        if (status === 204) {
                            message = 'Not found';
                            deferred.reject(message);
                        } else if (data.success) {
                            pet = self.filterResponseData(data.data);
                            deferred.resolve(pet);
                        } else {
                            deferred.reject(data.message);
                        }
                    }).error(function (data) {
                        deferred.reject(data.message);
                    });
                }


                return deferred.promise;
            },

            filterResponseData: function (pet) {
                var relation = pfRelation.get();

                pet['date'] = $filter('date')(pet['date'] * 1000, 'dd-MM-yyyy');
                pet['date_create'] = $filter('date')(pet['date_create'] * 1000, 'dd-MM-yyyy');
                pet['date_update'] = $filter('date')(pet['date_update'] * 1000, 'dd-MM-yyyy');
                pet['pet_id'] = $filter('list')(pet['pet_id'], relation.pet);
                pet['age_id'] = $filter('list')(pet['age_id'], relation.age);
                pet['sex'] = $filter('list')(pet['sex'], relation.sex);

                return pet;
            },

            sendComment: function (id, data) {
                var deferred = $q.defer();
                $http({
                    url: 'api/comment/new',
                    method: "POST",
                    data: {
                        id: id,
                        content: data
                    }
                }).success(function (response) {
                    deferred.resolve(response.data);
                }).error(function (response) {
                    deferred.reject(response.error);
                });
                return deferred.promise;
            },

            sendAnswer: function (id, data) {
                var deferred = $q.defer();
                $http({
                    url: 'api/answer/new',
                    method: "POST",
                    data: {
                        id: id,
                        content: data
                    }
                }).success(function (response) {
                    deferred.resolve(response.data);
                }).error(function (response) {
                    deferred.reject(response.error);
                });
                return deferred.promise;
            }
        }
    }
})();

