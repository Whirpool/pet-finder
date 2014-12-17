(function () {
    'use strict';

    angular.module('petFinder').controller('DetailCtrl', DetailCtrl);

    DetailCtrl.$inject = ['$scope', '$stateParams', 'pfData', 'pfMap', 'pfHeader'];

    function DetailCtrl($scope, $stateParams, pfData, pfMap, pfHeader) {
        pfData.getDetail($stateParams.type, $stateParams.status, $stateParams.id).then(function (data) {
            $scope.detail = {
                pet: data.pet[0],
                category: data.category,
                header: pfHeader,
                map: {
                    center: [data.pet[0].lat, data.pet[0].lng],
                    geoObject: pfMap.createGeoObject(data.pet[0])
                }
            };
            if (!!$scope.detail.pet.images && $scope.detail.pet.images.length > 0) {
                $scope.mainImage = $scope.detail.pet.images[0].source_original;
            } else {
                $scope.mainImage = 'images/default.png';
            }
        }, function (error) {
            $scope.detailError = error;
        });

        $scope.changeMainImage = function (src) {
            $scope.mainImage = src;
        };
    }
})();
