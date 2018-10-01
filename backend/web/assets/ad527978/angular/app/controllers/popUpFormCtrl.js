app.controller('popUpFormCtrl', function ($scope, $uibModal) {

  // Profile Page
  $scope.openForm = function () {
  	
    $uibModal.open({
      templateUrl: '../views/user/create.php',
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

});