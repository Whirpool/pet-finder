<?php

/**
 * This is the model class for table "data_dog_find".
 *
 * The followings are the available columns in table 'data_dog_find':
 * @property string $sigma
 * @property string $sex
 * @property string $special
 * @property string $advanced
 * @property string $date
 * @property string $date_create
 * @property string $date_update
 * @property integer $status
 * @property integer $author
 * @property string $location
 * @property integer $eyes
 * @property string $colors
 * @property integer $fur
 * @property integer $type_color
 * @property integer $figured
 * @property string $age
 * @property integer $id
 *
 * The followings are the available model relations:
 * @property Pet $pet
 * @property LPetFur $typeFur
 * @property LPetEyes $typeEyes
 * @property LDogTypeFigured $typeFigured
 * @property LDogTypeColor $typeColor
 */
class DogFind extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'data_dog_find';
    }

    public function behaviors()
    {
        return array(
            'petFinder'=>array(
                'class'=>'application.components.PetBehaviors.DogFindBehavior',
            ),

        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sex, date, author, location, eyes, colors, fur, type_color, figured, age', 'required'),
            array('status, author, eyes, fur, type_color, figured, id', 'numerical', 'integerOnly'=>true),
            array('colors', 'length', 'max'=>32),
            array('sigma, special, advanced', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('sigma, sex, special, advanced, date, date_create, date_update, status, author, location, eyes, colors, fur, type_color, figured, age, id', 'safe', 'on'=>'search'),
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
            'pet' => array(self::BELONGS_TO, 'Pet', 'id'),
            'typeFur' => array(self::BELONGS_TO, 'LPetFur', 'fur'),
            'typeEyes' => array(self::BELONGS_TO, 'LPetEyes', 'eyes'),
            'typeFigured' => array(self::BELONGS_TO, 'LDogTypeFigured', 'figured'),
            'typeColor' => array(self::BELONGS_TO, 'LDogTypeColor', 'type_color'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'sigma' => 'Sigma',
            'sex' => 'Sex',
            'special' => 'Special',
            'advanced' => 'Advanced',
            'date' => 'Date',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'status' => 'Status',
            'author' => 'Author',
            'location' => 'location',
            'eyes' => 'Eyes',
            'colors' => 'Colors',
            'fur' => 'Fur',
            'type_color' => 'Type Color',
            'figured' => 'Figured',
            'age' => 'Age',
            'id' => 'ID',
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

        $criteria=new CDbCriteria;

        $criteria->compare('sigma',$this->sigma,true);
        $criteria->compare('sex',$this->sex,true);
        $criteria->compare('special',$this->special,true);
        $criteria->compare('advanced',$this->advanced,true);
        $criteria->compare('date',$this->date,true);
        $criteria->compare('date_create',$this->date_create,true);
        $criteria->compare('date_update',$this->date_update,true);
        $criteria->compare('status',$this->status);
        $criteria->compare('author',$this->author);
        $criteria->compare('location',$this->location,true);
        $criteria->compare('eyes',$this->eyes);
        $criteria->compare('colors',$this->colors,true);
        $criteria->compare('fur',$this->fur);
        $criteria->compare('type_color',$this->type_color);
        $criteria->compare('figured',$this->figured);
        $criteria->compare('age',$this->age,true);
        $criteria->compare('id',$this->id);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return DogFind the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}