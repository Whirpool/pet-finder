<?php

/**
 * Class FindTrait
 * Установка параметров свойственных питомцам со статусом Find
 * @property $criteria CDbCriteria
 * @property $command CDbCommand
 * @property $owner CActiveRecord
 */
trait FindTrait
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

        if (isset($data['breeds']) && !empty($data['breeds'])) {
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
     * @param $params
     */
    protected function setStatusParams($params)
    {
        $dateStart = DateTime::createFromFormat('d-m-Y', $params['date']);
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
            $min = (float)$attributes['ageMin'];
            $max = (float)$attributes['ageMax'];
            $this->owner->age = new CDbExpression("'[$min, $max]'::numrange");
        }
    }
}