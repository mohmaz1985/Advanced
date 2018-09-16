<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ComBranch */

$this->title = 'Create Com Branch';
$this->params['breadcrumbs'][] = ['label' => 'Com Branches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="com-branch-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
