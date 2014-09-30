(function () {
    'use strict';

    angular.module('petFinder').run(startParams);

    startParams.$inject = ['$rootScope', '$state', 'pfRelation'];

    function startParams($rootScope, $state) {
        $rootScope.$on('$stateChangeSuccess', function(evt, to, toParams, from, fromParams) {
            if(to.proxy) {
                if(from.name === '')
                    $state.transitionTo(to.proxy.external, toParams);
                else
                    $state.transitionTo(to.proxy.internal, toParams);
            }
        });
    }
})();
