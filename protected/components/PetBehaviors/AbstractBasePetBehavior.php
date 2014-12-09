<?php

/**
 * Class AbstractBasePetBehavior
 * Класс устанавливающий базовые параметры: выбор колонок, реляционных связей, условий и т.д.
 * Конкретные параметры в зависимости от типа и статуса питомца реализуются в трейтах.
 * Класс наследник реализует методы по сбору всех параметров (колонки, реляционные связи, условия и т.д.)
 * и фомирует один едиый запрос.
 *
 * @property CDbCriteria $criteria Формирует запрос для дальнейшей передачи в объект CDbCommand.
 * @property CDbCommand $command Выполняет запрос сформированный критерией.
 *
 * @const string STRING_COMMA Разделитель между результатами функций возвращающих колонки для выборки.
 */
abstract class AbstractBasePetBehavior extends CActiveRecordBehavior
{
    protected $criteria;

    protected $command;

    const STRING_COMMA = ', ';

    /**
     * Формирование всех колонок для конкретного типа питомца с установленным статусом поиска
     * @return string
     */
    abstract protected function getColumns();

    /**
     * Формирование всех реляционных связей для конкретного типа питомца с установленным статусом поиска
     * @return string
     */
    abstract protected function getJoins();

    /**
     * Формирование условий выборки для конкретного типа питомца с установленным статусом поиска
     * @param $data
     */
    abstract protected function setSearchConditions($data);

    /**
     * Формирование параметры запроса для конкретного типа питомца с установленным статусом поиска
     * @param $params
     */
    abstract protected function setParams($params);

    /**
     * Формирование атрибутов для конкретного типа питомца с установленным статусом поиска
     * @param $attributes
     */
    abstract public function setPetAttributes($attributes);

    /**
     * Поиск питомца по идентификатору
     * @param $id
     *
     * @return array|null
     */
    abstract public function findPetById($id);

    public function __construct()
    {
        $this->criteria = new CDbCriteria;
        $this->command = Yii::app()->db->createCommand();
    }

    public function beforeValidate()
    {
        $this->owner->author = 1;
        return true;
    }

    public function afterSave()
    {
        $this->addImage();
        return true;
    }

    /**
     * Конвертирование массива в фомат Postgres
     *
     * @param array  $set Исходный массив.
     * @param string $declaration Флаг определения фомата выходного массива:
     * short - {value1, value2} full - ARRAY[value1, value2]::type
     * @param string $type тип выходного массива (только для фомата full)
     *
     * @return string
     */
    protected function toPgArray($set, $declaration = 'short', $type = 'varchar')
    {
        settype($set, 'array');
        $result = array();
        foreach ($set as $t) {
            if (is_array($t)) {
                $result[] = $this->toPgArray($t);
            } else {
                $t = str_replace('"', '\\"', $t);
                if (!is_numeric($t)) {
                    $t = "'" . $t . "'";
                }
                $result[] = $t;
            }
        }
        if ($declaration === 'full') {
            return 'ARRAY[' . implode(',', $result) . ']::' . $type . '[]';
        }
        return '{' . implode(',', $result) . '}';
    }


    /**
     * Перемещает файл(ы) из временной папки в постоянное хранилище
     * и записывает данные об изображен(ии/ях) из сессии в бд
     */
    private function addImage()
    {
        $pathOriginal = Yii::app()->params['images']['original']['absolute'] . $this->owner->id . DIRECTORY_SEPARATOR;
        $pathOrigRel  = Yii::app()->params['images']['original']['relative'] . $this->owner->id . DIRECTORY_SEPARATOR;
        $pathSmall    = Yii::app()->params['images']['small']['absolute'] . $this->owner->id . DIRECTORY_SEPARATOR;
        $pathSmallRel = Yii::app()->params['images']['small']['relative'] . $this->owner->id . DIRECTORY_SEPARATOR;

        if (Yii::app()->user->hasState('image')) {
            $imageState = Yii::app()->user->getState('image');
            foreach ($imageState as $image) {
                if (is_file(Yii::app()->params['images']['tmp'] . $image['nameOriginal'])
                    && is_file(Yii::app()->params['images']['tmp'] . $image['nameSmall'])
                ) {
                    $imgPost = new Images;
                    $imgPost->createImageFolder($pathOriginal);
                    $imgPost->createImageFolder($pathSmall);
                    if (rename(Yii::app()->params['images']['tmp'] . $image['nameOriginal'], $pathOriginal . $image['nameOriginal'])
                        && rename(Yii::app()->params['images']['tmp'] . $image['nameSmall'], $pathSmall . $image['nameSmall'])
                    ) {
                        chmod($pathOriginal . $image['nameOriginal'], 0755);
                        chmod($pathSmall . $image['nameSmall'], 0755);
                        $imgPost->mime = $image['mime'];
                        $imgPost->source_original = $pathOrigRel . $image['nameOriginal'];
                        $imgPost->source_small = $pathSmallRel . $image['nameSmall'];
                        $imgPost->name_original = $image['nameOriginal'];
                        $imgPost->name_small = $image['nameSmall'];
                        $imgPost->pet_id = $this->owner->id;
                        $imgPost->save();
                    }
                }
            }
            Yii::app()->user->setState('image', null);
        }
    }


    /**
     * Получает sql из сформированной критерии и помещает его в подзапрос
     * для получения из сервера данных в формате json
     */
    protected function createSqlFromCriteria()
    {
        $sql = $this->owner->getCommandBuilder()
            ->createFindCommand($this->owner->getTableSchema(), $this->criteria)
            ->getText();
        $this->command->select('array_to_json(array_agg(row_to_json(x)))')->from('(' . $sql . ') as x');
    }

    /**
     * Выполнение запроса
     * @return array|null
     */
    protected function executeQuery()
    {
        $result = $this->command->queryAll(false);

        if (empty($result)) {
            return null;
        }

        return $result[0][0];
    }

    /**
     * Устанавливает общие для всех типов питомцев с различными статусами атрибуты
     * @param $data
     */
    protected function setBaseAttributes($data)
    {
        $this->owner->attributes = $data;
        //color
        $this->owner->colors = '{color1, color2}';
//        if(isset($data['colors'])) {
//            $this->owner->colors = $this->toPgArray($data['colors']);
//        }
        if (isset($data['location'])) {
            $this->owner->location = new CDbExpression("ST_SetSRID(ST_MakePoint(:lat, :lng), 4326)",
                [':lat' => $data['location']['lat'], ':lng' => $data['location']['lng']]);
        }
        if (isset ($data['sigma'])) {
            $this->owner->sigma = new CDbExpression("to_tsvector('english', ':sigma')", ['sigma' => $data['sigma']]);
        }
    }

    /**
     * Возвращает общие для всех типов питомцев с различными статусами колонки
     * @return string
     */
    protected function getBaseColumns()
    {
        return 't.id, t.sigma, t.sex, t.special, t.advanced, t.date, t.date_create, t.date_update, t.author, '
        . ' min(fur.value) as fur, min(type_color.value) as type_color, min(eyes.value) as eyes,  '
        . ' array_agg(breed.value) as breeds, pf_get_images(t.id) as images';
    }

    /**
     * Возвращает общие для всех типов питомцев с различными статусами реляционные связи
     * @return string
     */
    protected function getBaseJoins()
    {
        return 'join lookup_pet_fur fur on (t.fur = fur.id) join lookup_pet_eyes eyes on (t.eyes = eyes.id) ';
    }

    /**
     * Устанавливает общие для всех типов питомцев с различными статусами условия выборки
     * @param $data
     */
    protected function setBaseConditions($data)
    {
        $this->criteria->addCondition('sex = :sex');

        if (isset($data['eyes'])) {
            $this->criteria->addCondition('eyes = :eyes');
        }
        if (isset($data['fur'])) {
            $this->criteria->addCondition('fur = :fur');
        }
    }

    /**
     * Устанавливает общие для всех типов питомцев с различными статусами параметры
     * @param $params
     */
    protected function setBaseParams($params)
    {
        $this->command->bindParam(':sex', $params['sex'], PDO::PARAM_STR);
        if (isset($params['eyes'])) {
            $this->command->bindParam(':eyes', $params['eyes'], PDO::PARAM_INT);
        }
        if (isset($params['fur'])) {
            $this->command->bindParam(':fur', $params['fur'], PDO::PARAM_INT);
        }
    }
}