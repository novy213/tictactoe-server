<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "game".
 *
 * @property int $id
 * @property int $host_id
 * @property string $name
 * @property int|null $enemy_id
 * @property int|null $invited_player
 * @property int|null $is_password
 * @property string|null $password
 *
 * @property User $enemy
 * @property User $hostPlayer
 * @property User $invitedPlayer
 * @property Move[] $moves
 */
class Game extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['host_id', 'name'], 'required'],
            [['host_id', 'enemy_id', 'invited_player', 'is_password'], 'integer'],
            [['name', 'password'], 'string', 'max' => 255],
            [['enemy_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['enemy_id' => 'id']],
            [['host_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['host_id' => 'id']],
            [['invited_player'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['invited_player' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'host_id' => 'Host Player',
            'name' => 'Name',
            'enemy_id' => 'Enemy ID',
            'invited_player' => 'Invited Player',
            'is_password' => 'Is Password',
            'password' => 'Password',
        ];
    }

    /**
     * Gets query for [[Enemy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEnemy()
    {
        return $this->hasOne(User::class, ['id' => 'enemy_id']);
    }

    /**
     * Gets query for [[HostPlayer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHostPlayer()
    {
        return $this->hasOne(User::class, ['id' => 'host_id']);
    }

    /**
     * Gets query for [[InvitedPlayer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvitedPlayer()
    {
        return $this->hasOne(User::class, ['id' => 'invited_player']);
    }

    /**
     * Gets query for [[Moves]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMoves()
    {
        return $this->hasMany(Move::class, ['game_id' => 'id']);
    }
}
