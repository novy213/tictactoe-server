<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $login
 * @property string $password
 * @property string|null $name
 * @property string|null $last_name
 * @property string|null $access_token
 *
 * @property Game[] $games
 * @property Game[] $games0
 * @property Game[] $games1
 * @property Move[] $moves
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            [['login', 'password', 'name', 'last_name', 'access_token'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'password' => 'Password',
            'name' => 'Name',
            'last_name' => 'Last Name',
            'access_token' => 'Access Token',
        ];
    }

    /**
     * Gets query for [[Games]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGames()
    {
        return $this->hasMany(Game::class, ['enemy_id' => 'id']);
    }

    /**
     * Gets query for [[Games0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGames0()
    {
        return $this->hasMany(Game::class, ['host_player' => 'id']);
    }

    /**
     * Gets query for [[Games1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGames1()
    {
        return $this->hasMany(Game::class, ['invited_player' => 'id']);
    }

    /**
     * Gets query for [[Moves]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMoves()
    {
        return $this->hasMany(Move::class, ['player_id' => 'id']);
    }
}
