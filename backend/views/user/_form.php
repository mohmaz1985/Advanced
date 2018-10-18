<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php 

    //Check Unique When Update
    $validationUrl = ['user/validate'];
    if (!$model->isNewRecord)
        $validationUrl['id'] = $model->id;


    $form = ActiveForm::begin([
    'id' => 'user-form',
    'options' => ['enctype'=>'multipart/form-data'],
    'enableAjaxValidation' => true,
    'validationUrl' => $validationUrl
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
            <?php 
            echo $form->field($userProfile, 'country')->dropDownList(
            ArrayHelper::map(Yii::$app->generalComp->countryList($userProfile->country), 'country_code', 'country_name'),
            ['prompt'=>'Select Country',
             'onChange' =>'$.post("index.php?r=user/get-country&countryId='.'"+$(this).val(),function(data){
                    //alert(data);
                    var dataValue = data.split("_");
                    //alert(dataValue[0]);
                    //alert(dataValue[1]);
                    $("#userprofile-zip").val(dataValue[0]);
                    $("select#userprofile-city").html(dataValue[1]);
             });',
            ]) ?>
        </div>
        <div class="form-group col-md-4">
            <?= $form->field($userProfile, 'city')->dropDownList(
                ArrayHelper::map(Yii::$app->generalComp->cityList($userProfile->country), 'city_code', 'city_name'),
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
            <?= $form->field($userProfile, 'image_file')->widget(FileInput::classname(), [
                    'options' => ['accept' => 'image/*'],
                    'pluginOptions' => [
                    'initialPreview'=>[
                        Html::img($userProfile->user_image,['class'=>'file-preview-image kv-preview-data']),
                    ],
                    'overwriteInitial'=>true
                    ]
            ]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
