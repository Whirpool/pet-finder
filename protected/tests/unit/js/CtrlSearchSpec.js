'use strict';

describe('CtrlSearch: ', function () {
    var dataServiceMock,
        mapMock,
        pageMock,
        $timeout,
        SearchCtrl,
        mockFilter = function () {},
        $scope;
    beforeEach(module('petFinder'));
    beforeEach(module('stateMock'));
    beforeEach(module(function($provide) {
        $provide.value('listFilter', mockFilter);
    }));
    beforeEach(function () {
        dataServiceMock = jasmine.createSpyObj('pfData', ['findPet']);
        mapMock = jasmine.createSpyObj('pfMap', ['isZoomValid', 'getBounds', 'createGeoObjects']);
        pageMock = jasmine.createSpyObj('pfPagination', ['init', 'setPage', 'showPag']);
    });
    beforeEach(inject(function ($rootScope, $controller, $q, _$timeout_) {
            $scope = $rootScope.$new();
            $scope.map = {};
            $scope.pagination = {};
            $scope.tab = {
                list: {}
            };
            $scope.model = {
                pets: {},
                formSearch: {}
            };
            dataServiceMock.findPet.and.returnValue($q.when('success!'));
            mapMock.isZoomValid.and.returnValue(true);
            mapMock.getBounds.and.returnValue([{}, {}]);
            mapMock.createGeoObjects.and.returnValue([]);
            pageMock.init.and.returnValue({});

            $timeout = _$timeout_;
            SearchCtrl = $controller('SearchCtrl', {
                $scope: $scope,
                pfData: dataServiceMock,
                pfMap: mapMock,
                pfPagination: pageMock
            });
        })
    );

    it('should update pet list after call method submitForm', function () {
        $scope.submitForm();
        expect(dataServiceMock.findPet).toHaveBeenCalled();
        expect(mapMock.isZoomValid).toHaveBeenCalled();
        expect(mapMock.getBounds).toHaveBeenCalled();
        $timeout.flush();
        expect(mapMock.createGeoObjects).toHaveBeenCalled();
        expect(pageMock.init).toHaveBeenCalled();
        expect($scope.model.pets).toEqual('success!');
    });
});