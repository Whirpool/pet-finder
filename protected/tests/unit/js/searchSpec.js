'use strict';

describe('SearchCtrl', function () {
    var dataServiceMock,
        mapMock,
        pageMock,
        $timeout,
        SearchCtrl,
        $scope;
    beforeEach(module('petFinder'));
    beforeEach(module('stateMock'));
    beforeEach(function () {
        dataServiceMock = jasmine.createSpyObj('pfData', ['findPet']);
        mapMock = jasmine.createSpyObj('pfMap', ['isZoomValid', 'getBounds', 'createGeoObjects']);
        pageMock = jasmine.createSpyObj('pfPagination', ['init', 'setPage']);
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
            mapMock.getBounds.and.returnValue([43.43, 43.32]);
            mapMock.createGeoObjects.and.returnValue({});
            pageMock.init.and.returnValue({});
            pageMock.setPage.and.returnValue({});

            $timeout = _$timeout_;
            SearchCtrl = $controller('SearchCtrl', {
                $scope: $scope,
                pfData: dataServiceMock,
                pfMap: mapMock
            });
        })
    );

    it('should update pet list after call method sendForm', function () {
        $scope.submitForm();
        expect(dataServiceMock.findPet).toHaveBeenCalled();
        $timeout.flush();
        expect($scope.model.pets).toEqual('success!');
    });
});