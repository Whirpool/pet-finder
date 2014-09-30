(function () {
    'use strict';

    angular.module('petFinder').config(stateConfig);

    stateConfig.$inject = ['$stateProvider', '$locationProvider', '$urlRouterProvider'];

    function stateConfig($stateProvider, $locationProvider, $urlRouterProvider) {
        $urlRouterProvider.otherwise('/list');
        $locationProvider.hashPrefix('!');

        $stateProvider.state('form', {
            url: '',
            parallel: true,
            deepStateRedirect: true,
            views: {
                'main' : {
                    templateUrl: '/ng-search/app/partials/main.html',
                    controller: 'MainCtrl',
                    resolve: {
                        relations: function (pfRelation) {
                            return pfRelation.load();
                        }
                    }
                }
            }
        }).state('form.new', {
            url: '/new',
            templateUrl: '/ng-search/app/partials/new-form.html',
            controller: 'NewCtrl'
        }).state('form.search', {
            url: '/list',
            templateUrl: '/ng-search/app/partials/search-form.html',
            controller: 'SearchCtrl as search'
        });

        $stateProvider.state('detail', {
            url: '/detail/:id',
            proxy: {
                internal: 'detail.modal',
                external: 'detail.full'
            }
        }).state('detail.modal', {
            onEnter: function($stateParams, $state, $modal) {
                $modal.open({
                    controller : 'DetailCtrl',
                    templateUrl:  '/ng-search/app/partials/view.html'
                }).result.then(function() {}, function() {
                        return $state.transitionTo('form.search');
                    });
            }
        }).state('detail.full', {
            views: {
                'detail@': {
                    resolve: {
                        relations: function (pfRelation) {
                            return pfRelation.load();
                        }
                    },
                    controller : 'DetailCtrl',
                    templateUrl:  '/ng-search/app/partials/view.html'
                }
            }
        });
    }
})();
