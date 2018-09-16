app.controller('sidebarCtrl',function($scope,$window){
	
    $scope.windowWidth = $window.innerWidth;
     // sidebar initial values
    if($scope.windowWidth <= 768)
    {
    	$scope.sideBarStatusClass ="sidebar-close";
    	$scope.sideBarStatus = 'close';
    	$scope.showHide = false;
    }else{
    	$scope.sideBarStatusClass ="sidebar-open";
    	$scope.sideBarStatus = 'open';
    	$scope.showHide = true;
    }

	$scope.sideBar = function(){
		
		//alert($window.innerWidth);
		$scope.showLg = !$scope.showLg;
		$scope.showSm = !$scope.showSm;
		
		if($scope.sideBarStatus == 'open' || $window.innerWidth <= 500){
			$scope.sideBarStatusClass ="sidebar-close";
			$scope.sideBarStatus = 'close';
			$scope.showHide = false;
		}else if($scope.sideBarStatus == 'close'){
			$scope.sideBarStatusClass ="sidebar-open";
			$scope.sideBarStatus = 'open';
			$scope.showHide = true;
		}
	}
});