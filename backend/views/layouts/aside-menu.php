<!-- sidebar -->
<aside class="sidebar-menu {{sideBarStatusClass}} flex-shrink-0"  ng-controller="dropdownCtrl" >
  <nav class="sidebar" >
    <ul class="nav flex-column" >
            
      <li class="nav-item">
        <div class="nav-link p-2">
        
          <i class="fas fa-tachometer-alt"></i>
          <span class=" p-2 show-text">
           <a href="<?=Yii::$app->homeUrl?>">
            Dashboard
           </a>
          </span>
        
        </div>
      </li>
      <li class="nav-item mb-1" uib-dropdown ng-repeat=" sideBarItem in sideBarMenuList">
        <div class="dropdown-toggle p-2" uib-dropdown-toggle ng-disabled="disabled">
         <i class="fas fa-{{sideBarItem.icon}}"></i>
          <span class="nav-link p-2 show-text">{{sideBarItem.title}}</span>
        </div>
        <ul class="dropdown-menu shadow" role="menu">
          <li ng-show="{{sideBarItem.list}}"><a href="{{sideBarItem.link}}" class="dropdown-item">
            <i class="fas fa-clipboard-list"></i>
            {{sideBarItem.title}} List
          </a></li>
          <li ng-show="{{sideBarItem.add}}"><a href="#" class="dropdown-item">
            <i class="fas fa-plus"></i>
            Add {{sideBarItem.title}}</a>
          </li>
          <li ng-show="{{sideBarItem.catigory}}"><a href="#" class="dropdown-item">
            <i class="fas fa-code-branch"></i>
            {{sideBarItem.title}} Categories</a>
          </li>
          <div ng-show="{{sideBarItem.settings}}" class="dropdown-divider"></div>
          <li ng-show="{{sideBarItem.settings}}"><a href="#" class="dropdown-item">
            <i class="fas fa-cogs"></i> 
             Settings</a>
          </li>
        </ul>
      </li>     
    </ul>
  </nav>
</aside>