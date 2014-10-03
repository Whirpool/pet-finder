'use strict';

describe('Service Relation:', function () {
    var $httpBackend,
        injector,
        pfRelation,
        relation,
        responsePet = {
            pet: [{id: 1, name: 'кошка'}],
            age: [{id: 1, name: 'до 3 лет'}, {id: 2,name: 'до 5 лет'}]
        };

    beforeEach(module('petFinder'));
    beforeEach(module('stateMock'));
    beforeEach(inject(function ($injector) {
        injector = $injector;
        $httpBackend = $injector.get('$httpBackend');
        pfRelation = $injector.get('pfRelation');
        $httpBackend.when('GET', '/api/relation').respond(responsePet);
    }));

    afterEach(function () {
        $httpBackend.verifyNoOutstandingExpectation();
        $httpBackend.verifyNoOutstandingRequest();
    });

    it('load', function () {
        $httpBackend.expectGET('/api/relation');
        pfRelation.load();
        $httpBackend.flush();
    });

    it('get', function () {
        pfRelation.load();
        $httpBackend.flush();
        relation = pfRelation.get();
        expect(relation.pet[0].id).toBe(1);
        expect(relation.age[1].name).toBe('до 5 лет');
    });
});