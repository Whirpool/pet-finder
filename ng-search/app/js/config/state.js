(function () {
    'use strict';

    angular.module('petFinder').config(stateConfig);

    stateConfig.$inject = ['$stateProvider', '$locationProvider', '$urlRouterProvider'];

    function stateConfig($stateProvider, $locationProvider, $urlRouterProvider) {
        $urlRouterProvider.otherwise('/search');
        $locationProvider.hashPrefix('!');

        $stateProvider.state('form', {
            url: '',
            sticky: true,
            deepStateRedirect: true,
            views: {
                'main': {
                    templateUrl: '/ng-search/app/partials/main.html',
                    controller: 'MainCtrl',
                    controllerAs: 'mainVm',
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
            controller: 'NewCtrl',
            controllerAs: 'newVm'
        }).state('form.search', {
            url: '/search',
            templateUrl: '/ng-search/app/partials/search-form.html',
            controller: 'SearchCtrl',
            controllerAs: 'searchVm'
        }).state('form.search.list', {
            url: '/q?{pet:int}&{status:int}&{radius:int}&{lat}&{lng}&{date}&{sex}&{age}&{ageMin}&{ageMax}&{breeds}',
            params: {
                pet: {},
                status: {},
                lat: {},
                lng: {},
                radius: {},
                date: {},
                sex: {},
                age: {value: null},
                ageMin: {value: null},
                ageMax: {value: null},
                breeds: {value: null}
            },
            templateUrl: '/ng-search/app/partials/list.html',
            controller: 'ListCtrl',
            controllerAs: 'listVm',
            resolve: {
                findPets: function (pfPet, $stateParams, $state) {
                    return pfPet.findByLocation($stateParams).then(function () {
                    }, function (error) {
                        console.log(error);
                        $state.go('form.search');
                    })
                }
            }
        });

        $stateProvider.state('detail', {
            url: '/detail/{pet:[a-zA-Z]+}/{status:[a-zA-Z]+}/{id:int}',
            proxy: {
                internal: 'detail.modal',
                external: 'detail.full'
            }
        }).state('detail.modal', {
            onEnter: function ($state, $modal) {
                $modal.open({
                    controller: 'DetailCtrl as detailVm',
                    templateUrl: '/ng-search/app/partials/view.html'
                }).result.then(function () {
                    }, function () {
                        return $state.go('form.search');
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
                    controller: 'DetailCtrl',
                    controllerAs: 'detailVm',
                    templateUrl: '/ng-search/app/partials/view.html'
                }
            }
        });
    }
})();
