<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "branch".
 *
 * @property integer $id
 * @property string $branch_name
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 */
class Branch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'branch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['branch_name', 'description'], 'required'],
            [['description'], 'string'],
            //[['created_at', 'updated_at'], 'integer'],
            [['branch_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            //'id' => 'ID',
            'branch_name' => 'Branch Name',
            'description' => 'Description',
            //'created_at' => 'Created At',
            //'updated_at' => 'Updated At',
        ];
    }
}
