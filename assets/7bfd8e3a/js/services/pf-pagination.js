'use strict';

petFinder.factory('pfPagination', ['$filter', function ($filter) {
    return {
        setPage: function (pagination) {
            pagination.start = (pagination.currentPage - 1) * pagination.pageSize + (pagination.filteredItems.length ? 1 : 0);
            pagination.startItems = $filter('startFrom')(pagination.filteredItems, pagination.start - 1);
            pagination.pagedItems = $filter('limitTo')(pagination.startItems, pagination.pageSize);
            pagination.end = (pagination.currentPage - 1) * pagination.pageSize + pagination.pagedItems.length;
            return pagination;
        },
        setStartSettings: function (pagination, data) {
            pagination.totalItems = data.length;
            pagination.filteredItems = data;
            pagination.numOfPages = Math.ceil(pagination.filteredItems.length / pagination.pageSize);
            return pagination;
        }
    }
}]);
