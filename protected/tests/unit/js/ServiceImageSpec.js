'use strict';

describe('Service Relation:', function () {
    var $httpBackend,
        injector,
        pfImage,
        requestImage = {name_default: 'img1'},
        responseImage = {name_original: 'md5_img1'};

    beforeEach(module('petFinder'));
    beforeEach(module('stateMock'));
    beforeEach(inject(function ($injector) {
        injector = $injector;
        $httpBackend = $injector.get('$httpBackend');
        pfImage = $injector.get('pfImage');
        $httpBackend.when('POST', 'api/file/new').respond(responseImage);
        $httpBackend.when('DELETE', 'api/file/delete').respond(200);
    }));

    afterEach(function () {
        $httpBackend.verifyNoOutstandingExpectation();
        $httpBackend.verifyNoOutstandingRequest();
    });

    it('add', function () {
        $httpBackend.expectPOST('api/file/new');
        pfImage.add(requestImage);
        $httpBackend.flush();
    });

    it('remove', function () {
        $httpBackend.expectDELETE('api/file/delete');
        pfImage.remove(requestImage);
        $httpBackend.flush();
    });
});