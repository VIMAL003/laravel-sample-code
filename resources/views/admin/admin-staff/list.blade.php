@extends('admin.layout.admin_master')

@include('admin.include.datatablejs')

@section('admin_content')
	<aside class="right-side">                
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Administrator Staff List
                        <small>listing of staffs</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Staff List</li>
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
                    <div class="row">
                        <div class="col-xs-12">
                             <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Staff List</h3>                                    
                                </div><!-- /.box-header -->
                                <div class="box-footer">
                                    <a href="{{route('admin.adminstaff.add')}}" class="btn btn-primary">Add Admin Staff</a>
                                </div>
                                <div class="box-body table-responsive">
                                    <table id="adminStaffList" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                              <th>Username</th>
                                              <th>First Name</th>
                                              <th>Last Name</th>
                                              <th>Role</th>
                                              <th>Email</th>
                                              <th>Telephone</th>
                                              <th>Action</th>
                                            </tr>
                                        </thead>
                                        
                                    </table>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        
                        </div>
                    </div>

                </section><!-- /.content -->
            </aside>
@endsection