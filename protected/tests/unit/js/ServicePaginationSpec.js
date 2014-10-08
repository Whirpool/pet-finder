describe('Service Pagination: ', function () {
    var mockFilter,
        paginationService,
        pag,
        data = [{id: 1}, {id: 2}, {id: 3}, {id: 4}];
    beforeEach(module('petFinder'));
    beforeEach(mockFilter = function () {
        return [{id: 1}, {id: 2}]
    });
    beforeEach(module(function ($provide) {
            $provide.value('startFromFilter', mockFilter);
        })
    );
    beforeEach(inject(function ($injector) {
            paginationService = $injector.get('pfPagination');
        })
    );

    it('should set page', function () {
        pag = paginationService.init(data);

        expect(init.numOfPages).toEqual(1);
        expect(init.totalItems).toEqual(4);

        paginationService.setPage();
        pag = paginationService.getPagination();


        expect(pag.start).toEqual(1);
        expect(pag.end).toEqual(2);

        pag.currentPage = 2;

        paginationService.setPage();
        pag = paginationService.getPagination();

        expect(pag.start).toEqual(7);
        expect(pag.end).toEqual(8);
    });
});