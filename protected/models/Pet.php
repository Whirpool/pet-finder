<?php

/**
 * This is the model class for table "data_pet".
 *
 * The followings are the available columns in table 'data_pet':
 *
 * @property integer     $type
 * @property integer     $status
 * @property integer     $id
 *
 * The followings are the available model relations:
 * @property LPetType    $pet0
 * @property LPetStatus  $status0
 * @property CatFind     $catFind
 * @property CatLost     $catLost
 * @property LCatBreed[] $catBreeds
 * @property LDogBreed[] $dogBreeds
 * @property DogLost     $dogLost
 * @property DogFind     $dogFind
 */
class Pet extends CActiveRecord
{
    const INT_STATUS_FIND = 1;
    const INT_STATUS_LOST = 2;

    const STR_STATUS_FIND = 'find';
    const STR_STATUS_LOST = 'lost';

    const INT_TYPE_CAT = 1;
    const INT_TYPE_DOG = 2;

    const STR_TYPE_CAT = 'cat';
    const STR_TYPE_DOG = 'dog';

    const MODEL_CAT = 'Cat';
    const MODEL_DOG = 'Dog';
    const MODEL_LOST = 'Lost';
    const MODEL_FIND = 'Find';

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'data_pet';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('type, status', 'required'),
            array('type, status', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('type, status, id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'pet0' => array(self::BELONGS_TO, 'LPetType', 'type'),
            'status0' => array(self::BELONGS_TO, 'LPetStatus', 'status'),
            'catBreeds' => array(self::MANY_MANY, 'LCatBreed', 'map_cat_breed(id_cat, id_breed)'),
            'dogBreeds' => array(self::MANY_MANY, 'LDogBreed', 'map_dog_breed(id_dog, id_breed)'),
            'catFind' => array(self::HAS_ONE, 'CatFind', 'id'),
            'catLost' => array(self::HAS_ONE, 'CatLost', 'id'),
            'dogLost' => array(self::HAS_ONE, 'DogLost', 'id'),
            'dogFind' => array(self::HAS_ONE, 'DogFind', 'id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'type' => 'type',
            'status' => 'Status',
            'id' => 'ID',
        );
    }

    public function behaviors()
    {
        return array(
            'withRelated' => array(
                'class' => 'application.components.ESaveRelatedBehavior',
            ),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('type', $this->pet);
        $criteria->compare('status', $this->status);
        $criteria->compare('id', $this->id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className active record class name.
     *
     * @return Pet the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getLookup()
    {
        $result = [];
        $result['pet']['type'] = Yii::app()->db->createCommand()
            ->select('id, value')
            ->from('lookup_pet_type')
            ->queryAll();
        $result['pet']['status'] = Yii::app()->db->createCommand()
            ->select('id, value')
            ->from('lookup_pet_status')
            ->queryAll();
        $result['pet']['eyes'] = Yii::app()->db->createCommand()
            ->select('id, value')
            ->from('lookup_pet_eyes')
            ->queryAll();
        $result['pet']['fur'] = Yii::app()->db->createCommand()
            ->select('id, value')
            ->from('lookup_pet_fur')
            ->queryAll();
        $result['cat']['color'] = Yii::app()->db->createCommand()
            ->select('id, value')
            ->from('lookup_cat_type_color')
            ->queryAll();
        $result['cat']['breed'] = Yii::app()->db->createCommand()
            ->select('id, value')
            ->from('lookup_cat_breed')
            ->queryAll();
        $result['dog']['breed'] = Yii::app()->db->createCommand()
            ->select('id, value')
            ->from('lookup_dog_breed')
            ->queryAll();
        $result['dog']['figured'] = Yii::app()->db->createCommand()
            ->select('id, value')
            ->from('lookup_dog_type_figured')
            ->queryAll();
        $result['dog']['color'] = Yii::app()->db->createCommand()
            ->select('id, value')
            ->from('lookup_dog_type_color')
            ->queryAll();

        return $result;

    }

    public function create($pet, $status)
    {
        if (isset($pet) && isset($status)) {
            $model = '';
            switch ($pet) {
                case(self::INT_TYPE_CAT):
                    $model .= self::MODEL_CAT;
                    break;
                case(self::INT_TYPE_DOG):
                    $model .= self::MODEL_DOG;
                    break;
                default:
                    throw new CException('Ошибка инициализации типа питомца');
                    break;
            }

            switch ($status) {
                case(self::INT_STATUS_FIND):
                    $model .= self::MODEL_FIND;
                    break;
                case(self::INT_STATUS_LOST):
                    $model .= self::MODEL_LOST;
                    break;
                default:
                    throw new CException('Ошибка инициализации статуса питомца');
                    break;
            }
        } else {
            throw new CException('Поля \'Тип питомца\' и \'Статус питомца\' обязательны для заполнения');
        }

        return new $model;
    }

    private function convertInitParams($init)
    {
        $type = strtolower(preg_replace('/\s+/', '', $init['pet']));
        $status = strtolower(preg_replace('/\s+/', '', $init['status']));

        if ($type === self::STR_TYPE_CAT) {
            $init['pet'] = self::INT_TYPE_CAT;
        } elseif ($type === self::STR_TYPE_DOG) {
            $init['pet'] = self::INT_TYPE_DOG;
        }
        if ($status === self::STR_STATUS_FIND) {
            $init['status'] = self::INT_STATUS_FIND;
        } elseif ($status === self::STR_STATUS_LOST) {
            $init['status'] = self::INT_STATUS_LOST;
        }
        return $init;
    }
}