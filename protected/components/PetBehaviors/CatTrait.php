<?php

/**
 * Class CatTrait
 * Установка параметров свойственных питомцам типа Cat
 * @property string $breedRelation Имя реляционной связи свзывающей таблицы Many To Many
 * @property array $breeds Содержит породы питомцев. Необходим для реализации реляционной связи MANY_TO_MANY.
 */
trait CatTrait
{
    protected $breedRelation = 'catBreeds';

    protected $breeds;


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
            $b = new LCatBreed;
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