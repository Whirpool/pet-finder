describe('newCtrl', function () {
    var dataServiceMock,
        mapMock,
        pageMock,
        scope,
        $httpBackend,
        NewCtrl,
        timeout;


    beforeEach(module('petFinder'));
    beforeEach(module('stateMock'));
    beforeEach(function () {
        dataServiceMock = jasmine.createSpyObj('pfData', ['newPet']);
        mapMock = jasmine.createSpyObj('pfMap', ['isZoomValid', 'getBounds', 'createGeoObjects']);
        pageMock = jasmine.createSpyObj('pfPagination', ['init', 'setPage']);
    });
    beforeEach(inject(function ($injector, $rootScope, $controller, $q, $timeout) {
            scope = $rootScope.$new();
            timeout = $timeout;
            scope.map = {};
            scope.file = {};
            scope.pagination = {};
            scope.model = {
                pets: [],
                formNew: {}
            };
            scope.tab = {
                list: {},
                map: {}
            };

            dataServiceMock.newPet.and.returnValue($q.when('success!'));
            mapMock.createGeoObjects.and.returnValue({});
            pageMock.init.and.returnValue({});
            pageMock.setPage.and.returnValue({});

            NewCtrl = $controller('NewCtrl', {
                $scope: scope,
                pfData: dataServiceMock,
                pfMap: mapMock,
                pfPagination: pageMock
            });
        })
    );

    it('should send form', function () {
        scope.submitForm();
        expect(dataServiceMock.newPet).toHaveBeenCalled();
        timeout.flush();
        expect(scope.model.pets).toEqual([]);
    });
});