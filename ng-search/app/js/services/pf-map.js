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
            getLocation: function () {
                var location = {},
                    center = map.getCenter(),
                    bounds = map.getBounds();
                location.lat = center[0];
                location.lng = center[1];
                location.radius = Math.ceil(ymaps.coordSystem.geo.getDistance(center, bounds[0]));
                return location;
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
