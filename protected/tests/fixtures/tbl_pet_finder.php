<?php
$lat = 61.45;
$lng = 55.15;
return array(
    0=>array(
        'pet_id' => 1,
        'breed' => 'персидская',
        'name' => 'Вася',
        'age_id' => 4,
        'sex' => 1,
        'date' => 1390240800,
        'date_create' => 1390260843,
        'date_update' => 1390260843,
        'search_type' => 1,
        'search_status' => 1,
        'author_id' => 1,
        'lng' => 55.15,
        'lat' => 61.45,
        'coords' => new CDbExpression('POINT(:lat, :lng)', array(':lat' => $lat, ':lng' => $lng)),
    ),
    1=>array(
        'pet_id' => 1,
        'breed' => 'Сибирская',
        'name' => 'Петя',
        'age_id' => 5,
        'sex' => 2,
        'date' => 1390240800,
        'date_create' => 1390260843,
        'date_update' => 1390260843,
        'search_type' => 1,
        'search_status' => 1,
        'author_id' => 1,
        'lng' => 55.15,
        'lat' => 61.45,
        'coords' => new CDbExpression('POINT(:lat, :lng)', array(':lat' => $lat, ':lng' => $lng)),
    ),
);