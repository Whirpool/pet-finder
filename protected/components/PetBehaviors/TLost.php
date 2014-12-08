<?php

/**
 * Class TLost
 * Установка параметров свойственных питомцам со статусом Lost
 * @property $criteria CDbCriteria
 * @property $command CDbCommand
 */
trait TLost
{
    /**
     * Устанавливает условия выборки для питомцев со статусом Lost
     * @param $data
     */
    protected function setStatusConditions($data)
    {
        $min = (float)$data['age']['min'];
        $max = (float)$data['age']['max'];
        $this->criteria->addCondition('date BETWEEN :dateEnd AND :dateStart');
        $this->criteria->addCondition("age <@ '[$min, $max]'::numrange");
    }

    /**
     * Возвращает колонки для питомцев со статусом Lost
     * @return string
     */
    protected function getStatusColumns()
    {
        $select = 't.name, t.age';
        return $select;
    }

    /**
     * Устанавливает параметры для питомцев со статусом Lost
     * @param $data
     */
    protected function setStatusParams($data)
    {
        $dateStart = DateTime::createFromFormat('d-m-Y', $data['date']);
        $dateEnd = DateTime::createFromFormat('d-m-Y', $data['date']);
        $dateEnd->sub(new DateInterval('P1Y'));
        $this->command->bindParam(':dateEnd', $dateEnd->format('Y-m-d'), PDO::PARAM_STR);
        $this->command->bindParam(':dateStart', $dateStart->format('Y-m-d'), PDO::PARAM_STR);
    }
}
