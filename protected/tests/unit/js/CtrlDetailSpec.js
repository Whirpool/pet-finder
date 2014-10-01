describe('CtrlDetail', function () {
    var dataServiceMock,
        mapServiceMock,
        scope,
        rootScope,
        $httpBackend,
        defer,
        NewCtrl,
        pfData,
        stateParamsfk,
        timeout;



    beforeEach(module('petFinder'));
    beforeEach(module('stateMock'));
    beforeEach(function () {
        dataServiceMock = jasmine.createSpyObj('pfData', ['getDetail']);
        mapServiceMock  = jasmine.createSpyObj('pfMap', ['isZoomValid', 'getBounds', 'createGeoObjects']);
    });
    beforeEach(inject(function ($injector, $rootScope, $controller, $q, $timeout) {
            rootScope = $rootScope;
            scope = $rootScope.$new();
            timeout = $timeout;
            defer = $q.defer();
            stateParamsfk = {
                id:1
            };
            scope.map = {};
            scope.detail = {
                pets: []
            };


            dataServiceMock.getDetail.and.returnValue(defer.promise);
            mapServiceMock.createGeoObjects.and.returnValue({});

            NewCtrl = $controller('DetailCtrl', {
                $scope: scope,
                $stateParams: stateParamsfk,
                pfData: dataServiceMock,
                pfMap: mapServiceMock
            });
        })
    );

    it('should send form', function () {
        defer.resolve('resolveData');
        expect(dataServiceMock.getDetail).toHaveBeenCalled();
        expect(dataServiceMock.getDetail).toHaveBeenCalledWith(1);
        timeout.flush();
        expect(scope.detail.pets).toEqual('resolveData');
    });
});