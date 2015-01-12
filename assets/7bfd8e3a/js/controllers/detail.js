'use strict';

petFinder.controller('DetailCtrl', ['$scope', '$stateParams',  'pfData', 'pfMap',  'pfHeader', function ($scope, $stateParams, pfData, pfMap, pfHeader) {
    pfData.getDetail($stateParams.id).then(function (pet) {
        $scope.detail = {
            pet: pet,
            header: pfHeader,
            map: {
                center: [pet.lat, pet.lng],
                geoObject: pfMap.createGeoObject(pet)
            }
        };
        if($scope.detail.pet.images !== "undefined" && $scope.detail.pet.images.length > 0) {
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
}]);