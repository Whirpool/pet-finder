<?php

/**
 * This is the model class for table "{{search_images}}".
 *
 * The followings are the available columns in table '{{search_images}}':
 * @property integer $id
 * @property string $pet_id
 * @property string $mime
 * @property string $source_original
 * @property string $name_original
 * @property string $source_small
 * @property string $name_small
 */
class Images extends CActiveRecord
{
    public $file;


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'data_pet_images';
	}


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			['pet_id, source_original, source_small, mime, name_original, name_small', 'required', 'except' => 'upload'],
            ['file', 'application.components.ImageValidator', 'on' => 'upload']
		];
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return [
            'pet' => [self::BELONGS_TO, 'Pet', 'pet_id'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'post_id' => 'Post',
			'size' => 'Size',
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
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('post_id',$this->post_id,true);
		$criteria->compare('size',$this->size);
		$criteria->compare('source',$this->source,true);
		$criteria->compare('mime',$this->mime,true);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SearchImages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function createImageFolder($path){
        if (!is_dir($path)) {
            mkdir($path);
            chmod($path, 0755);
            return true;
        }
        return false;
    }
}
