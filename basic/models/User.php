<?php

namespace app\models;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
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

    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            [['login', 'password', 'name', 'last_name', 'access_token'], 'string', 'max' => 255],
        ];
    }
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
    public function createApiToken()
    {
        $this->access_token = \Yii::$app->security->generateRandomString();
        $this->updateAttributes(['access_token']);
        return $this->access_token;
    }

    public function clearApiToken()
    {
        $this->access_token = null;
        $this->updateAttributes(['access_token']);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user = self::findOne(['access_token' => $token]);
        return $user;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['login' => $username]);
    }
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {

    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return password_verify($password, $this->password);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);
            return true;
        }
        return false;
    }
}
