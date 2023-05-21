<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "move".
 *
 * @property int $id
 * @property string $move
 * @property int $player_id
 * @property int $game_id
 *
 * @property Game $game
 * @property User $player
 */
class Move extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'move';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['move', 'player_id', 'game_id'], 'required'],
            [['player_id', 'game_id'], 'integer'],
            [['move'], 'string', 'max' => 255],
            [['game_id'], 'exist', 'skipOnError' => true, 'targetClass' => Game::class, 'targetAttribute' => ['game_id' => 'id']],
            [['player_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['player_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'move' => 'Move',
            'player_id' => 'Player ID',
            'game_id' => 'Game ID',
        ];
    }

    /**
     * Gets query for [[Game]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGame()
    {
        return $this->hasOne(Game::class, ['id' => 'game_id']);
    }

    /**
     * Gets query for [[Player]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayer()
    {
        return $this->hasOne(User::class, ['id' => 'player_id']);
    }
}
