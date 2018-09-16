app.controller('mapCtrl', ['$rootScope','$scope', function($rootScope,$scope,$moment) {

$scope.render=false;

$scope.$on('config-loaded', function(){
    //$rootScope.config.data
    $scope.render=true; 
    $scope.countryData = $rootScope.config.data; 
    $scope.valueRange = [0,100];
    $scope.colorRange = ["#F03B20", "#FFEDA0"];
    $scope.dimension = 400;
    $scope.mapWidth = 400;
    $scope.descriptiveText = 'failure %';
    $scope.countryFillColor = "#222d32";
    $scope.countryBorderColor = "#ffffff";
    $scope.worldData = [
        /*{
          "countryCode": "AFG",
          "value": 10
        },
        {
          "countryCode": "USA",
          "value": 99
        },
        {
          "countryCode": "CAN",
          "value": 50
        },
        {
          "countryCode": "ISR",
          "value": 2
        },
        {
          "countryCode": "NLD",
          "value": 30
        },
        {
          "countryCode": "JOR",
          "value": 1
        }*/
      ];
  });
    
    
    
}]);