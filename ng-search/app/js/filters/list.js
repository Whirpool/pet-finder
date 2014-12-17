(function () {
    'use strict';

    angular.module('petFinder').filter('init', init);

    function init() {
        return function (value) {
            var type,
                status;
            switch(value.type) {
                case (1):
                    type = 'cat';
                    break;
                case (2):
                    type = 'dog';
                    break;
                default:
                    type = null;
                    break;
            }
            switch(value.status) {
                case (1):
                    status = 'find';
                    break;
                case (2):
                    status = 'lost';
                    break;
                default:
                    status = null;
                    break;
            }
            return {type: type, status: status};
        }
    }
})();
