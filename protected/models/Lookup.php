<?php

/**
 * This is the model class for table "{{lookup}}".
 *
 * The followings are the available columns in table '{{lookup}}':
 *
 * @property integer $id
 * @property string  $type
 * @property integer $type_id
 * @property string  $name
 */
class Lookup extends CActiveRecord
{
    const TYPE_PET = 'pet';
    const TYPE_AGE = 'age';
    const TYPE_SEX = 'sex';

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{lookup}}';
    }

    /**
     * @return string class name
     */
    public function getClassName()
    {
        return lcfirst(__CLASS__);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('type, type_id, name', 'required'),
            array('type_id', 'numerical', 'integerOnly' => true),
            array('type, name', 'length', 'max' => 32),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'type' => 'Type',
            'type_id' => 'Type',
            'name' => 'Name',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className active record class name.
     *
     * @return Lookup the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    /**
     * Извлечение данных для фильтрации на клиенте
     *
     */
    public function getRelationData()
    {
        $command = Yii::app()->db->createCommand()
            ->select('type_id, name')
            ->from("{{lookup}}")
            ->where('type=:type');

        $command->bindValue('type', self::TYPE_PET, PDO::PARAM_STR);
        $result[self::TYPE_PET] = $command->queryAll();

        $command->bindValue('type', self::TYPE_AGE, PDO::PARAM_STR);
        $result[self::TYPE_AGE] = $command->queryAll();

        $command->bindValue('type', self::TYPE_SEX, PDO::PARAM_STR);
        $result[self::TYPE_SEX] = $command->queryAll();

        if (empty($result)) {
            return null;
        }
        return $result;
    }
}
