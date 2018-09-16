app.controller('modalCtrl', function ($scope, $uibModal) {

  // Profile Page
  $scope.openProfile = function () {
  	
    $uibModal.open({
      templateUrl: '././profile.html',
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
      templateUrl: '././dateTime.html',
      controller: function ($scope, $uibModalInstance) { 
        $scope.close = function () {
          $uibModalInstance.dismiss('cancel');
        };
      }
    }).result.catch(function(error) {});
  };

});