<?php
  $this->title = Yii::$app->generalComp->userInformation();
?>
<!-- Home Page-->
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
