<?php

use yii\db\Migration;

/**
 * Class m230521_184223_fake_data
 */
class m230521_184223_fake_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('user', ['login'=>'test', 'password'=>password_hash('test', PASSWORD_BCRYPT),'name'=>'John','last_name'=>'Doe']);
        $this->insert('user', ['login'=>'alice', 'password'=>password_hash('password123', PASSWORD_BCRYPT),'name'=>'Alice','last_name'=>'Smith']);
        $this->insert('user', ['login'=>'bob', 'password'=>password_hash('abc123', PASSWORD_BCRYPT),'name'=>'Bob','last_name'=>'Johnson']);
        $this->insert('user', ['login'=>'emma', 'password'=>password_hash('qwerty', PASSWORD_BCRYPT),'name'=>'Emma','last_name'=>'Davis']);
        $this->insert('user', ['login'=>'alex', 'password'=>password_hash('securepass', PASSWORD_BCRYPT),'name'=>'Alex','last_name'=>'Wilson']);
        $this->insert('user', ['login'=>'sara', 'password'=>password_hash('pass123', PASSWORD_BCRYPT),'name'=>'Sara','last_name'=>'Brown']);
        $this->insert('user', ['login'=>'michael', 'password'=>password_hash('testpass', PASSWORD_BCRYPT),'name'=>'Michael','last_name'=>'Taylor']);
        $this->insert('user', ['login'=>'lucy', 'password'=>password_hash('lucy123', PASSWORD_BCRYPT),'name'=>'Lucy','last_name'=>'Roberts']);
        $this->insert('user', ['login'=>'max', 'password'=>password_hash('maxpass', PASSWORD_BCRYPT),'name'=>'Max','last_name'=>'Anderson']);
        $this->insert('user', ['login'=>'olivia', 'password'=>password_hash('olivia2023', PASSWORD_BCRYPT),'name'=>'Olivia','last_name'=>'Clark']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('user', ['name' => 'John']);
        $this->delete('user', ['name' => 'Alice']);
        $this->delete('user', ['name' => 'Bob']);
        $this->delete('user', ['name' => 'Emma']);
        $this->delete('user', ['name' => 'Alex']);
        $this->delete('user', ['name' => 'Sara']);
        $this->delete('user', ['name' => 'Michael']);
        $this->delete('user', ['name' => 'Lucy']);
        $this->delete('user', ['name' => 'Max']);
        $this->delete('user', ['name' => 'Olivia']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230521_184223_fake_data cannot be reverted.\n";

        return false;
    }
    */
}
