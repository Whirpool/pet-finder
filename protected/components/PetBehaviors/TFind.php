<?php

/**
 * Class TFind
 * Установка параметров свойственных питомцам со статусом Find
 * @property $criteria CDbCriteria
 * @property $command CDbCommand
 */
trait TFind
{
    /**
     * Устанавливает условия выборки для питомцев со статусом Find
     * @param $data
     */
    protected function setStatusConditions($data)
    {
        $age = (float)$data['age'];
        $this->criteria->addCondition("{$age} <@ age");
        $this->criteria->addCondition('date BETWEEN :dateStart AND :dateEnd');

        if (isset($data['breeds'])) {
            $pgArray = $this->toPgArray($data['breeds'], 'full');
            $this->command->where($pgArray . ' && x.breeds');
        }
    }

    /**
     * Возвращает колонки для питомцев со статусом Find
     * @return string
     */
    protected function getStatusColumns()
    {
        $select = 'lower(t.age) as age_min, upper(t.age) as age_max';
        return $select;
    }

    /**
     * Устанавливает параметры для питомцев со статусом Find
     * @param $data
     */
    protected function setStatusParams($data)
    {
        $dateStart = DateTime::createFromFormat('d-m-Y', $data['date']);
        $dateEnd = new DateTime();
        $this->command->bindParam(':dateEnd', $dateEnd->format('Y-m-d'), PDO::PARAM_STR);
        $this->command->bindParam(':dateStart', $dateStart->format('Y-m-d'), PDO::PARAM_STR);
    }

    /**
     * Устанавливает атрибуты для питомцев со статусом Find
     * @param $attributes
     */
    protected function setStatusAttributes($attributes)
    {
        if (isset($attributes['age'])) {
            $min = (float)$attributes['age']['min'];
            $max = (float)$attributes['age']['max'];
            $this->owner->age = new CDbExpression("'[$min, $max]'::numrange");
        }
    }
}