<?php

use yii\db\Migration;

/**
 * Class m180913_044939_user_profile
 */
class m180913_044939_user_profile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_profile}}', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer()->notNull(),
            'full_name_ar' => $this->string(225)->notNull()->unique(),
            'full_name_en' => $this->string(225)->notNull()->unique(),
            'user_image' => $this->string(255),
            'country' => $this->string(2)->notNull(),
            'city' => $this->string(2)->notNull(),
            'zip' => $this->string(6)->notNull(),
        ], $tableOptions);

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk_user_id',
            'user_profile',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
        
        /*$this->insert('{{%user_profile}}', [
            'user_id'=>1,
            'full_name_ar'=>'محمد السيد',
            'full_name_en'=>'Mohammad Alsayyed',
            'updated_at' => time(),
        ]);*/
        $this->batchInsert('user_profile', [
            'user_id',
            'full_name_ar',
            'full_name_en',
            'country',
            'city',
            'zip',
            ],
            [
                [1,
                'محمد السيد',
                'Mohammad Alsayyed',
                'JO',
                'Am',
                '+962',
                ],
                [2,
                'تجربة',
                'Demo Demo',
                'US',
                'Wa',
                '+20066',
                ]
            ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk_user_id',
            'user_profile'
        );
        $this->dropTable('user_profile');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180913_044939_user_profile cannot be reverted.\n";

        return false;
    }
    */
}
