<?php

namespace app\controllers;

use app\models\Game;
use app\models\Move;
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
        $game = Game::find()->andWhere(['enemy_id'=>null, 'invited_player'=>null])->all();
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
    public function actionGetusers(){
        $user = Yii::$app->user->identity;
        $users = User::find()->andWhere(['<>', 'id', $user->id])->all();
        $return = array();
        for($i=0;$i<count($users);$i++){
            $return[] = [
              'id'=>$users[$i]->id,
              'name'=>$users[$i]->name,
              'last_name'=>$users[$i]->last_name,
            ];
        }
        if(!$users){
            return[
                'error' => true,
                'message' => 'there is no players'
            ];
        }
        else{
            return [
                'error' => FALSE,
                'message' => NULL,
                'users' => $return
            ];
        }
    }
    public function actionSendmove(){
        $user = Yii::$app->user->identity;
        $game = Game::find()->andWhere(['host_id'=>$user->id])->orWhere(['enemy_id'=>$user->id])->one();
        $post = $this->getJsonInput();
        $move = new Move();
        if(!$post || !$game){
            return[
                'error' => true,
                'message' => 'there is something wrong with your request'
            ];
        }
        else{
            $move->move = $post->move;
            $move->player_id = $user->id;
            $move->game_id = $game->id;
            if($move->validate()){
                $move->save();
                return [
                    'error' => FALSE,
                    'message' => NULL,
                ];
            }
            else{
                return [
                    'error' => true,
                    'message' => $move->getErrorSummary(false),
                ];
            }
        }
    }
    public function actionRecivemoves(){
        $user = Yii::$app->user->identity;
        $game = Game::find()->andWhere(['host_id'=>$user->id])->orWhere(['enemy_id'=>$user->id])->one();
        if($game) {
            $win = $game->Win();
            if (!$win['error']) {
                return $win;
            }
        }
        if(!$game){
            return [
                'error' => true,
                'message' => 'this game does not exist'
            ];
        }
        $moves = $game->moves;
        if($moves){
            return [
                'error' => FALSE,
                'message' => NULL,
                'moves'=>$moves
            ];
        }
        else {
            return [
                'error' => false,
                'message' => 'there is no moves in game'
            ];
        }
    }
    public function actionGetinvites(){
        $user = Yii::$app->user->identity;
        $games = Game::find()->andWhere(['invited_player'=>$user->id])->all();
        $invites = array();
        for($i=0;$i<count($games);$i++){
            $invites[]=[
              'game_id' => $games[$i]->id,
              'name' => $games[$i]->name,
              'user_name' => User::find()->andWhere(['id'=>$games[$i]->host_id])->one()->name,
              'user_last_name' => User::find()->andWhere(['id'=>$games[$i]->host_id])->one()->last_name,
            ];
        }
        return [
            'error' => false,
            'message' => null,
            'invites'=>$invites
        ];
    }
    public function actionRejectgame($game_id){
        $user = Yii::$app->user->identity;
        $game = Game::find()->andWhere(['invited_player'=>$user->id, 'id'=>$game_id])->one();
        if($game){
            $game->delete();
            return [
                'error' => false,
                'message' => null
            ];
        }
        else{
            return [
                'error' => true,
                'message' => 'this game does not exist'
            ];
        }
    }
}
