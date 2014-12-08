<?php

/**
 * Class CatLostBehavior
 *
 * Конечный класс с реализацией методов формирования всех параметров для результатирубщего запроса
 */
final class CatLostBehavior extends BasePetBehavior implements PetFinderInterface
{
    use TCat, TLost;

    /**
     * Поиск питомцев по геологическому местоположению
     * @param $data
     *
     * @return array|null
     */
    public function findPetByLocation($data)
    {
        $this->initCriteria();
        $this->setSearchConditions($data);
        $this->createSqlFromCriteria();
        $this->setParams($data);

        return $this->executeQuery();
    }

    /**
     * Поиск питомца по идентификатору
     * @param $id
     *
     * @return array|null
     */
    public function findPetById($id)
    {
        $this->initCriteria();
        $this->criteria->addCondition('t.id = :id');
        $this->createSqlFromCriteria();
        $this->command->bindParam('id', $id, PDO::PARAM_INT);

        return $this->executeQuery();
    }

    /**
     * Формирование атрибутов для данного типа питомца с данным статусом
     * @param $attributes
     */
    public function setPetAttributes($attributes)
    {
        $this->setBaseAttributes($attributes);
    }

    /**
     * Устранение подводного камня с foreign keys при использования наследования в Postgres
     * см. Документацию Postgres п. 5.8.1
     * Была создана отдельная общая таблица от которой все child таблицы получали уникальный идентификатор.
     * В общей таблице созданы внешние ключи для связывающей таблицы пород, что гарантировало целостность данных.
     * @return bool
     */
    public function beforeSave()
    {
        $commonPet = new Pet;
        $commonPet->type = Pet::INT_TYPE_CAT;
        $commonPet->status = Pet::INT_STATUS_LOST;
        $commonPet->{$this->breedRelation} = $this->breeds;
        if ($commonPet->saveWithRelated($this->breedRelation)) {
            $this->owner->id = $commonPet->getPrimaryKey();
            return true;
        }
        return false;
    }

    /**
     * Получение всех колонок для данного типа питомца с данным статусом
     * @return string
     */
    protected function getColumns()
    {
        $select = $this->getBaseColumns();
        $select .= self::STRING_COMMA;
        $select .= $this->getStatusColumns();
        return $select;
    }

    /**
     * Получение всех реляционных связей для данного типа питомца с данным статусом
     * @return string
     */
    protected function getJoins()
    {
        $joins = $this->getBaseJoins();
        $joins .= $this->getPetJoins();
        return $joins;
    }

    /**
     * Формирование условий выборки для данного типа питомца с данным статусом
     * @param $data
     */
    protected function setSearchConditions($data)
    {
        $this->setBaseConditions($data);
        $this->setStatusConditions($data);
    }

    /**
     * Формирование параметров для данного типа питомца с данным статусом
     * @param $data
     */
    protected function setParams($data)
    {
        $this->setBaseParams($data);
        $this->setStatusParams($data);
    }

    /**
     * Формирование начальных установок критерии для данного типа питомца с данным статусом
     */
    private function initCriteria()
    {
        $this->criteria->select = $this->getColumns();
        $this->criteria->join = $this->getJoins();
        $this->criteria->group = 't.id';
    }
}