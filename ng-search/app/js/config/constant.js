(function () {
    'use strict';

    angular.module('petFinder').constant('pfLabelConst', {
        special: 'Особые приметы:',
        advanced: 'Дополнительно:',
        looking: 'Я ищу:',
        breed: 'Порода:',
        date: 'Дата:',
        name: 'Кличка:',
        age: 'Возраст:',
        pet: 'Питомец:',
        sex: 'Пол:',
        contact: 'Контактные данные:',
        src: 'Фото:',
        lng: 'Широта:',
        lat: 'Долгота:'
    });

    angular.module('petFinder').constant('pfCategoryConst', {
        cat: {
            int: 1,
            string: 'cat'
        },
        dog: {
            int: 2,
            string: 'dog'
        },
        find: {
            int: 1,
            string: 'find'
        },
        lost: {
            int: 2,
            string: 'lost'
        }
    });
})();


