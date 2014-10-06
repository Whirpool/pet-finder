<?php

/**
 * This is the model class for table "{{pet_finder}}".
 *
 * The followings are the available columns in table '{{pet_finder}}':
 *
 * @property integer $id
 * @property integer $pet_id
 * @property string  $breed
 * @property integer $age_id
 * @property string  $name
 * @property integer $sex
 * @property string  $special
 * @property string  $advanced
 * @property integer $date
 * @property integer $date_create
 * @property integer $date_update
 * @property integer $search_type
 * @property integer $search_status
 * @property integer $author_id
 * @property double  $lng
 * @property double  $lat
 * @property point   $coords
 */
class PetFinder extends RActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{pet_finder}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'pet_id, breed, age_id, sex, date, date_create, date_update, search_type, author_id, lng, lat',
                'required',
                'message' => 'Заполните поле {attribute}'
            ),
            array(
                'pet_id, age_id, sex, date, date_create, date_update, search_type, search_status, author_id',
                'numerical',
                'integerOnly' => true
            ),
            array('lng, lat', 'numerical'),
            array(
                'breed, name',
                'length',
                'min' => 3,
                'max' => 30,
                'message' => 'Длинна поля {attribute} от 3 до 30 символов'
            ),
            array(
                'special, advanced',
                'length',
                'max' => 255,
                'message' => 'Длинна поля {attribute} не более 255 символов'
            ),
            array('sex', 'in', 'range' => array(1, 2)),
            array(
                'breed, name',
                'match',
                'pattern' => '/^[A-zА-я\s]+$/u',
                'message' => "В поле {attribute} используйте только буквы."
            ),
            array('special, advanced', 'safe'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'author_id', 'alias' => 'u1'),
            'images' => array(self::HAS_MANY, 'PfImages', 'post_id'),
            'comments' => array(self::HAS_MANY, 'PfComment', 'post_id', 'order' => 'c.time_create', 'alias' => 'c'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'pet_id' => 'Питомец',
            'breed' => 'Порода',
            'age_id' => 'Возраст',
            'name' => 'Кличка',
            'sex' => 'Пол',
            'special' => 'Особые приметы',
            'advanced' => 'Дополнительно',
            'date' => 'Дата',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата редактирования',
            'search_type' => 'Тип поиска',
            'search_status' => 'Статус поиска',
            'author_id' => 'Автор',
            'lng' => 'Lng',
            'lat' => 'Lat',
            'src' => 'Фото'
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className active record class name.
     *
     * @return PetFinder the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Установка даты и автора перед валидацией
     *
     * @return bool
     */
    protected function beforeValidate()
    {
        $this->date_create = $this->date_update = time();
        $this->author_id = Yii::app()->user->id;

        return true;
    }


    protected function afterSave()
    {
        parent::afterSave();
        $this->addImage();
    }


    /**
     * @param $data
     * Устанавливает сравнения для поиска.
     * В зависимости от типа поиска устанавливается диапазон времени год вперед или назад от текущей даты.
     * Для криерия поиска по координатам используются внутренние функции MySQL.
     * Проверяется наличие записей внутри бокса из границ карты
     *
     * @return array
     */
    public function searchPets($data)
    {
        $dateStart = DateTime::createFromFormat('d-m-Y', $data['date']);
        $dateEnd = DateTime::createFromFormat('d-m-Y', $data['date']);

        $criteria = new CDbCriteria();
        $criteria->with = [
            'user',
            'images',
        ];
        $criteria->alias = 't';
        $criteria->select
            = 't.id, t.pet_id, t.breed, t.age_id, t.name, t.sex, t.special, t.advanced, t.date, t.date_create, t.date_update, t.author_id, t.lng, t.lat';
        $coordsExpression = new CDbExpression('within(coords, envelope(linestring(POINT(:upLeftX, :upLeftY), POINT(:downRightX, :downRightY))))');
        $criteria->addCondition($coordsExpression);
        $criteria->compare('search_type', $data['search_type']);
        if (isset($data['pet_id'])) {
            $criteria->compare('pet_id', $data['pet_id']);
        }
        if (isset($data['age_id'])) {
            $criteria->compare('age_id', $data['age_id']);
        }
        if (isset($data['sex'])) {
            $criteria->compare('sex', $data['sex']);
        }
        if (isset($data['breed'])) {
            $criteria->addSearchCondition('breed', $data['breed']);
        }
        if ($data['search_type'] == 1) {
            $dateEnd->sub(new DateInterval('P1Y'));
            $criteria->addBetweenCondition('date', $dateEnd->format('U'), $dateStart->format('U'));
        } else {
            $dateEnd->add(new DateInterval('P1Y'));
            $criteria->addBetweenCondition('date', $dateStart->format('U'), $dateEnd->format('U'));
        }

        $criteria->params[':upLeftX'] = $data['location']['upLeft']['lat'];
        $criteria->params[':upLeftY'] = $data['location']['upLeft']['lng'];
        $criteria->params[':downRightX'] = $data['location']['downRight']['lat'];
        $criteria->params[':downRightY'] = $data['location']['downRight']['lng'];
        $query = $this->findAll($criteria);

        if (empty($query)) {
            return null;
        }
        $result = $this->convertModelToArray($query);

        return $result;
    }

    /**
     * Поиск по первичному ключу
     * результат со всеми связями конвертируется в массив
     *
     * @param $id
     *
     * @return array|null
     */
    public function searchPet($id)
    {
        $criteria = new CDbCriteria();
        $criteria->with = ['images', 'user', 'comments'];
        $criteria->alias = 't';
        $criteria->select = 't.id, t.pet_id, t.breed, t.age_id, t.name, t.sex, t.special, t.advanced, t.date, t.date_create, t.date_update, t.author_id, t.lng, t.lat';
        $query = $this->findByPk($id, $criteria);
        if (empty($query)) {
            return null;
        }
        $result = $this->convertModelToArray($query);
        return $result;
    }

    /**
     * Перемещает файл из временной папки
     * и записывает данные об изображении в бд
     */
    private function addImage()
    {
        $pathOriginal = Yii::app()->params['images']['path']['size']['original']['absolute'] . $this->id . DIRECTORY_SEPARATOR;
        $pathOrigRel  = Yii::app()->params['images']['path']['size']['original']['relative'] . $this->id . DIRECTORY_SEPARATOR;
        $pathSmall    = Yii::app()->params['images']['path']['size']['small']['absolute'] . $this->id . DIRECTORY_SEPARATOR;
        $pathSmallRel = Yii::app()->params['images']['path']['size']['small']['relative'] . $this->id . DIRECTORY_SEPARATOR;

        if (Yii::app()->user->hasState('image')) {
            $imageState = Yii::app()->user->getState('image');
            foreach ($imageState as $image) {
                if (is_file(Yii::app()->params['images']['path']['tmp'] . $image['nameOriginal'])
                    && is_file(Yii::app()->params['images']['path']['tmp'] . $image['nameSmall'])
                ) {
                    $imgPost = new PfImages;
                    $imgPost->createImageFolder($pathOriginal);
                    $imgPost->createImageFolder($pathSmall);
                    if (rename(Yii::app()->params['images']['path']['tmp'] . $image['nameOriginal'], $pathOriginal . $image['nameOriginal'])
                        && rename(Yii::app()->params['images']['path']['tmp'] . $image['nameSmall'], $pathSmall . $image['nameSmall'])
                    ) {
                        chmod($pathOriginal . $image['nameOriginal'], 0755);
                        chmod($pathSmall . $image['nameSmall'], 0755);
                        $imgPost->size = $image['size'];
                        $imgPost->mime = $image['mime'];
                        $imgPost->source_original = $pathOrigRel . $image['nameOriginal'];
                        $imgPost->source_small = $pathSmallRel . $image['nameSmall'];
                        $imgPost->name_original = $image['nameOriginal'];
                        $imgPost->name_small = $image['nameSmall'];
                        $imgPost->post_id = $this->id;
                        $imgPost->save();
                        Yii::app()->user->setState('image', null);
                    }
                }
            }
        }
    }

    public function setUnixDate()
    {
        $date = DateTime::createFromFormat('d-m-Y', $this->date);
        $this->date = (int)$date->format('U');
    }

    public function setPoint()
    {
        $this->coords = new CDbExpression('POINT(:lat, :lng)', array(':lat' => $this->lat, ':lng' => $this->lng));
    }

    public function isZoomValid($location)
    {
        $distanceLng = $location['downRight']['lng'] - $location['upLeft']['lng'];
        $distanceLat = $location['downRight']['lat'] - $location['upLeft']['lat'];
        $validLng = ($distanceLng < Yii::app()->params['zoom']['max']['lng'] && $distanceLng > 0);
        $validLat = ($distanceLat < Yii::app()->params['zoom']['max']['lat'] && $distanceLat > 0);
        return ($validLng && $validLat);
    }
}
