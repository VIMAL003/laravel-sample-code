

     
        <!-- header logo: style can be found in header.less -->
<header class="header">
    <a href="{{route('admin-dashboard')}}" class="logo">
        <!-- Add the class icon to your logo image or logo icon to add the margining -->
        <img src="{{asset('img/small_logo_scc.png')}}" class="img img-responsive" alt="logo" style="height: 44px; width: 100px; margin: 0px auto">
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i>
                        <span>{{$data['loginuser']->username}} <i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header bg-light-blue">
                            <img src="{{url('/images/profile_img').'/'.$data['loginuser']->profile_img}}" class="img-circle" alt="User Image" />
                            <p>
                                {{ucfirst($data['loginuser']->first_name)." ". ucfirst($data['loginuser']->last_name)." - "}}
                                @if($data['loginuser']->role_id == 1)
                                    {{ 'Administrator' }}
                                @elseif($data['loginuser']->role_id == 2)
                                    {{ 'Accountant' }}
                                @elseif($data['loginuser']->role_id == 3)
                                    {{ 'Manager' }}
                                @endif
                            </p>
                        </li>
                        
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{route('admin.adminstaff.profile')}}" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{route('admin-logout')}}" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
