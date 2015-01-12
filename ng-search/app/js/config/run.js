(function () {
    'use strict';

    angular.module('petFinder').run(startParams);

    startParams.$inject = ['$rootScope', '$state', 'pfRelation'];

    function startParams($rootScope, $state) {
        $rootScope.$on('$stateChangeSuccess', function (evt, to, toParams, from, fromParams) {
            if (to.proxy) {
                if (from.name === '') {
                    $state.go(to.proxy.external, toParams);
                } else {
                    $state.go(to.proxy.internal, toParams);
                }
            }
        });
        //$rootScope.$on('$stateChangeError', function (evt, toState, toParams, fromState, fromParams, error) {
        //    console.log(error);
        //    if (fromState.name === '') {
        //        $state.go('form.search');
        //    } else {
        //        $state.go(fromState.name);
        //    }
        //});
    }
})();
