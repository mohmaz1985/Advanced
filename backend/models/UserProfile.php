<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user_profile".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $full_name_ar
 * @property string $full_name_en
 * @property string $user_image
 * @property string $country
 * @property string $city
 * @property string $zip
 * @property string $full_address
 *
 * @property User $user
 */
class UserProfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $image_file;
    public static function tableName()
    {
        return 'user_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'full_name_ar', 'full_name_en', 'country', 'city', 'zip','full_address'], 'required'],
            [['user_id'], 'integer'],
            [['full_name_ar', 'full_name_en'], 'string', 'max' => 225],
            [['image_file'],'file'],
            [['user_image'], 'string', 'max' => 255],
            [['country'], 'string', 'max' => 2],
            [['zip','city'], 'string', 'max' => 6],
            [['full_address'], 'string', 'max' => 400],
            [['full_name_ar'], 'unique'],
            [['full_name_en'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'full_name_ar' => 'Full Name Ar',
            'full_name_en' => 'Full Name En',
            'image_file' => 'User Image',
            'country' => 'Country',
            'city' => 'City',
            'zip' => 'Zip',
            'full_address' => 'Address',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
