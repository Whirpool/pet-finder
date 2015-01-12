'use strict';

petFinder.factory('pfRelation', ['$resource', function ($resource) {
    var listData = [],
        load = $resource('api/relation', {}, {relation: {method: 'GET', isArray:false}});
        listData = load.relation();

    return function () {
        return listData;
    }
}]);