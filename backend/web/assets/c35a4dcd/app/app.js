'use strict';
/* Main app */
var app = angular.module('app',['chart.js','ngAnimate','ui.bootstrap','angular-map-it','angularMoment']);

// load json file
app.run(function($timeout,$http, $rootScope){
  $http.get('js/mapList.json').then(function(data, status, headers, config) {
    //alert('config'+ data.name);
    $rootScope.config = data;
    $rootScope.$broadcast('config-loaded');
  }).catch(function(data, status, headers, config) {
    // log error
    alert('Error ... ');
  });

  /* */
  $rootScope.isLoading = true; 
  $timeout(function() { // simulate long page loading
        $rootScope.isLoading = false; // turn "off" the flag
  }, 2500);
});

/*******************/



     
