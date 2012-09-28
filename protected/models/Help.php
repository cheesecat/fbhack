<?php

/**
 * This is the model class for table "help".
 *
 * The followings are the available columns in table 'help':
 * @property string $id
 * @property double $lat
 * @property double $long
 * @property string $place
 * @property string $image
 * @property int $user_id
 */
class Help extends CActiveRecord
{
    public $image;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Help the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'help';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('lat, long', 'numerical'),
            array('place', 'length', 'max'=>256),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, lat, long, place, image', 'safe', 'on'=>'search'),
            array('image', 'file', 'types'=>'jpg, gif, png', 'allowEmpty' => true),

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
			'lat' => 'Lat',
			'long' => 'Long',
			'place' => 'Place',
			'image' => 'Image',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('lat',$this->lat);
		$criteria->compare('long',$this->long);
		$criteria->compare('place',$this->place,true);
		$criteria->compare('image',$this->image,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}