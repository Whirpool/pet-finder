<?php

/**
 * Class BasePetBehavior
 * Класс устанавливающий базовые параметры: выбор колонок, реляционных связей и условий.
 * Конкретные параметры в зависимости от типа и статуса питомца реализуются в трейтах.
 * Класс наследник реализует методы по сбору всех параметров (колонки, реляционные связи и условия)
 * и фомирует один едиый запрос.
 *
 * @property array $breeds Содержит породы питомцев. Необходим для реализации реляционной связи MANY_TO_MANY.
 * @property CDbCriteria $criteria Формирует запрос для дальнейшей передачи в объект CDbCommand.
 * @property CDbCommand $command Выполняет запрос сформированный критерией.
 *
 * @const string STRING_COMMA Разделитель между результатами функций возвращающих колонки для выборки.
 * @const integer VALID_RADIUS Максимально допустимый радиус поиска в метрах.
 */
abstract class BasePetBehavior extends CActiveRecordBehavior
{
    protected $breeds;

    protected $criteria;

    protected $command;

    const STRING_COMMA = ', ';

    const VALID_RADIUS = 3000;


    abstract protected function getColumns();

    abstract protected function getJoins();

    abstract protected function setSearchConditions($data);

    abstract protected function setParams($data);

    abstract public function setPetAttributes($attributes);


    public function __construct()
    {
        $this->criteria = new CDbCriteria;
        $this->command = Yii::app()->db->createCommand();
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
     * Устанавливает породы питомца
     * @var string $breedModel Зависит от типа питомца. Инициализируется в трейте TCat/TDog
     * @param array $breeds
     *
     * @throws CException
     */
    public function setBreeds(array $breeds)
    {
        $breedArray = [];
        foreach ($breeds as $breed) {
            $b = new $this->breedModel;
            $b->value = $breed;
            if ($b->validate()) {
                $breedArray[] = $b->value;
            } else {
                throw new CException('Неверный формат породы питомца. Выберите породу из списка.');
            }
        }
        $this->breeds = $breedArray;
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
     * Валидация радиуса поиска вводимого пользователем.
     * @param $radius
     *
     * @return bool
     */
    public function isZoomValid($radius)
    {
        return (int)$radius < self::VALID_RADIUS;
    }

    /**
     * Перемещает файл(ы) из временной папки в постоянное хранилище
     * и записывает данные об изображен(ии/ях) из сессии в бд
     */
    private function addImage()
    {
        $pathOriginal = Yii::app()->params['images']['original']['absolute'] . $this->owner->id . DIRECTORY_SEPARATOR;
        $pathOrigRel = Yii::app()->params['images']['original']['relative'] . $this->owner->id . DIRECTORY_SEPARATOR;
        $pathSmall = Yii::app()->params['images']['small']['absolute'] . $this->owner->id . DIRECTORY_SEPARATOR;
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
     * Возвращает общие для всех типов питомцев с различными статусами колонки
     * @return string
     */
    protected function getBaseColumns()
    {
        return 't.id, t.sigma, t.sex, t.special, t.advanced, t.date, t.date_create, t.date_update, t.author, '
        . ' min(fur.value) as fur, min(type_color.value) as type_color, min(eyes.value) as eyes, array_agg(breed.value) as breeds, '
        . ' ST_X(CAST(location as geometry)) as lat, ST_Y(CAST(location as geometry)) as lng, pf_get_images(t.id) as images';
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
        $lat = (float)$data['location']['lat'];
        $lng = (float)$data['location']['lng'];
        $this->criteria->addCondition("ST_DWithin(location, ST_GeomFromText('POINT({$lat} {$lng})',4326), :radius)");

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
     * @param $data
     */
    protected function setBaseParams($data)
    {
        $this->command->bindParam(':radius', $data['location']['radius'], PDO::PARAM_INT);
        $this->command->bindParam(':sex', $data['sex'], PDO::PARAM_STR);
        if (isset($data['eyes'])) {
            $this->command->bindParam(':eyes', $data['eyes'], PDO::PARAM_INT);
        }
        if (isset($data['fur'])) {
            $this->command->bindParam(':fur', $data['fur'], PDO::PARAM_INT);
        }
    }
}