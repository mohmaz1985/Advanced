// Line Chart 
app.controller("LineCtrl", function ($scope) {

  $scope.labels = ["January", "February", "March", "April", "May", "June", "July"];
  $scope.series = ['Series A', 'Series B'];
  $scope.data = [
    [65, 59, 80, 81, 56, 55, 40],
    [28, 48, 40, 19, 86, 27, 90]
  ];
  $scope.onClick = function (points, evt) {
    console.log(points, evt);
  };

  $scope.datasetOverride = [{ yAxisID: 'y-axis-1' }, { yAxisID: 'y-axis-2' }];
  $scope.options = {
    scales: {
      yAxes: [
        {
          id: 'y-axis-1',
          type: 'linear',
          display: true,
          position: 'left',
        },
        {
          id: 'y-axis-2',
          type: 'linear',
          display: true,
          position: 'right'
        }
      ]
    },
    maintainAspectRatio:false
    
  };
  $scope.colors =['#009688', '#212529', '#00635a'];
  /*$scope.colors = [
  {
    fillColor: "rgba(0,90,99,0.2)",
    strokeColor: "rgba(151,187,205,1)",
    pointColor: "rgba(151,187,205,1)",
      pointStrokeColor: "#fff",
      pointHighlightFill: "#fff",
      pointHighlightStroke: "rgba(0,187,205,0.8)"
  }];*/
  
});

// Pie Chart 
app.controller("pieCtrl", function ($scope) {
  $scope.labels = ["Download Sales", "In-Store Sales", "Mail-Order Sales"];
  $scope.data = [300, 500, 100];

  $scope.colorsPie = ['#009688', '#212529', '#00635a']; 
});