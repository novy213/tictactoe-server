<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'tictactoe',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'pattern' => '/',
                    'route' => '/auth/login',
                    'verb' => 'POST',
                ],
                [
                    'pattern' => '/',
                    'route' => '/auth/logout',
                    'verb' => 'DELETE',
                ],
                [
                    'pattern' => '/register',
                    'route' => '/site/register',
                    'verb' => 'POST',
                ],
                [
                    'pattern' => '/game',
                    'route' => '/site/creategame',
                    'verb' => 'POST',
                ],
                [
                    'pattern' => '/game',
                    'route' => '/site/getgames',
                    'verb' => 'GET',
                ],
                [
                    'pattern' => '/game/<game_id:\d+>',
                    'route' => '/site/joingame',
                    'verb' => 'POST',
                ],
                [
                    'pattern' => '/game/user',
                    'route' => '/site/getgameinfo',
                    'verb' => 'GET',
                ],
                [
                    'pattern' => '/game/abort',
                    'route' => '/site/abortgame',
                    'verb' => 'DELETE',
                ],
                [
                    'pattern' => '/users',
                    'route' => '/site/getusers',
                    'verb' => 'GET',
                ],
                [
                    'pattern' => '/game/move',
                    'route' => '/site/sendmove',
                    'verb' => 'POST',
                ],
                [
                    'pattern' => '/game/move',
                    'route' => '/site/recivemoves',
                    'verb' => 'GET',
                ],
                [
                    'pattern' => '/game/invites',
                    'route' => '/site/getinvites',
                    'verb' => 'GET',
                ],
                [
                    'pattern' => '/game/<game_id:\d+>',
                    'route' => '/site/rejectgame',
                    'verb' => 'DELETE',
                ],
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '172.29.0.1'],
    ];
}

return $config;
