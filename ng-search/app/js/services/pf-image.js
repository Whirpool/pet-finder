(function () {
    'use strict';

    angular.module('petFinder').factory('pfImage', pfImage);

    pfImage.$inject = ['$http', '$q', '$upload'];

    function pfImage($http, $q, $upload) {
        return {
            add: function(images) {
                    var deferred = $q.defer();
                    $upload.upload({
                        url: 'api/file/new',
                        method: 'POST',
                        file: images,
                        headers: {'Content-Type': undefined}
                    }).success(function (response) {
                        deferred.resolve(response);
                    }).error(function (response) {
                        deferred.reject(response.message);
                    });
                    return deferred.promise;
            },
            remove: function(img) {
                var deferred = $q.defer();
                $http({
                    url: 'api/file/delete',
                    method: "DELETE",
                    data: img
                }).success(function(response) {
                    if (response.success) {
                        deferred.resolve(response);
                    } else {
                        deferred.resolve(response);
                    }
                });
                return deferred.promise;
            }
        }
    }
})();
