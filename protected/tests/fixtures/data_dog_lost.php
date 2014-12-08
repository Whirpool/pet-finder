<?php
$lat = 61.45;
$lng = 55.15;
return array(
    0=>array(
        'name' => 'Вася',
        'age_id' => 4,
        'sex' => 'мужской',
        'date' => 1390240800,
        'date_create' => 1390260843,
        'date_update' => 1390260843,
        'author_id' => 1,
        'location' => new CDbExpression('POINT(:lat, :lng)', array(':lat' => $lat, ':lng' => $lng)),
    ),
    1=>array(
        'pet_id' => 1,
        'breed' => 'Сибирская',
        'name' => 'Петя',
        'age_id' => 5,
        'sex' => 'женский',
        'date' => 1390240800,
        'date_create' => 1390260843,
        'date_update' => 1390260843,
        'location' => new CDbExpression('POINT(:lat, :lng)', array(':lat' => $lat, ':lng' => $lng)),
    ),
);