<?php

/**
 * Interface SearchByLocationInterface
 */
interface SearchByLocationInterface
{
    /**
     * Максимально допустимый радиус поиска питомца в метрах.
     */
    const VALID_RADIUS = 3000;

    /**
     * Формирование запроса для поиска питомцев по геологическому местоположению
     * @param $data
     *
     * @return array|null
     */
    public function findPetByLocation($data);

    /**
     * Валидация радиуса поиска вводимого пользователем.
     * @param $radius
     *
     * @return mixed
     */
    public function isZoomValid($radius);

    /**
     * Возвращает колонки содержащие местоположение питомца
     * @return mixed
     */
    public function getLocationColumns();

    /**
     * Устанавливает условия выборки для поиска по местоположению
     * @param $data
     *
     * @return mixed
     */
    public function setLocationConditions($data);

    /**
     * Устанавливает параметры запроса для поиска по местоположению
     * @param $params
     *
     * @return mixed
     */
    public function setLocationParams($params);
}