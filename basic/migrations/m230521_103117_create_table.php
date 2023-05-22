<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%}}`.
 */
class m230521_103117_create_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey()->notNull()->unique(),
            'login' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
            'name' => $this->string(),
            'last_name' => $this->string(),
            'access_token' => $this->string()
        ]);
        $this -> alterColumn('user','id', $this->integer().' AUTO_INCREMENT');
        $this->createTable('game', [
            'id' => $this->primaryKey()->notNull()->unique(),
            'host_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'enemy_id' =>$this->integer(),
            'invited_player' =>$this->integer(),
            'is_password' =>$this->boolean()->defaultValue(false),
            'password' =>$this->string(),
            'turn' =>$this->integer(),
        ]);
        $this -> alterColumn('game','id', $this->integer().' AUTO_INCREMENT');
        $this->addForeignKey(
            'fk-turn',
            'game',
            'turn',
            'user',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-host_id',
            'game',
            'host_id',
            'user',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-enemy_id',
            'game',
            'enemy_id',
            'user',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-invited_player',
            'game',
            'invited_player',
            'user',
            'id',
            'CASCADE'
        );
        $this->createTable('move', [
            'id' => $this->primaryKey()->notNull()->unique(),
            'move' =>$this->string()->notNull(),
            'player_id' =>$this->integer()->notNull(),
            'game_id' =>$this->integer()->notNull(),
        ]);
        $this -> alterColumn('move','id', $this->integer().' AUTO_INCREMENT');
        $this->addForeignKey(
            'fk-player_id',
            'move',
            'player_id',
            'user',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-game_id',
            'move',
            'game_id',
            'game',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-invited_player', 'game');
        $this->dropForeignKey('fk-turn', 'game');
        $this->dropForeignKey('fk-enemy_id', 'game');
        $this->dropForeignKey('fk-host_id', 'game');
        $this->dropForeignKey('fk-player_id', 'move');
        $this->dropForeignKey('fk-game_id', 'move');
        $this->dropTable('user');
        $this->dropTable('game');
        $this->dropTable('move');
    }
}
