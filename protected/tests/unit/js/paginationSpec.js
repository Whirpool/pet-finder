describe('pagination service', function () {
    var mockFilter,
        paginationService,
        init,
        pagination = {
        pageSize: 2,
        maxSize: 5,
        currentPage: 1,
        numOfPages: 0,
        start: 0,
        end: 0,
        startItems: [],
        filteredItems: [],
        pagedItems: [],
        totalItems: 0
        },
        data = [
            {
                id:1
            },
            {
                id:2
            },
            {
                id:3
            },
            {
                id:4
            }
        ];
    beforeEach(module('petFinder'));
    beforeEach(mockFilter = function () {
        return [{id:1}, {id:2}]
    });
    beforeEach(module(function($provide) {
            $provide.value('startFromFilter', mockFilter);
        })
    );
    beforeEach(inject(function ($injector) {
                paginationService = $injector.get('pfPagination');
            })
    );

    it('should set page', function () {
        init = paginationService.init(2, data);

        angular.extend(pagination, init);

        expect(pagination.numOfPages).toEqual(2);
        expect(pagination.totalItems).toEqual(4);

        pagination = paginationService.setPage(pagination);

        expect(pagination.start).toEqual(1);
        expect(pagination.end).toEqual(2);

        pagination.currentPage = 2;

        pagination = paginationService.setPage(pagination);

        expect(pagination.start).toEqual(3);
        expect(pagination.end).toEqual(4);
    });
});