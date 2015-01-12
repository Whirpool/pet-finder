(function () {
    'use strict';

    angular.module('petFinder').factory('pfImage', pfImage);

    pfImage.$inject = ['$http', '$q', '$upload'];

    function pfImage($http, $q, $upload) {
        return {
            add: add,
            remove: remove
        };

        function add(images) {
            return $upload.upload({
                url: 'api/file',
                method: 'POST',
                file: images,
                headers: {'Content-Type': undefined}
            }).then(function (response) {
                    return response.data.model;
                },
                function (response) {
                    return $q.reject(response.data.message);
                });
        }

        function remove(image) {
            return $http.delete('api/file', {data: image});
        }
    }
})();
