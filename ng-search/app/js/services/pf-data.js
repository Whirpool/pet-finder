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
                        return response.data;
                    }
                }, function (response) {
                    return $q.reject(response.data.message);
                });
            },

            newPet: function (query) {
                return $http({
                    url: 'api/pet',
                    method: "POST",
                    data: query,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                });
            },

            getDetail: function (type, status, id) {
                var pet = null,
                    find = false,
                    message = 'Not found',
                    url = 'api/pet',
                    deferred = $q.defer(),
                    statusPet,
                    result = {
                        category: {}
                    };

                type   = type.toLowerCase().replace(/\s+/g, '');
                statusPet = status.toLowerCase().replace(/\s+/g, '');
                if ($rootScope.pets !== 'undefined') {
                    angular.forEach($rootScope.pets, function (pet) {
                        if (pet.id === id) {
                            find = true;
                            result.pet = pet;
                            result.category.status = status;
                            result.category.type = type;
                            deferred.resolve(result);
                        }
                    });
                }

                if (!find) {
                    $http({
                        url: url,
                        method: 'GET',
                        params: {id: id, type: type, status: status}
                    }).success(function (data, status) {
                        if (status === 204) {
                            deferred.reject(data);
                        } else {
                            result.pet = data;
                            result.category.status = statusPet;
                            result.category.type = type;
                            deferred.resolve(result);
                        }
                    });
                }
                return deferred.promise;
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

