app.controller('dropdownCtrl',function($scope,$log){
 
 	$scope.sideBarMenuList = [
 		{
 			title : "About",
 			icon : "paperclip",
 			list: true,
 			add: true,
 			catigory: true,
 			settings: true,
 			link:"index.php?r=user/"
 		},
 		{
 			title : "Contact",
 			icon : "address-book",
 			list: true,
 			add: true,
 			catigory: false,
 			settings: false,
 			link:"index.php?r=user/"
 		},
 		{
 			title : "News",
 			icon : "newspaper",
 			list: true,
 			add: true,
 			catigory: false,
 			settings: false,
 			link:"index.php?r=user/"
 		},
 		{
 			title : "Users",
 			icon : "users",
 			list: true,
 			add: true,
 			catigory: false,
 			settings: true,
 			link:"index.php?r=user/"
 		}
 		
 	];
});
