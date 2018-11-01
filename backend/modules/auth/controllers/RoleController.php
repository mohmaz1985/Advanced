<?php

namespace backend\modules\auth\controllers;

use yii\rbac\Item;
use backend\modules\auth\controllers\AuthItemController;

/**
 * Class PermissionController
 */

class RoleController extends AuthItemController{

	 /**
     * @var int
     */
    protected $type = Item::TYPE_ROLE;

    /**
     * @var array
     */
    protected $labels = [
        'Item' => 'Role',
        'Items' => 'Roles',
    ];
}

?>