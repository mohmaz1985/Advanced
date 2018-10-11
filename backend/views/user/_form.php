<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
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
    <div class="form-row">
        <div class="form-group col-md-6">
            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
        </div>
        
        <div class="form-group col-md-6">
            <?= $form->field($model, 'password_hash')->passwordInput() ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
           <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?> 
        </div>
        <div class="form-group col-md-6">
            <?= $form->field($model, 'status')->dropDownList(
            ArrayHelper::map(Yii::$app->generalComp->userStatusList(), 'id', 'name'),
            ['prompt'=>'Select Status']) ?>
        </div>
    </div>
    <div class="form-row">
        <h5 class="border-bottom">Personal Information:</h5>
    </div>
    <!-- User Profile Form -->
    <div class="form-row">
        <div class="form-group col-md-6">
            <?= $form->field($userProfile, 'full_name_ar')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="form-group col-md-6">
            <?= $form->field($userProfile, 'full_name_en')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-4">
            <?= $form->field($userProfile, 'country')->dropDownList(
            ArrayHelper::map(Yii::$app->generalComp->countryList(), 'country_code', 'country_name'),
            ['prompt'=>'Select Country',
             'onChange' =>'$.post("index.php?r=user/user-location&countryId='.'"+$(this).val(),function(data){
                    //alert(data);
                    var dataValue = data.split("__");
                    //alert(dataValue[0]);
                    //alert(dataValue[1]);
                    $("#userprofile-zip").val(dataValue[0]);
                    $("select#userprofile-city").html(dataValue[1]);
             });',
            ]) ?>
        </div>
        <div class="form-group col-md-4">
            <?= $form->field($userProfile, 'city')->dropDownList(
            ['prompt'=>'Select City']) ?>
        </div>
        <div class="form-group col-md-4">
            <?= $form->field($userProfile, 'zip')->textInput() ?>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-12">
            <?= $form->field($userProfile, 'full_address')->textInput() ?>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-12">
            <?= $form->field($userProfile, 'user_image')->textInput() ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
