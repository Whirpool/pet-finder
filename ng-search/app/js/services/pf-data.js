(function () {
    'use strict';

    angular.module('petFinder').factory('pfData', pfData);

    pfData.$inject = ['$http', '$rootScope', '$q', '$filter', 'pfRelation'];

    function pfData($http, $rootScope, $q, $filter, pfRelation) {
        return {
            findPet: function (query) {
                var self = this,
                    url = 'api/pet/search',
                    message = 'Not found';

                return $http.post(url, query, {
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (response) {
                    if (response.status === 204) {
                        return $q.reject(message);
                    } else {
                        response.data.forEach(function (pet) {
                            self.filterResponseData(pet);
                        });
                        return response.data;
                    }
                }, function (response) {
                    return $q.reject(response.data);
                });
            },

            newPet: function (query) {
                return $http({
                    url: 'api/pet/new',
                    method: "POST",
                    data: query,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                });
            },

            getDetail: function (id) {
                var pet = null,
                    find = false,
                    message = 'Not found',
                    url = 'api/pet/view',
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
                        url: url,
                        method: 'GET',
                        params: {id: id}
                    }).success(function (data, status) {
                        if (status === 204) {
                            deferred.reject(message);
                        } else {
                            pet = self.filterResponseData(data);
                            deferred.resolve(pet);
                        }
                    }).error(function (error) {
                        deferred.reject(error);
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

            sendMessage: function (id, data, type) {
                var url = 'api/' + type + '/new',
                    query = {
                        id: id,
                        content: data
                    };
                return $http.post(url, query).then(function (response) {
                    return response.data;
                }, function (error) {
                    return $q.reject(error.data)
                });
            }
        }
    }
})();

