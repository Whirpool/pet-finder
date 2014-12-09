<?php

/**
 * Class CatFindBehavior
 *
 * Конечный класс с реализацией методов формирования всех параметров для результатирубщего запроса
 */
final class CatFindBehavior extends AbstractBasePetBehavior implements SearchByLocationInterface
{
    use CatTrait, FindTrait;

    public function findPetByLocation($data)
    {
        $this->initCriteria();
        $this->setSearchConditions($data);
        $this->createSqlFromCriteria();
        $this->setParams($data);

        return $this->executeQuery();
    }

    public function findPetById($id)
    {
        $this->initCriteria();
        $this->criteria->addCondition('t.id = :id');
        $this->createSqlFromCriteria();
        $this->command->bindParam('id', $id, PDO::PARAM_INT);

        return $this->executeQuery();
    }

    public function isZoomValid($radius)
    {
        return (int)$radius < SearchByLocationInterface::VALID_RADIUS;
    }

    public function setPetAttributes($data)
    {
        $this->setBaseAttributes($data);
        $this->setStatusAttributes($data);
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
        $commonPet->status = Pet::INT_STATUS_FIND;
        $commonPet->{$this->breedRelation} = $this->breeds;
        if ($commonPet->saveWithRelated($this->breedRelation)) {
            $this->owner->id = $commonPet->getPrimaryKey();
            return true;
        }
        return false;
    }

    protected function getColumns()
    {
        $select = $this->getBaseColumns();
        $select .= self::STRING_COMMA;
        $select .= $this->getStatusColumns();
        $select .= self::STRING_COMMA;
        $select .= $this->getLocationColumns();
        return $select;
    }

    protected function getJoins()
    {
        $joins = $this->getBaseJoins();
        $joins .= $this->getPetJoins();
        return $joins;
    }

    protected function setSearchConditions($data)
    {
        $this->setBaseConditions($data);
        $this->setStatusConditions($data);
        $this->setLocationConditions($data);
    }

    protected function setParams($params)
    {
        $this->setBaseParams($params);
        $this->setStatusParams($params);
        $this->setLocationParams($params);
    }

    /**
     * Формирование начальных установок критерии.
     */
    private function initCriteria()
    {
        $this->criteria->select = $this->getColumns();
        $this->criteria->join = $this->getJoins();
        $this->criteria->group = 't.id';
    }

    public function getLocationColumns()
    {
        $select = 'ST_X(CAST(location as geometry)) as lat, ST_Y(CAST(location as geometry)) as lng';
        return $select;
    }

    public function setLocationConditions($data)
    {
        $lat = (float)$data['location']['lat'];
        $lng = (float)$data['location']['lng'];
        $this->criteria->addCondition("ST_DWithin(location, ST_GeomFromText('POINT({$lat} {$lng})',4326), :radius)");
    }

    public function setLocationParams($params)
    {
        $this->command->bindParam(':radius', $params['location']['radius'], PDO::PARAM_INT);
    }
}