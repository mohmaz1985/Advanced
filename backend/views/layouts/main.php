<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\web\View;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body ng-app="app" ng-controller="sidebarCtrl" ng-cloak window-size>
<?php $this->beginBody() ?>
<div class="loader sticky-top" ng-show="isLoading"></div>
    <?php echo $this->render('//layouts/header');?>
	
	<!-- Container -->
	<div class="container-fluid d-flex">
	<?php 
		if(!Yii::$app->user->isGuest)
			echo $this->render('//layouts/aside-menu');
	?>
	<!-- Main Block -->
	<main role="main" class="main-block flex-grow-1 mx-2" >
	<?php
	if(!Yii::$app->user->isGuest){
	echo Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]);
	} ?>
    <?= Alert::widget() ?>
	<?=$content?>
	</main>
	</div>
    
    <?php echo $this->render('//layouts/footer'); ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>