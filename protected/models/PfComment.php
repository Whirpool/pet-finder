<?php

/**
 * This is the model class for table "{{pf_comment}}".
 *
 * The followings are the available columns in table '{{pf_comment}}':
 * @property integer $id
 * @property integer $post_id
 * @property integer $author
 * @property integer $time_create
 * @property string $content
 */
class PfComment extends RActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{pf_comment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('post_id, author, time_create, content', 'required'),
			array('post_id, author', 'numerical', 'integerOnly'=>true),
            array('content', 'length', 'min' => 3, 'max'=> 255, 'message' => 'Комментарий должен содержать не менее 3 и не более 255 символов'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, post_id, author, time_create, content', 'safe', 'on'=>'search'),
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
            'user'      => array(self::BELONGS_TO, 'User', 'author', 'alias' => 'u2'),
            'answers' => array(self::HAS_MANY, 'PfAnswers', 'comment_id', 'order' => 'a.time_create', 'alias' => 'a'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'post_id' => 'Post',
			'author' => 'Author',
			'time_create' => 'Time Create',
			'content' => 'Content',
		);
	}

    public function defaultScope()
    {
        return array(
            'with' => ['answers', 'user'],
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
		$criteria->compare('post_id',$this->post_id);
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
	 * @return PfComment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function loadComment($id)
    {
        $query = $this->findByPk($id);
        $result = $this->convertModelToArray($query);
        return $result;
    }
}
