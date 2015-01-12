(function () {
    'use strict';

    angular.module('petFinder').factory('pfPet', pfPet);

    pfPet.$inject = ['$http', '$filter', '$q', 'pfMap'];

    function pfPet($http, $filter, $q, pfMap) {
        var pets = [],
            category = {
                pet: null,
                status: null
            };

        return {
            findByLocation: findByLocation,
            newPet: newPet,
            findById: findById,
            getPets: getPets,
            getCategory: getCategory
        };

        function findByLocation(query) {
            var url = 'api/pet/search',
                message = 'Not found';

            return $http.get(url, {
                params: query,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (response) {
                if (response.status === 204) {
                    return $q.reject(message);
                } else {
                    pfMap.setGeoObjects(response.data);
                    pfMap.setCenter(query.lat, query.lng);
                    pets = response.data;
                    category.pet = query.pet;
                    category.status = query.status;
                }
            }, function (response) {
                return $q.reject(response.data.message);
            });
        }

        function newPet(query) {
            return $http({
                url: 'api/pet',
                method: "POST",
                data: query,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            });
        }

        function findById(petType, petStatus, petId) {
            var pet = null,
                message = 'Not found',
                url = 'api/pet',
                deferred = $q.defer(),
                requestCategory,
                findInClient = false,
                output = {};

            if (pets.length) {
                pet = $filter('filter')(pets, {id: petId}, true);
                if (pet.length) {
                    output.pet = pet[0];
                    output.point = pfMap.createPoint(pet[0].lat, pet[0].lng);
                    output.category = category;
                    findInClient = true;
                    deferred.resolve(output);
                }
            }
            if (!findInClient) {
                requestCategory = $filter('category')({pet: petType, status: petStatus});
                $http({
                    url: url,
                    method: 'GET',
                    params: {id: petId, pet: requestCategory.pet, status: requestCategory.status}
                }).success(function (data, status) {
                    if (status === 204) {
                        deferred.reject(message);
                    } else {
                        output.pet = data[0];
                        output.category = requestCategory;
                        output.point = pfMap.createPoint(data[0].lat, data[0].lng);
                        deferred.resolve(output);
                    }
                });
            }

            return deferred.promise;
        }

        function getPets() {
            return pets;
        }

        function getCategory() {
            return category;
        }
    }
})();

