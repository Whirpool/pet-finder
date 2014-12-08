<?php

/**
 * Class TDog
 * Установка параметров свойственных питомцам типа Dog
 * @property string $breedRelation Имя реляционной связи свзывающей таблицы Many To Many
 * @property string $breedModel Имя модели Many To Many
 */
trait TDog
{
    protected $breedRelation = 'dogBreeds';

    protected $breedModel = LDogBreed::class;

    /**
     * Возвращает колонки для питомцев типа Dog
     * @return string
     */
    protected function getPetColumns()
    {
        $select = 'min(figured.value) as figured';
        return $select;
    }

    /**
     * Возвращает реляционные связи для питомцев типа Dog
     * @return string
     */
    protected function getPetJoins()
    {
        $join = 'join lookup_dog_type_color type_color on (t.type_color = type_color.id) ';
        $join .= 'join map_dog_breed map on (t.id = map.id_dog) ';
        $join .= 'join lookup_dog_breed breed on (map.id_breed = breed.id) ';
        $join .= 'join lookup_dog_type_figured figured on (t.figured = figured.id) ';
        return $join;
    }

}