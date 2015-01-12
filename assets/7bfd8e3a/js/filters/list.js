'use strict';

petFinder.filter('list', function () {
    return function (value, relation) {
        return relation[value-1]['name'];
    }
});