<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="message" ></div>
<div class="user-index pop-up-form" >
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p >
        <?= Html::button('Create User',[
         'value' => Url::to(['/user/create']),
         'class' => 'btn btn-success',
         'id' => 'modalButton'
     	]) ?>
    </p>
    <div class="loader sticky-top" style="display: none" id="loadPage"></div>

    <?php

	    Modal::begin([
	    'headerOptions'=>[
	    	'class'=>'modal-header',
	    ],
	    'header' => '<h2 class="modal-title">Create User</h2>',
	    'id' => 'modal-form',
	    'size' => 'modal-md',
        'clientOptions' => ['backdrop' => 'static', 'keyboard' => false],
	    
		]);
        ?>
        

		<div class='modal-content' id='modalContant'></div>
        <?php

		Modal::end();

    ?>
    <?php Pjax::begin(['id'=>'userIDView','enablePushState'=>false]);?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'username',
            [
                'attribute' => 'full_name_ar',
                'value' => 'userProfiles.full_name_ar'
            ],
            [
                'attribute' => 'user_image',
                'format' => 'raw',
                'value' => function($data){ 

                $path = $data['userProfiles']['user_image'];
                $class= 'img-thumbnail rounded mx-auto d-block';
                $width = '90px';
                $height = '90px';
                $alt = 'ggg';
                return Yii::$app->generalComp->getImage($path,$class,$width,$height,$alt);
                },
            ],
            // 'auth_key',
            //'password_hash',
            //'password_reset_token',
            // 'email:email',
            // 'status',
            // 'created_at',
            // 'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons'=>[
                'update' => function ($url, $model) {
                   $url = Url::to(['user/update', 'id' => $model->id]);
                   return Html::a('<span class="fas fa-pencil-alt"></span>', '#', ['title' => 'update','value' =>$url,'id' => 'modalButtonIndexUpdate']);

               }
            ],


            ],
        ],

    ]); ?>
    <?php Pjax::end();?>
</div>