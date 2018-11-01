<?php

namespace backend\modules\auth\controllers;

use yii\rbac\Item;
use backend\modules\auth\controllers\AuthItemController;

/**
 * Class PermissionController
 */

class PermissionController extends AuthItemController{

	 /**
     * @var int
     */
    protected $type = Item::TYPE_PERMISSION;

    /**
     * @var array
     */
    protected $labels = [
        'Item' => 'Permission',
        'Items' => 'Permissions',
    ];
}

?>