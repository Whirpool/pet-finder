<?php

/**
 * Class TCat
 * Установка параметров свойственных питомцам типа Cat
 * @property string $breedRelation Имя реляционной связи свзывающей таблицы Many To Many
 * @property string $breedModel Имя модели Many To Many
 */
trait TCat
{
    protected $breedRelation = 'catBreeds';

    protected $breedModel = LCatBreed::class;

    /**
     * Возвращает реляционные связи для питомцев типа Cat
     * @return string
     */
    protected function getPetJoins()
    {
        $joins = 'join lookup_cat_type_color type_color on (t.type_color = type_color.id) ';
        $joins .= 'join map_cat_breed map on (t.id = map.id_cat) ';
        $joins .= 'join lookup_cat_breed breed on (map.id_breed = breed.id) ';

        return $joins;
    }
}