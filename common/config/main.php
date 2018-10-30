<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', 
        ],
        'generalComp' => [
            'class' => 'common\components\GeneralComponent'
        ],
        'cryptoData' => [
            'class' => 'common\components\Crypto'
        ]
    ],
];
