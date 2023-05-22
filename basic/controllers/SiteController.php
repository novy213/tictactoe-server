<?php

namespace app\controllers;

use app\models\Game;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /*public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }*/

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
    /*public function actionIndex()
    {
        return $this->render('index');
    }*/

    public function actionRegister(){
        $post = $this->getJsonInput();
        $user = new User();
        if (isset($post->login)) {
            $user->login = $post->login;
        }
        if (isset($post->password)) {
            $user->password = $post->password;
        }
        if (isset($post->name)) {
            $user->name = $post->name;
        }
        if (isset($post->last_name)) {
            $user->last_name = $post->last_name;
        }
        if ($user->validate()) {
            $user->save();
            return [
                'error' => FALSE,
                'message' => NULL,
            ];
        } else {
            return [
                'error' => true,
                'message' => $user->getErrorSummary(false),
            ];
        }
    }
    public function actionCreategame(){
        $user = Yii::$app->user->identity;
        $post = $this->getJsonInput();
        $game = new Game();
        if(isset($user)){
            $game->host_id = $user->id;
        }
        if(isset($post->name)){
            $game->name = $post->name;
        }
        if(isset($post->invited_player)){
            $game->invited_player = $post->invited_player;
        }
        if(isset($post->is_password)){
            $game->is_password = $post->is_password;
            if(isset($post->password)){
                $game->password = $post->password;
            }
            else {
                return [
                    'error' => true,
                    'message' => 'password is required when password is enabled'
                ];
            }
        }
        if($game->validate()){
            $game->save();
            return [
                'error' => FALSE,
                'message' => NULL,
            ];
        }
        else {
            return [
                'error' => true,
                'message' => $game->getErrorSummary(false),
            ];
        }
    }
    public function actionGetgames(){
        $game = Game::find()->andWhere(['enemy_id'=>null])->all();
        $games=array();
        for($i=0;$i<count($game);$i++){
            $games[] =[
                'id' => $game[$i]->id,
                'host_id' => $game[$i]->host_id,
                'name' => $game[$i]->name,
                'enemy_id' => $game[$i]->enemy_id,
                'invited_player' => $game[$i]->invited_player,
                'is_password' => $game[$i]->is_password,
                'password' => $game[$i]->password,
                'user_name' => $game[$i]->host->name,
                'user_last_name' => $game[$i]->host->last_name
            ];
        }
        return [
            'error' => FALSE,
            'message' => NULL,
            'games' => $games
        ];
    }
    public function actionJoingame($game_id){
        $post = $this->getJsonInput();
        $user = yii::$app->user->identity;
        $game = Game::find()->andWhere(['id'=>$game_id])->one();
        $userGame = Game::find()->andWhere(['host_id'=>$user->id])->orWhere(['enemy_id'=>$user->id])->one();
        if(!$userGame && $user->id != $game_id) {
            if (!$game) {
                return [
                    'error' => true,
                    'message' => 'this game does not exist',
                ];
            } else {
                if (!$game->is_password) {
                    if ($game->enemy_id != null) {
                        return [
                            'error' => true,
                            'message' => 'this game is full',
                        ];
                    } else {
                        $game->enemy_id = $user->id;
                        $start = rand(0,1);
                        if($start==1) $game->turn = $game->host_id;
                        else if($start==0) $game->turn = $game->enemy_id;
                        $game->update();
                        return [
                            'error' => FALSE,
                            'message' => NULL,
                        ];
                    }
                } else {
                    if ($post->password == $game->password) {
                        if ($game->enemy_id != null) {
                            return [
                                'error' => true,
                                'message' => 'this game is full',
                            ];
                        } else {
                            $game->enemy_id = $user->id;
                            $start = rand(0,1);
                            if($start==1) $game->turn = $game->host_id;
                            else if($start==0) $game->turn = $game->enemy_id;
                            $game->update();
                            return [
                                'error' => FALSE,
                                'message' => NULL,
                            ];
                        }
                    } else {
                        return [
                            'error' => true,
                            'message' => 'incorrect password'
                        ];
                    }
                }
            }
        }
        else {
            return [
                'error' => true,
                'message' => 'this user is currently in game'
            ];
        }
    }
    public function actionGetgameinfo(){
        $user = Yii::$app->user->identity;
        $game = Game::find()->andWhere(['host_id'=>$user->id])->orWhere(['enemy_id'=>$user->id])->one();
        if(!$game){
            return[
                'error' => true,
                'message' => 'this player is not in any game'
            ];
        }
        else{
            return [
                'error' => FALSE,
                'message' => NULL,
                'game' => $game
            ];
        }
    }
    public function actionAbortgame(){
        $user = Yii::$app->user->identity;
        $game = Game::find()->andWhere(['host_id'=>$user->id])->orWhere(['enemy_id'=>$user->id])->one();
        if(!$game){
            return[
                'error' => true,
                'message' => 'this player is not in any game'
            ];
        }
        else{
            $game->delete();
            return [
                'error' => FALSE,
                'message' => NULL,
            ];
        }
    }
}
