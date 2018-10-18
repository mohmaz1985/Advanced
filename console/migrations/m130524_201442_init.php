<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull()->defaultValue('0'),
            'updated_at' => $this->integer()->notNull()->defaultValue('0'),
        ], $tableOptions);
        
       /* $this->insert('{{%user}}', [
            'username' => 'mohammad',
            'password_hash' => Yii::$app->security->generatePasswordHash('123456'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_reset_token' =>Yii::$app->security->generateRandomString() . '_' . time(),
            'email' => 'mohmaz1985@yahoo.com',
            'created_at' => time(),
            'updated_at' => time(),
        ]);*/
        $this->batchInsert('user', [
            'id',
            'username',
            'password_hash',
            'auth_key',
            'password_reset_token',
            'email',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at'],
            [
                [1,
                'mohammad',
                Yii::$app->security->generatePasswordHash('123456'),
                Yii::$app->security->generateRandomString(),
                Yii::$app->security->generateRandomString() . '_' . time(),
                'mohmaz1985@yahoo.com',
                '1',
                time(),
                '1',
                time(),
                ],
                [2,
                'demo',
                Yii::$app->security->generatePasswordHash('demo'),
                Yii::$app->security->generateRandomString(),
                Yii::$app->security->generateRandomString() . '_' . time(),
                'demo@yahoo.com',
                '1',
                time(),
                '1',
                time(),
                ]
            ]);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
