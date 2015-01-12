(function () {
    'use strict';

    angular.module('petFinder').factory('pfMap', pfMap);


    function pfMap() {
        var map,
            validZoom = 12,
            geoObjects = [],
            center = [];

        return {

            init: init,
            getCoords: getCoords,
            isZoomValid: isZoomValid,
            getLocation: getLocation,
            setGeoObjects: setGeoObjects,
            getGeoObjects: getGeoObjects,
            createGeoObject: createGeoObject,
            createPoint: createPoint,
            setCenter: setCenter,
            getCenter: getCenter,
            clearGeoObjects: clearGeoObjects
        };

        function init($map) {
            map = $map;
        }

        function getCoords(event) {
            var coords = event.get('coordPosition'),
                form = {};

            form.lng = coords[1].toPrecision(6);
            form.lat = coords[0].toPrecision(6);

            map.balloon.open(coords, {
                contentBody: 'Координаты: ' + [
                    coords[0].toPrecision(6),
                    coords[1].toPrecision(6)
                ].join(', ')
            });

            return form;
        }

        function isZoomValid() {
            return map.getZoom() >= validZoom;
        }

        function getLocation() {
            var location = {},
                center = map.getCenter(),
                bounds = map.getBounds();
            location.lat = center[0];
            location.lng = center[1];
            location.radius = Math.ceil(ymaps.coordSystem.geo.getDistance(center, bounds[0]));
            return location;
        }

        function setGeoObjects(pets) {
            var self = this;
            angular.forEach(pets, function (value) {
                geoObjects.push(self.createGeoObject(value));
            });
        }

        function getGeoObjects() {
            return geoObjects;
        }

        function createGeoObject(pet) {
            return {
                geometry: {
                    type: 'Point',
                    coordinates: [pet.lat, pet.lng]
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
        }

        function createPoint(lat, lng) {
            return {
                geometry: {
                    type: 'Point',
                    coordinates: [lat, lng]
                }
            };
        }

        function setCenter(lat, lng) {
            center.push(lat, lng);
        }

        function getCenter() {
            return center;
        }

        function clearGeoObjects() {
            geoObjects = [];
        }
    }
})();
