describe('CtrlDetail: ', function () {
    var dataServiceMock,
        mapServiceMock,
        scope,
        rootScope,
        getController,
        q,
        NewCtrl,
        response,
        responseImg,
        fakeStateParams,
        timeout;



    beforeEach(module('petFinder'));
    beforeEach(module('stateMock'));
    beforeEach(function () {
        dataServiceMock = jasmine.createSpyObj('pfData', ['getDetail', 'filterResponseData']);
        mapServiceMock  = jasmine.createSpyObj('pfMap', ['isZoomValid', 'getBounds', 'createGeoObject']);
    });
    beforeEach(inject(function ($injector, $rootScope, $controller, $q, $timeout) {
            timeout = $timeout;
            rootScope = $rootScope;
            q = $q;
            scope = $rootScope.$new();
            fakeStateParams = {
                id:1
            };
            responseImg = {
                id:1,
                pet_id:2,
                images: [{source_original: 'img1'}, {source_original: 'img2'}]
            };
            response = {
                id:1,
                pet_id:2,
                images: []
            };
            scope.detail = {
                pet: {},
                map: {
                    geoObject: {}
                }
            };


            dataServiceMock.filterResponseData.and.returnValue({});
            mapServiceMock.createGeoObject.and.returnValue({});
            getController = function () {
                return $controller('DetailCtrl', {
                    $scope: scope,
                    $stateParams: fakeStateParams,
                    pfData: dataServiceMock,
                    pfMap: mapServiceMock
                });
            }
        })
    );

    it('get pet', function () {
        dataServiceMock.getDetail.and.returnValue(q.when(response));
        NewCtrl = getController();
        expect(dataServiceMock.getDetail).toHaveBeenCalled();
        expect(dataServiceMock.getDetail).toHaveBeenCalledWith(fakeStateParams.id);
        timeout.flush();
        expect(scope.detail.pet).toEqual(response);
        expect(scope.mainImage).toBe('images/default.png');
    });

    it('get pet with images', function () {
        dataServiceMock.getDetail.and.returnValue(q.when(responseImg));
        NewCtrl = getController();
        timeout.flush();
        expect(scope.mainImage).toBe('img1');
        scope.changeMainImage('img2');
        expect(scope.mainImage).toBe('img2');

    });
});