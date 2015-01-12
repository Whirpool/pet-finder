'use strict';

var petFinder = angular.module('petFinder', ['ui.router', 'ngResource', 'ui.bootstrap', 'yaMap', 'angularFileUpload', 'ngAnimate']);

petFinder.config(['$stateProvider', '$locationProvider', '$urlRouterProvider', function ($stateProvider, $locationProvider, $urlRouterProvider) {
    $urlRouterProvider.otherwise("/list");
    $locationProvider.hashPrefix('!');

    $stateProvider.state('form', {
        url: '',
        parallel: true,
        deepStateRedirect: true,
        views: {
            'main' : {
                templateUrl: 'ng-search/app/partials/main.html',
                controller: 'MainCtrl'
            }
        }
    }).state('form.new', {
        url: "/new",
        templateUrl: 'ng-search/app/partials/new-form.html',
        controller: 'NewCtrl'
    }).state('form.search', {
        url: "/list",
        templateUrl: 'ng-search/app/partials/search-form.html',
        controller: 'SearchCtrl'
    });

    $stateProvider.state('detail', {
        url: '/detail/:id',
        proxy: {
            internal: 'detail.modal',
            external: 'detail.full'
        }
    }).state("detail.modal", {
        onEnter: function($stateParams, $state, $modal) {
            $modal.open({
                controller : 'DetailCtrl',
                templateUrl:  "ng-search/app/partials/view.html"
            }).result.then(function() {}, function() {
                    return $state.transitionTo("form.search");
                });
        }
    }).state('detail.full', {
        views: {
            'detail@': {
                controller : 'DetailCtrl',
                templateUrl:  "ng-search/app/partials/view.html"
            }
        }
    });
}]).run(function($rootScope, $state, pfRelation) {
    $rootScope.$on('$stateChangeSuccess', function(evt, to, toParams, from, fromParams) {
        if(to.proxy) {
            if(from.name === '')
                $state.transitionTo(to.proxy.external, toParams);
            else
                $state.transitionTo(to.proxy.internal, toParams);
        }
    });
});

petFinder.constant('pfHeader', {
    special: 'Особые приметы:',
    advanced: 'Дополнительно:',
    looking: 'Я ищу:',
    breed: 'Порода:',
    date: 'Дата:',
    name: 'Кличка:',
    age: 'Возраст:',
    pet: 'Питомец:',
    sex: 'Пол:',
    contact: 'Контактные данные:',
    src: 'Фото:',
    lng: 'Широта:',
    lat: 'Долгота:'
});
