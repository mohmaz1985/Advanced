app.directive("digitalClock", function($timeout, dateFilter) {
  return {
    restrict: 'E',
    transclude: true,
     template: [
            '<table class="table mb-0">',
            '<tr>',
            '<td>Time:</td><td>{{timeValue}}</td>',
            '</tr>',
            '</table>'
            ].join(''),
    link: function($scope, $element) {
      (function updateClock() {
        $scope.dateAndTime = new Date();
        $scope.timeValue = dateFilter($scope.dateAndTime, 'H:mm:ss a');
        $timeout(updateClock, 1000);
      })();
    }
  };
});

app.directive("todayDate", function($timeout, dateFilter) {
  return {
    restrict: 'E',
    transclude: true,
    template: [
            '<table class="table ">',
            '<tr>',
            '<td>Date: </td><td> {{todayDate}}</td>',
            '</tr>',
            '<tr>',
            '<td>Day: </td><td> {{dayName}}</td>',
            '</tr>',
            '</table>'
            ].join(''),
    link: function($scope, $element) {
      (function updateDate() {
        $scope.dateAndTime = new Date();
        $scope.todayDate = dateFilter($scope.dateAndTime, 'y/MM/dd');
        $scope.dayName = dateFilter($scope.dateAndTime, 'EEEE');
        $timeout(updateDate, 1000);
      })();
    }
  };
});