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
 * @property integer $status
 * @property integer $updated_at
 *
 * @property User $user
 */
class UserProfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
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
            [['user_id', 'full_name_ar', 'full_name_en', 'country', 'city', 'zip', 'updated_at'], 'required'],
            [['user_id', 'status', 'updated_at'], 'integer'],
            [['full_name_ar', 'full_name_en'], 'string', 'max' => 225],
            [['user_image'], 'string', 'max' => 255],
            [['country', 'city', 'zip'], 'string', 'max' => 2],
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
            'user_image' => 'User Image',
            'country' => 'Country',
            'city' => 'City',
            'zip' => 'Zip',
            'status' => 'Status',
            'updated_at' => 'Updated At',
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
