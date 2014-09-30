'use strict';

describe('Filter test', function () {
    var output = [
        {
            id: 1,
            name: 'кошка'
        },
        {
            id:2,
            name: 'собака'
        }
    ];

    beforeEach(module('petFinder'));

    it('should return value from array', inject(function (listFilter) {
        expect(listFilter(1, output)).toEqual('кошка');
        expect(listFilter(2, output)).toEqual('собака');
    }))

});
