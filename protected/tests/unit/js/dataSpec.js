'use strict';

describe('Data Service', function () {
    var dataService,
        $httpBackend,
        mockFilter,
        request = {pet_type: 1},
        response = [{id: 1}],
        relation = {};

    beforeEach(module('petFinder'));
    beforeEach(module('stateMock'));
    beforeEach(
        inject(function ($injector) {
            dataService = $injector.get('pfData');
            $httpBackend = $injector.get('$httpBackend');
            $httpBackend.when('POST', 'api/pet/search').respond(response);

        })

        );

    afterEach(function () {
        $httpBackend.verifyNoOutstandingExpectation();
        $httpBackend.verifyNoOutstandingRequest();
    });

    it('should send request', function () {
        $httpBackend.expectPOST('api/pet/search');
        dataService.findPet(request);
        $httpBackend.flush();
    });
});