<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'auth' => [
            'class' => 'common\modules\auth\module',
        ],
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'generalComp' => [
            'class' => 'common\components\GeneralComponent'
        ],
        'cryptoData' => [
            'class' => 'common\components\Crypto'
        ],
        'authManager'  => 
        [
            'class' => 'yii\rbac\DbManager',
        ]
    ],
];
