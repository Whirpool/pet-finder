(function () {
    'use strict';

    angular.module('petFinder').factory('pfPagination', pfPagination);

    pfPagination.$inject = ['$filter'];

    function pfPagination($filter) {
        return {
            setPage: function (pagination) {
                pagination.start = (pagination.currentPage - 1) * pagination.pageSize + (pagination.filteredItems.length ? 1 : 0);
                pagination.startItems = $filter('startFrom')(pagination.filteredItems, pagination.start - 1);
                pagination.pagedItems = $filter('limitTo')(pagination.startItems, pagination.pageSize);
                pagination.end = (pagination.currentPage - 1) * pagination.pageSize + pagination.pagedItems.length;
                return pagination;
            },
            init: function (pageSize, data) {
                var pagination = {};
                pagination.totalItems = data.length;
                pagination.filteredItems = data;
                pagination.numOfPages = Math.ceil(pagination.filteredItems.length / pageSize);
                return pagination;
            },
            showPag: function (total, size) {
                return total > size;
            }
        }
    }
})();
