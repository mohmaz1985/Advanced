<?php
namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

class AngularAsset extends AssetBundle
{
    public $sourcePath = '@bower';
    public $js = [
        'angular/angular.js',
        'angular-route/angular-route.js',
        'angular-ui4/ui-bootstrap-tpls-3.0.4.min.js',
    ];
    public $jsOptions = [
        'position' => View::POS_HEAD,
    ];
}
