<?php

/**
 * This is the model class for table "{{pf_answers}}".
 *
 * The followings are the available columns in table '{{pf_answers}}':
 * @property integer $id
 * @property integer $comment_id
 * @property integer $author
 * @property integer $time_create
 * @property string $content
 */
class PfAnswers extends RActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{pf_answers}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('comment_id, author, time_create, content', 'required'),
			array('comment_id, author', 'numerical', 'integerOnly'=>true),
            array('content', 'length', 'min' => 3, 'max'=> 255, 'message' => 'Ответ должен содержать не менее 3 и не более 255 символов'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, comment_id, author, time_create, content', 'safe', 'on'=>'search'),
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
            'user' => array(self::BELONGS_TO, 'User', 'author', 'alias' => 'u3'),
		);
	}
    public function defaultScope()
    {
        return array(
            'with' => 'user',
        );
    }
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'comment_id' => 'Comment',
			'author' => 'Author',
			'time_create' => 'Time Create',
			'content' => 'Content',
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
		$criteria->compare('comment_id',$this->comment_id);
		$criteria->compare('author',$this->author);
		$criteria->compare('time_create',$this->time_create);
		$criteria->compare('content',$this->content,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PfAnswers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function loadAnswer($id)
    {
        $query = $this->findByPk($id);
        $result = $this->convertModelToArray($query);
        return $result;
    }
}
