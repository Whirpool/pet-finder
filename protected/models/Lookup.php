<?php

/**
 * This is the model class for table "{{lookup}}".
 *
 * The followings are the available columns in table '{{lookup}}':
 * @property integer $id
 * @property string $type
 * @property integer $type_id
 * @property string $name
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
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, type_id, name', 'required'),
			array('type_id', 'numerical', 'integerOnly'=>true),
			array('type, name', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, type_id, name', 'safe', 'on'=>'search'),
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

		$criteria->compare('id',$this->id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Lookup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    /**
     * Извлечение данных из выбранной таблицы
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
