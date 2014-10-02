'use strict';

describe('FilterStartForm: ', function () {
    var output = [
        {id: 1, name: 'кошка'},
        {id: 2, name: 'собака'},
        {id: 3, name: 'хорек'}
    ];

    beforeEach(module('petFinder'));

    it('should return value from array', inject(function (startFromFilter) {
        expect(startFromFilter(output, 0)).toEqual(output);
        expect(startFromFilter(output, 1)).toEqual([
            {id: 2, name: 'собака'},
            {id: 3, name: 'хорек'}
        ]);
        expect(startFromFilter(output, 2)).toEqual([{id: 3, name: 'хорек'}]);
    }));


});