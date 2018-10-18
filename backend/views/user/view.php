<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            [
                'attribute' => 'full_name_ar',
                'value' => $model->userProfiles->full_name_ar
            ],
            'email:email',
             [
                'attribute' => 'user_image',
                'format' => 'raw',
                'value' => function($data){ 

                $path = $data['userProfiles']['user_image'];
                $class= 'img-thumbnail rounded';
                $width = '190px';
                $height = '190px';
                $alt = 'ggg';
                return Yii::$app->generalComp->getImage($path,$class,$width,$height,$alt);
                },
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => Yii::$app->generalComp->getStatus($model->status)
            ],
            [
                'attribute' => 'created_by',
                'format' => 'raw',
                'value' => Yii::$app->generalComp->userInformation('full_name_en',$model->created_by)
            ],
            [
                'attribute' => 'created_at',
                'format' => 'raw',
                'value' => Yii::$app->generalComp->getDateAndTime($model->created_at)
            ],
            [
                'attribute' => 'updated_by',
                'format' => 'raw',
                'value' => Yii::$app->generalComp->userInformation('full_name_en',$model->updated_by)
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'raw',
                'value' => Yii::$app->generalComp->getDateAndTime($model->updated_at)
            ],
        ],
    ]) ?>

</div>
