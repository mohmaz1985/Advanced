<?php
use yii\helpers\Html;

?> 
<!-- Main Header -->
  <header class="main-header">
    <nav class="navbar fixed-top shadow-lg">
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
        <?php if(!Yii::$app->user->isGuest){ ?>
        <li class="nav-item pl-3" ng-click="openProfile()">
          <i class="fas fa-user-circle"></i> 
          <span class="show-text">Profile</span>
        </li>
        <li class="nav-item pl-3">
          <?= Html::a('<i class="fas fa-sign-out-alt"></i> <span class="show-text">Sign out</span>', ['site/logout'], [
              'data' => ['method' => 'post'],
              'class' => 'nav-item'
            ]) ?> 
        <?php } ?>
        </li>
      </ul>
    </nav>

  </header>