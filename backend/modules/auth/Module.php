<?php

namespace backend\modules\auth;

/**
 * auth module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */

    public $defaultRoute = 'auth-item';

    public $controllerNamespace = 'backend\modules\auth\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
