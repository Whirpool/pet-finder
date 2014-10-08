(function () {
    'use strict';

    angular.module('petFinder').factory('pfPagination', pfPagination);

    pfPagination.$inject = ['$filter'];

    function pfPagination($filter) {
        var pagination = {
            pageSize: 6,
            maxSize: 5,
            currentPage: 1,
            numOfPages: 0,
            start: 0,
            end: 0,
            startItems: [],
            filteredItems: [],
            pagedItems: [],
            totalItems: 0,
            show: false
            };
        return {
            setPage: function () {
                pagination.start = (pagination.currentPage - 1) * pagination.pageSize + (pagination.filteredItems.length ? 1 : 0);
                pagination.startItems = $filter('startFrom')(pagination.filteredItems, pagination.start - 1);
                pagination.pagedItems = $filter('limitTo')(pagination.startItems, pagination.pageSize);
                pagination.end = (pagination.currentPage - 1) * pagination.pageSize + pagination.pagedItems.length;
            },
            getPagination: function () {
                return pagination;
            },
            init: function (data) {
                var self = this;
                pagination.totalItems = data.length;
                pagination.filteredItems = data;
                pagination.numOfPages = Math.ceil(pagination.filteredItems.length / pagination.pageSize);
                pagination.show = (pagination.numOfPages > 1);
                self.setPage();
                return self.getPagination();
            }
        }
    }
})();
