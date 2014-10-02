describe('CtrlNew: ', function () {
    var dataServiceMock,
        mapMock,
        imageMock,
        pageMock,
        scope,
        location,
        img,
        httpBackend,
        NewCtrl,
        timeout;


    beforeEach(module('petFinder'));
    beforeEach(module('stateMock'));
    beforeEach(function () {
        dataServiceMock = jasmine.createSpyObj('pfData', ['newPet']);
        imageMock = jasmine.createSpyObj('pfImage', ['add', 'remove']);
        mapMock = jasmine.createSpyObj('pfMap', ['isZoomValid', 'getBounds', 'createGeoObjects', 'setCoords']);
        pageMock = jasmine.createSpyObj('pfPagination', ['init', 'setPage']);
    });
    beforeEach(inject(function ($rootScope, $controller, $q, $timeout, $location, $httpBackend) {
            scope = $rootScope.$new();
            timeout = $timeout;
            location = $location;
            httpBackend = $httpBackend;
            img = {source_original: 'img1'};
            scope.map = {};
            scope.images = [];
            scope.pagination = {};
            scope.model = {
                formNew: {}
            };
            scope.tab = {
                list: {},
                map: {}
            };


            dataServiceMock.newPet.and.returnValue($q.when('success!'));
            imageMock.add.and.returnValue($q.when(img));
            imageMock.remove.and.returnValue($q.when('success!'));
            mapMock.createGeoObjects.and.returnValue({});
            mapMock.setCoords.and.returnValue({});
            pageMock.init.and.returnValue({});
            pageMock.setPage.and.returnValue({});

            NewCtrl = $controller('NewCtrl', {
                $scope: scope,
                pfData: dataServiceMock,
                pfImage: imageMock,
                pfMap: mapMock,
                pfPagination: pageMock
            });
        })
    );

    it('should send form', function () {
        scope.submitForm();
        expect(dataServiceMock.newPet).toHaveBeenCalled();
        timeout.flush();
        expect(location.path()).toBe('/list')
    });

    it('add file', function () {
        scope.onFileSelect([{source_original: 'img1'}]);
        expect(imageMock.add).toHaveBeenCalled();
        timeout.flush();
        expect(scope.images.length).toBe(1);
        expect(scope.images[0].source_original).toBe('img1');
    });

    it('remove file', function () {
        scope.onFileSelect([{source_original: 'img1'}]);
        timeout.flush();
        expect(scope.images.length).toBe(1);
        scope.deleteImage({source_original: 'img1'}, 0);
        expect(imageMock.remove).toHaveBeenCalled();
        expect(imageMock.remove).toHaveBeenCalledWith({source_original: 'img1'});
        timeout.flush();
        expect(scope.images.length).toBe(0);
    });

    it('set coords', function () {
        location.path('/new');
        scope.map.mapClick();
        expect(mapMock.setCoords).toHaveBeenCalled();
        timeout.flush();
    });
});