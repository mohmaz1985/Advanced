<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

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
<body>
<?php $this->beginBody() ?>
<div class="loader sticky-top" ng-show="isLoading"></div>
    <!-- Main Header -->
    <header class="main-header">
      <nav class="navbar fixed-top flex-nowrap shadow-lg">
        <a href="#" class="navbar-brand">
          <span class="px-1" ng-show="showHide">ALSayyed Dashboard</span>
          <span class="px-1" ng-hide="showHide">SD</span>
        </a>
        <a class="navbar-toggler" ng-click="sideBar();">
        <span class="p-1 navbar-toggler-icon"><i class="fas fa-align-justify"></i></span>
        </a>
        <ul class="nav ml-auto mr-3" ng-controller="modalCtrl">
          <li class="nav-item" ng-click="dateTime()">
            <i class="fas fa-clock"></i>
            <span class="show-text" >Clock</span> 
          </li>
          <li class="nav-item pl-3" ng-click="openProfile()">
            <i class="fas fa-user-circle"></i> 
            <span class="show-text">Profile</span>
          </li>
          <li class="nav-item pl-3">
            <i class="fas fa-sign-out-alt"></i>
            <span class="show-text">Sign Out</span> 
          </li>
        </ul>
      </nav>

    </header>
    
    <!-- Container -->
    <div class="container-fluid d-flex">
        <!-- sidebar -->
        <aside class="sidebar-menu flex-shrink-0  {{sideBarStatusClass}} "  ng-controller="dropdownCtrl" >
          <nav class="sidebar" >
            <ul class="nav flex-column" >
                    
              <li class="nav-item" uib-dropdown>
                <div class="dropdown-toggle p-2" uib-dropdown-toggle ng-disabled="disabled">
                 <i class="fas fa-tachometer-alt"></i>
                  <span class="nav-link p-2 show-text active">Dashboard</span>
                </div>
              </li>
              <li class="nav-item mb-1" uib-dropdown ng-repeat=" sideBarItem in sideBarMenuList">
                <div class="dropdown-toggle p-2" uib-dropdown-toggle ng-disabled="disabled">
                 <i class="fas fa-{{sideBarItem.icon}}"></i>
                  <span class="nav-link p-2 show-text">{{sideBarItem.title}}</span>
                </div>
                <ul class="dropdown-menu shadow" role="menu">
                  <li ng-show="{{sideBarItem.list}}"><a href="#" class="dropdown-item">
                    <i class="fas fa-clipboard-list"></i>
                    {{sideBarItem.title}} List
                  </a></li>
                  <li ng-show="{{sideBarItem.add}}"><a href="#" class="dropdown-item">
                    <i class="fas fa-plus"></i>
                    Add {{sideBarItem.title}}</a>
                  </li>
                  <li ng-show="{{sideBarItem.catigory}}"><a href="#" class="dropdown-item">
                    <i class="fas fa-code-branch"></i>
                    {{sideBarItem.title}} Categories</a>
                  </li>
                  <div ng-show="{{sideBarItem.settings}}" class="dropdown-divider"></div>
                  <li ng-show="{{sideBarItem.settings}}"><a href="#" class="dropdown-item">
                    <i class="fas fa-cogs"></i> 
                     Settings</a>
                  </li>
                </ul>
              </li>     
            </ul>
          </nav>
        </aside>

        <!-- Main Block -->
        <main role="main" class="main-block flex-grow-1" >
          <div class="row " >
            <div class="col-md m-2 p-2 " ng-controller="LineCtrl" >
                <canvas id="line" class="chart chart-line shadow w-100" chart-data="data" chart-labels="labels" chart-series="series" chart-options="options" chart-dataset-override="datasetOverride" chart-colors="colors" chart-click="onClick">
                </canvas> 
    
            </div>
            <div class="col-md m-2 p-2" ng-controller="mapCtrl">
                <div ng-hide="render" class="loading">
                  <i class="fas fa-spinner"></i> MAP LOADING ...
                </div>
                <div id="map" class="shadow justify-content-center" ng-if="render">

                 <world-map  world-data="worldData" value-range="valueRange" color-range="colorRange" dimension="dimension" map-width="mapWidth" descriptive-text="descriptiveText" country-fill-color="countryFillColor" country-border-color="countryBorderColor" country-data="countryData"></world-map>

                  <div class="btn-group-vertical" role="group" aria-label="..." id="float-button-group">
                  <button type="button" class="btn btn-default" id="zoom-in"><i class="fas fa-search-plus"></i></button>
                  <button type="button" class="btn btn-default" id="zoom-out"><i class="fas fa-search-minus"></i></button>
                  <button type="button" class="btn btn-default" id="reset">
                    <i class="fas fa-sync-alt"></i></button>
                  </div>
                    <input type="range" value="1" min="1" max="8" orient="vertical" id="map-zoomer"/>
                </div>
            </div>
          </div>
          <div class="row " >
            <div class="col-md m-2 p-2 " ng-controller="pieCtrl">
               <canvas id="pie" class="chart chart-pie shadow w-100"
                  chart-data="data" chart-labels="labels" chart-options="options" chart-colors="colorsPie">
               </canvas> 
    
            </div>
            <div class="col-md m-2 p-2" >
              <table class="table table-bordered table-dark">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">First</th>
                    <th scope="col">Last</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
  
                  </tr>
                  <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>

                  </tr>
                  <tr>
                    <th scope="row">3</th>
                    <td colspan="2">Larry the Bird</td>

                  </tr>
                </tbody>
              </table>
            </div>
          </div>

        </main>

    </div>

    <!-- Footer -->
    <footer class="main-footer p-2  shadow-lg ">
      <span class="footer-data ">
        Copyright &copy Mohammad Alsayyed. All rights reserved 2018.
      </span>
    </footer>



<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>



