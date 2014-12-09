<?php

/**
 * Class DogTrait
 * Установка параметров свойственных питомцам типа Dog
 * @property string $breedRelation Имя реляционной связи свзывающей таблицы Many To Many
 * @property array $breeds Содержит породы питомцев. Необходим для реализации реляционной связи MANY_TO_MANY.
 */
trait DogTrait
{
    protected $breedRelation = 'dogBreeds';

    protected $breeds;

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
        $join  = 'join lookup_dog_type_color type_color on (t.type_color = type_color.id) ';
        $join .= 'join map_dog_breed map on (t.id = map.id_dog) ';
        $join .= 'join lookup_dog_breed breed on (map.id_breed = breed.id) ';
        $join .= 'join lookup_dog_type_figured figured on (t.figured = figured.id) ';
        return $join;
    }

    /**
     * Устанавливает породы питомца
     * @param array $breeds
     *
     * @throws CException
     */
    public function setBreeds(array $breeds)
    {
        $breedArray = [];
        foreach ($breeds as $breed) {
            $b = new LDogBreed;
            $b->value = $breed;
            if ($b->validate()) {
                $breedArray[] = $b->value;
            } else {
                throw new CException('Неверный формат породы питомца. Выберите породу из списка.');
            }
        }
        $this->breeds = $breedArray;
    }

}