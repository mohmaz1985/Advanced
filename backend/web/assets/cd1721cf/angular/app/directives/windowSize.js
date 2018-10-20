app.directive('windowSize',function($window){
	return{
		link: linkd,
		restrict: 'A'
	};

	function linkd($scope,$element, $attrs){

		var appWindow = angular.element($window);
		
    	appWindow.bind('resize', function () {
        	
        	if ($scope.width !== $window.innerWidth)
	        {
	            $scope.width = $window.innerWidth;
	            
	        }
        	
        	$scope.windowWidth = $window.innerWidth;
        	//alert($scope.windowWidth);
        	// Sidebar resize initial values
		    if($scope.windowWidth <= 768)
		    {
		    	$scope.sideBarStatusClass ="sidebar-close";
		    	$scope.sideBarStatus = 'close';
		    	$scope.showHide = false;
		    }
		    else {
		    	$scope.sideBarStatusClass ="sidebar-open";
		    	$scope.sideBarStatus = 'open';
		    	$scope.showHide = true;
		    }
        	$scope.$digest();
  
    	});

	}
});