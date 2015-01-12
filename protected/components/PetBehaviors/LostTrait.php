<?php

/**
 * Class LostTrait
 * Установка параметров свойственных питомцам со статусом Lost
 * @property $criteria CDbCriteria
 * @property $command CDbCommand
 */
trait LostTrait
{
    /**
     * Устанавливает условия выборки для питомцев со статусом Lost
     * @param $data
     */
    protected function setStatusConditions($data)
    {
        $min = (float)$data['ageMin'];
        $max = (float)$data['ageMax'];
        $this->criteria->addCondition('date BETWEEN :dateEnd AND :dateStart');
        $this->criteria->addCondition("age <@ '[$min, $max]'::numrange");

        if (isset($data['breeds']) && !empty($data['breeds'])) {
            $pgArray = $this->toPgArray($data['breeds'], 'full');
            $this->command->where($pgArray . ' && x.breeds');
        }
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
     * @param $params
     */
    protected function setStatusParams($params)
    {
        $dateStart = DateTime::createFromFormat('d-m-Y', $params['date']);
        $dateEnd = DateTime::createFromFormat('d-m-Y', $params['date']);
        $dateEnd->sub(new DateInterval('P1Y'));
        $this->command->bindParam(':dateEnd', $dateEnd->format('Y-m-d'), PDO::PARAM_STR);
        $this->command->bindParam(':dateStart', $dateStart->format('Y-m-d'), PDO::PARAM_STR);
    }
}
