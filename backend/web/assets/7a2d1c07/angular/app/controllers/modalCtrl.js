app.controller('modalCtrl', function ($scope, $uibModal) {

  // Profile Page
  $scope.openProfile = function () {
  	
    $uibModal.open({
      templateUrl: '../views/layouts/profile.php',
      controller: function ($scope, $uibModalInstance) {
        $scope.ok = function () {
          alert('Save Data ....');
          $uibModalInstance.close();
        };
      
        $scope.cancel = function () {
          $uibModalInstance.dismiss('cancel');
        };
      }
    }).result.catch(function(error) {});
  };

  // Date and Time
  $scope.dateTime = function () {
    
    $uibModal.open({
      templateUrl: '../views/layouts/dateTime.php',
      controller: function ($scope, $uibModalInstance) { 
        $scope.close = function () {
          $uibModalInstance.dismiss('cancel');
        };
      }
    }).result.catch(function(error) {});
  };

});