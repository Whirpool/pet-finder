'use strict';

describe('Relation service', function() {
    var $httpBackend,
        injector,
        pfRelation,
        state,
        responseAge = [
            {
                id: 1,
                name: 'до 1 года'
            }
         ],
        responsePet = [
            {
                id: 1,
                name: 'кошка'
            }
        ];

    beforeEach(module('petFinder'));
    beforeEach(module('stateMock'));
    beforeEach(inject(function ($injector, $state) {
        state = $state;
        injector = $injector;
        $httpBackend = $injector.get('$httpBackend');
        pfRelation = $injector.get('pfRelation');
        $httpBackend.when('GET', '/api/relation').respond(responsePet);
    }));

    afterEach(function() {
        $httpBackend.verifyNoOutstandingExpectation();
        $httpBackend.verifyNoOutstandingRequest();
    });

    it('should send two request', function () {
        $httpBackend.expectGET('/api/relation');
        pfRelation.load();
        $httpBackend.flush();
    });
});