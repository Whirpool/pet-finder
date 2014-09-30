(function () {
    'use strict';

    angular.module('petFinder').factory('pfMap', pfMap);

    function pfMap() {
        var map,
            validZoom = 12;

        return {
            init: function ($map) {
                map = $map;
            },
            setCoords: function (event) {
                var coords = event.get('coordPosition'),
                    form   = {};

                form.lng = coords[1].toPrecision(6);
                form.lat = coords[0].toPrecision(6);

                map.balloon.open(coords, {
                    contentBody: 'Координаты: ' + [
                        coords[0].toPrecision(6),
                        coords[1].toPrecision(6)
                    ].join(', ')
                });

                return form;
            },
            isZoomValid: function () {
                return map.getZoom() >= validZoom;
            },
            getBounds: function () {
                var bounds,
                    coords = {
                        upLeft: {},
                        downRight: {}
                    };
                bounds = map.getBounds();
                coords.upLeft.lat = bounds[0][0];
                coords.upLeft.lng = bounds[0][1];
                coords.downRight.lat = bounds[1][0];
                coords.downRight.lng = bounds[1][1];
                return coords;
            },
            createGeoObjects: function (pets) {
                var objects = [],
                    self = this;
                angular.forEach(pets, function (value) {
                    objects.push(self.createGeoObject(value));
                });
                return objects;
            },
            createGeoObject: function (pet) {
                var geoObject;
                geoObject = {
                    geometry: {
                        type: 'Point',
                        coordinates: [pet.lat , pet.lng]
                    },
                    properties: {
                        balloonContentBody: '<table class="table table-condensed"><tr><td>Питомец:</td><td>' + pet.pet_id + '</td></tr> ' +
                            '<tr><td>Порода:</td><td>' + pet.breed + '</td></tr>' +
                            '<tr><td>Возраст:</td><td>' + pet.age_id + '</td></tr>' +
                            '<tr><td>Пол:</td><td>' + pet.sex + '</td></tr></table>',
                        clusterCaption: pet.id,
                        iconContent: pet.id
                    }
                };
                return geoObject;
            }
        }
    }
})();
