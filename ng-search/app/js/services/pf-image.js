(function () {
    'use strict';

    angular.module('petFinder').factory('pfImage', pfImage);

    pfImage.$inject = ['$http', '$q', '$upload'];

    function pfImage($http, $q, $upload) {
        return {
            add: function (images) {
                return $upload.upload({
                    url: 'api/file/new',
                    method: 'POST',
                    file: images,
                    headers: {'Content-Type': undefined}
                }).then(function (response) {
                        return response.data.data;
                    },
                    function (response) {
                        return $q.reject(response.data.message);
                    });
            },
            remove: function (img) {
                return $http.delete('api/file/delete', {data: img});
            }
        }
    }
})();
