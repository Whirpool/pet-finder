'use strict';

petFinder.directive('pfListGallery', function ($timeout) {
    return {
        restrict: "E",
        templateUrl: "ng-search/app/partials/directives/pf-list-gallery.html",
        replace:true,
        scope: {
            images: '='
        },
        link: function (scope) {
            var total,
                interval,
                timer;
            scope.indexImage = 0;
            var imageRandom = function() {
                total = scope.images.length;
                if (total > 0) {
                    scope.indexImage = (scope.indexImage === --total) ? 0 : scope.indexImage + 1;
                    interval = Math.random() * (6000 - 4000) + 4000;
                    timer = $timeout(imageRandom, interval);
                }
            };
            imageRandom();

            scope.$on("$destroy", function(event) {
                    $timeout.cancel(timer);
                }
            );
        }
    }
});