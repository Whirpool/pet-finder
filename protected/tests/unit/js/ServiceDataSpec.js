'use strict';

describe('Service Data: ', function () {
    var dataService,
        $httpBackend,
        rootScope,
        relationService,
        request = {pet_type: 1},
        response = [
            {pet_id: 1, age_id:1, sex:1}
        ],
        responseOne = {pet_id: 1, age_id:2, sex:2, date: 123213, date_create: 123213, date_update: 123213},
        relation = {
            pet: [{type_id: 1, name: 'кошка'}, {type_id: 2, name: 'собака'}],
            age: [{type_id: 1, name: 'lj1'}, {type_id: 2, name: 'собака'}],
            sex: [{type_id: 1, name: 'lj1'}, {type_id: 2, name: 'женский'}]
        };

    beforeEach(module('petFinder'));
    beforeEach(module('stateMock'));
    beforeEach(inject(function ($injector, $rootScope) {
            dataService = $injector.get('pfData');
            rootScope = $rootScope;
            relationService = $injector.get('pfRelation');
            $httpBackend = $injector.get('$httpBackend');
            $httpBackend.when('POST', 'api/pet/search').respond(response);
            $httpBackend.when('POST', 'api/pet/new').respond(200);
            $httpBackend.when('POST', 'api/comment/new').respond(200);
            $httpBackend.when('POST', 'api/answer/new').respond(200);
            $httpBackend.when('GET', 'api/pet/view?id=1').respond(responseOne);
            spyOn(relationService, 'get').and.returnValue(relation);
            spyOn(dataService, 'filterResponseData').and.callThrough();
        })
        );

    afterEach(function () {
        $httpBackend.verifyNoOutstandingExpectation();
        $httpBackend.verifyNoOutstandingRequest();
    });

    it('find pet', function () {
        $httpBackend.expectPOST('api/pet/search');
        dataService.findPet(request);
        $httpBackend.flush();
        expect(dataService.filterResponseData).toHaveBeenCalled();
    });

    it('new pet', function () {
        $httpBackend.expectPOST('api/pet/new');
        dataService.newPet(response);
        $httpBackend.flush();
    });


    it('get detail', function () {
        $httpBackend.expectGET('api/pet/view?id=1');
        dataService.getDetail(1);
        $httpBackend.flush();
        expect(dataService.filterResponseData).toHaveBeenCalled();
    });

    it('get detail from rootScope', function () {
        rootScope.pets = [ {id: 1, pet_id: 1, age_id:1, sex:1},  {id: 2, pet_id: 2, age_id:3, sex:2}];
        dataService.getDetail(2);
    });

    it('send comment', function () {
        $httpBackend.expectPOST('api/comment/new');
        dataService.sendMessage(1, request, 'comment');
        $httpBackend.flush();
    });

    it('send answer', function () {
        $httpBackend.expectPOST('api/answer/new');
        dataService.sendMessage(1, request, 'answer');
        $httpBackend.flush();
    });

    it('filterResponseData', function () {
        var test = dataService.filterResponseData(responseOne);
        expect(test.pet_id).toBe('кошка');
        expect(test.age_id).toBe('собака');
        expect(test.sex).toBe('женский');
        expect(test.date).toBe('02-01-1970');
        expect(test.date_create).toBe('02-01-1970');
        expect(test.date_update).toBe('02-01-1970');
    });
});