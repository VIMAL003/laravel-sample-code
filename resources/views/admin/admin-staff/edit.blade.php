@extends('admin.layout.admin_master')


@section('admin_content')


<aside class="right-side">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Administrator Staff
      <small>{{ ucfirst(explode('@',Route::currentRouteAction())[1]) }}</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Administrator Staff</a></li>
      <li class="active">{{ ucfirst(explode('@',Route::currentRouteAction())[1]) }}</li>
    </ol>
  </section>
  @if(Session::has('message'))
  <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissable">
    <i class="fa fa-check"></i>
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <b>Alert!</b> {{ Session::get('message') }}.
  </div>
  @endif
  <!-- Main content -->
  <section class="content">
   <form role="form" method="post" action="{{route('admin.adminstaff.save')}}" enctype="multipart/form-data">
     {{csrf_field()}}
     <input type="hidden" name="id" value="{{(!empty($data->id))? $data->id : ''}}" />
     <input type="hidden" name="actionname" value="{{explode('@',Route::currentRouteAction())[1]}}" />
     <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->

        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">{{ ucfirst(explode('@',Route::currentRouteAction())[1]) }} Staff</h3>
          </div><!-- /.box-header -->
          <!-- form start -->

          <div class="box-body col-md-6">
            <div class="form-group">
              <label for="exampleInputEmail1">First Name <span class="text-red">*</span></label>
              <input type="text" class="@error('first_name') is-invalid @enderror form-control" id="exampleInputEmail1" placeholder="Enter First Name" name="first_name" value="{{(!empty($data->first_name))? $data->first_name : old('first_name') }}">
              @error('first_name')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Last Name <span class="text-red">*</span></label>
              <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Last Name" name="last_name"  value="{{(!empty($data->last_name))? $data->last_name : old('last_name') }}">
              @error('last_name')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Username <span class="text-red">*</span></label>
              <input type="text" class="form-control" name="username" id="exampleInputEmail1" placeholder="Enter Username"  value="{{(!empty($data->username))? $data->username : old('username') }}" {{(!empty($data->id))? 'disabled' : ''}}>
              @error('username')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror
            </div>
            @if(explode('@',Route::currentRouteAction())[1] != 'profile')
            <div class="form-group">
              <label for="role">Role <span class="text-red">*</span></label>
              <select class="form-control" name="role_id" >
               <option value="">Select</option>
               <option value="2"
               {{ (!empty($data->role_id) && $data->role_id == '2')? 'selected' : '' }}
               {{ (old('role_id') == '2')? 'selected' : '' }}  >
               Accountants
             </option>
             <option value="3" {{ (!empty($data->role_id) && $data->role_id == '3')? 'selected' : '' }}
              {{ (old('role_id') == '3')? 'selected' : '' }}

              >Manager</option>
            </select>
            @error('role_id')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
          </div>
          @endif

        </div><!-- /.box-body -->
        <div class="box-body col-md-6">
          <div class="form-group">
            <label for="exampleInputEmail1">Email <span class="text-red">*</span></label>
            <input type="email" class="form-control" name="email" id="exampleInputEmail1" placeholder="Enter email" value="{{(!empty($data->email))? $data->email : old('email') }}">
            @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-group">
            <label for="phoneNumber">Phone Number</label>
            <input type="text" class="form-control" name="phone" id="" placeholder="Phone Number" value="{{(!empty($data->phone))? $data->phone : old('phone') }}">
            @error('phone')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
          </div>

          
          <div class="form-group">
            <label for="password">{{ (explode('@',Route::currentRouteAction())[1] == 'profile')? 'New' : '' }} Password</label>
            <input type="password" class="form-control" name="password" id="" placeholder="Password" >
            @error('password')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" >
            @error('password_confirmation')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="form-group">
            <label for="exampleInputFile">Profile Pic</label>
            <input type="file" id="profile_img" name="profile_img" accept="image/*">
            @error('profile_img')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @if(!empty($data->profile_img))
            <img src="{{request()->getSchemeAndHttpHost().'/images/profile_img/'.$data->profile_img}}" class="img-circle pull-left image" alt="User Image" style="width: 20%;margin-top: 12px;">
            @endif
          </div>

        </div><!-- /.box-body -->
        <div style="clear:both"></div>
        <div class="box-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
          @if(explode('@',Route::currentRouteAction())[1] != 'profile')
          <a href="{{route('admin-stafflist')}}" style="margin-left: 3%;"><button type="button" class="btn">Cancel</button></a>
          @endif
        </div>
      </div><!-- /.box -->

    </div>

  </div>   <!-- /.row -->
</form>
</section><!-- /.content -->
</aside>

@endsection