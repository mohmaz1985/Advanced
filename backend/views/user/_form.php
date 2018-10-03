<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url
/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
    'id' => 'user-form',
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute('user/validate')
    ]); 
    //'validationUrl' => 'validate'
    ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password_hash')->passwordInput() ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <!-- User Profile Form -->
    <?= $form->field($userProfile, 'full_name_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($userProfile, 'full_name_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($userProfile, 'user_image')->textInput() ?>

    <?= $form->field($userProfile, 'country')->textInput() ?>

    <?= $form->field($userProfile, 'city')->textInput() ?>

    <?= $form->field($userProfile, 'zip')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
