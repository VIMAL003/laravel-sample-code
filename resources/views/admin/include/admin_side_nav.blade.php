 <aside class="left-side sidebar-offcanvas">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="{{url('/images/profile_img').'/'.$data['loginuser']->profile_img}}" class="img-circle" alt="User Image" />
      </div>
      <div class="pull-left info">
        <p>Hello, {{$data['loginuser']->username}}</p>

        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>

    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
      <li class="active">
        <a href="{{route('admin-dashboard')}}">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>
      @if($data['loginuser']->role_id == 1)
      <li class="treeview active">
        <a href="#">
          <i class="fa fa-user"></i>
          <span>User Management</span>
          <i class="fa pull-right fa-angle-left"></i>
        </a>
        <ul class="treeview-menu" style="display: none;">
          <li>
            <a href="{{route('admin-stafflist')}}">
              <i class="fa fa-angle-double-right"></i> <span>Administration Staff</span> 
            </a>
          </li>
          <li>
            <a href="{{route('staff-list')}}">
              <i class="fa fa-angle-double-right"></i> <span>Field Staff</span> 
            </a>
          </li>

        </ul>
      </li>

      <li class="treeview active">
        <a href="#">
          <i class="fa fa-gear"></i>
          <span>Configuration</span>
          <i class="fa pull-right fa-angle-left"></i>
        </a>
        <ul class="treeview-menu" style="display: none;">
          <li>
            <a href="{{route('role-list')}}">
              <i class="fa fa-angle-double-right"></i> <span>Role Management</span> 
            </a>
          </li>
        </ul>
      </li>
      
      <li>
        <a href="{{route('job-list')}}">
          <i class="fa fa-tasks"></i> <span>Jobs</span> 
        </a>
      </li>
      <!--li>
        <a href="#">
          <i class="fa fa-th"></i> <span>Activity</span> 
        </a>
      </li>
      <li>
        <a href="#">
          <i class="fa fa-tasks"></i> <span>Timesheet</span> 
        </a>
      </li-->
      <li>
        <a href="{{route('docket-list')}}">
          <i class="fa fa-folder"></i> <span>Dockets</span> 
        </a>
      </li>
      @endif
      @if($data['loginuser']->role_id == 3 || $data['loginuser']->role_id == 2|| $data['loginuser']->role_id == 1)
      <li>
        <a href="{{route('crane-check')}}">
          <i class="fa fa-truck"></i> <span>Crane Check</span> 
        </a>
      </li>
      <li>
        <a href="{{route('crane-fuel-record')}}">
          <i class="fa fa-road"></i> <span>Crane Fuel Record</span> 
        </a>
      </li>
      <li>
        <a href="{{ route('activity-log') }}">
          <i class="fa fa-tasks"></i> <span>Activity Log</span> 
        </a>
      </li>
      @endif

      <li>
        <a href="{{route('admin-logout')}}">
          <i class="fa fa-eject"></i> <span>Logout</span> 
        </a>
      </li>

    </ul>
  </section>
  <!-- /.sidebar -->
</aside>