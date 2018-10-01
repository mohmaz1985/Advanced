<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index pop-up-form">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p >
        <?= Html::button('Create User',[
         'value' => Url::to(['/user/create']),
         'class' => 'btn btn-success',
         'id' => 'modalButton'
     	]) ?>
    </p>
    <?php

	    Modal::begin([
	    'headerOptions'=>[
	    	'class'=>'modal-header',
	    ],
	    'header' => '<h2 class="modal-title">Create User</h2>',
	    'id' => 'modal',
	    'size' => 'modal-lg',
	    
		]);

		echo "<div class='modal-content' id='modalContant'></div>";

		Modal::end();

    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'username',
            // 'auth_key',
            //'password_hash',
            //'password_reset_token',
            // 'email:email',
            // 'status',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
