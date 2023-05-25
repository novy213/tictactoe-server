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
 * @property int|null $turn
 *
 * @property User $enemy
 * @property User $host
 * @property User $invitedPlayer
 * @property Move[] $moves
 * @property User $turn0
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
            [['host_id', 'enemy_id', 'invited_player', 'is_password', 'turn'], 'integer'],
            [['name', 'password'], 'string', 'max' => 255],
            [['enemy_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['enemy_id' => 'id']],
            [['host_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['host_id' => 'id']],
            [['invited_player'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['invited_player' => 'id']],
            [['turn'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['turn' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'host_id' => 'Host ID',
            'name' => 'Name',
            'enemy_id' => 'Enemy ID',
            'invited_player' => 'Invited Player',
            'is_password' => 'Is Password',
            'password' => 'Password',
            'turn' => 'Turn',
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
     * Gets query for [[Host]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHost()
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

    /**
     * Gets query for [[Turn0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTurn0()
    {
        return $this->hasOne(User::class, ['id' => 'turn']);
    }
    public function Win(){
        $moves = array();
        $move = Move::find()->andWhere(['game_id'=>$this->id])->all();
        $char = "X";
        for($i=0;$i<9;$i++) $moves[$i]="";
        for($i=0;$i<count($move);$i++){
            switch ($move[$i]->move){
                case "a1":
                    $moves[0]=$char;
                    break;
                case "a2":
                    $moves[1]=$char;
                    break;
                case "a3":
                    $moves[2]=$char;
                    break;
                case "b1":
                    $moves[3]=$char;
                    break;
                case "b2":
                    $moves[4]=$char;
                    break;
                case "b3":
                    $moves[5]=$char;
                    break;
                case "c1":
                    $moves[6]=$char;
                    break;
                case "c2":
                    $moves[7]=$char;
                    break;
                case "c3":
                    $moves[8]=$char;
                    break;
            }
            if ($char == "X") $char = "O";
            else if ($char == "O") $char = "X";
        }
        if($moves[0]=="X" && $moves[1]=="X" && $moves[2]=="X"){
            return[
                'error'=>false,
                'message'=>"Winner is X",
            ];
        }
        if($moves[3]=="X" && $moves[4]=="X" && $moves[5]=="X"){
            return[
                'error'=>false,
                'message'=>"Winner is X",
            ];
        }
        if($moves[6]=="X" && $moves[7]=="X" && $moves[8]=="X"){
            return[
                'error'=>false,
                'message'=>"Winner is X",
            ];
        }
        if($moves[0]=="X" && $moves[3]=="X" && $moves[6]=="X"){
            return[
                'error'=>false,
                'message'=>"Winner is X",
            ];
        }
        if($moves[1]=="X" && $moves[4]=="X" && $moves[7]=="X"){
            return[
                'error'=>false,
                'message'=>"Winner is X",
            ];
        }
        if($moves[2]=="X" && $moves[5]=="X" && $moves[8]=="X"){
            return[
                'error'=>false,
                'message'=>"Winner is X",
            ];
        }
        if($moves[0]=="X" && $moves[4]=="X" && $moves[8]=="X"){
            return[
                'error'=>false,
                'message'=>"Winner is X",
            ];
        }
        if($moves[2]=="X" && $moves[4]=="X" && $moves[6]=="X"){
            return[
                'error'=>false,
                'message'=>"Winner is X",
            ];
        }
        if($moves[0]=="O" && $moves[1]=="O" && $moves[2]=="O"){
            return[
                'error'=>false,
                'message'=>"Winner is O",
            ];
        }
        if($moves[3]=="O" && $moves[4]=="O" && $moves[5]=="O"){
            return[
                'error'=>false,
                'message'=>"Winner is O",
            ];
        }
        if($moves[6]=="O" && $moves[7]=="O" && $moves[8]=="O"){
            return[
                'error'=>false,
                'message'=>"Winner is O",
            ];
        }
        if($moves[0]=="O" && $moves[3]=="O" && $moves[6]=="O"){
            return[
                'error'=>false,
                'message'=>"Winner is O",
            ];
        }
        if($moves[1]=="O" && $moves[4]=="O" && $moves[7]=="O"){
            return[
                'error'=>false,
                'message'=>"Winner is O",
            ];
        }
        if($moves[2]=="O" && $moves[5]=="O" && $moves[8]=="O"){
            return[
                'error'=>false,
                'message'=>"Winner is O",
            ];
        }
        if($moves[0]=="O" && $moves[4]=="O" && $moves[8]=="O"){
            return[
                'error'=>false,
                'message'=>"Winner is O",
            ];
        }
        if($moves[2]=="O" && $moves[4]=="O" && $moves[6]=="O"){
            return[
                'error'=>false,
                'message'=>"Winner is O",
            ];
        }
        if($moves[0]!=null && $moves[1]!=null&& $moves[2]!=null &&
            $moves[3]!=null && $moves[4]!=null&& $moves[5]!=null &&
            $moves[6]!=null && $moves[7]!=null&& $moves[8]!=null){
            return[
                'error'=>false,
                'message'=>"Draw",
            ];
        }
        else {
            return[
                'error'=>true,
                'message'=>"No winner",
            ];
        }
    }
}
