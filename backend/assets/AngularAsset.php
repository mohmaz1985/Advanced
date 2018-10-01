<?php
namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

class AngularAsset extends AssetBundle
{
    public $sourcePath = '@bower';
    public $js = [
        ['angular/angular.min.js','position' => View::POS_HEAD],
        ['angular-route/angular-route.min.js','position' => View::POS_HEAD],
        ['angular-ui4/ui-bootstrap-tpls-3.0.4.min.js','position' => View::POS_HEAD],
        ['angular/js/angular-animate.min.js','position' => View::POS_HEAD],
        ['angular/app/app.js','position' => View::POS_HEAD],
        'angular/js/Chart.min.js',
        'angular/js/angular-chart.min.js',
        'angular/js/moment.js',
        'angular/js/moment-timezone.js',
        'angular/js/angular-moment.js',
        'http://d3js.org/d3.v3.min.js',
        'angular/js/angular-map-it.js',
        'angular/app/controllers/sidebarCtrl.js',
        'angular/app/controllers/dropdownCtrl.js',
        'angular/app/controllers/popUpFormCtrl.js',
        'angular/app/controllers/modalCtrl.js',
        'angular/app/directives/windowSize.js',
        'angular/app/directives/dateTime.js',
        'angular/app/controllers/mapCtrl.js',
        'angular/app/controllers/chartCtrl.js'
    ];
    /*public $jsOptions = [
        'position' => View::POS_HEAD,
    ];*/
}
