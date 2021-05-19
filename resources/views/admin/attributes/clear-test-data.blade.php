@include('admin.include.admin_header')

@include('admin.include.admin_head_nav')
@include('admin.include.admin_side_nav')
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #dd4b39;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Clear Test Data
            <small>Setting</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Dashboard</a></li>
            <li class="active">Clear Test Data</li>
        </ol>
        <br>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-ban"></i> Alert..!</h4>
            Please don't do anything on this page without permissions of SUPER ADMIN. Its only used to delete some test data with all related records i.e opportunity,chat,hours etc.
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Quick Data Remove</h3>
                    </div>

                    <form role="form" method="post" action="{{route('clear.test.data.update')}}">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group">
                                <label for="clearUsersSelect">Select Users</label>
                                <select  name="user_ids[]" class="form-control" id="clearUsersSelect" multiple>
                                    @foreach($stats['users'] as $user)
                                        <option value="{{$user->id}}">{{$user->id}} - {{$user->user_role}} -{{$user->user_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="text-center">
                                <label>------OR------</label>
                            </div>

                            <div class="form-group">
                                <label>User Ids</label>
                                <textarea name="user_ids_comma" class="form-control" rows="3" placeholder="Enter comma separated user ids"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Password </label>
                                <input name="password" value="web123456" class="form-control" placeholder="Confirm you are admin">
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input required type="checkbox"> Please confirm to run this
                                </label>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-right">Run Command</button>
                        </div>
                    </form>
                </div>

            </div>

            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Some Stats</h3>
                    </div>

                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Type</th>
                                <th>Deleted</th>
                                <th style="width: 40px">Total</th>
                            </tr>
                            <tr>
                                <td>1.</td>
                                <td>Total User</td>
                                <td>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-danger" style="width: 99%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-green">{{$stats['totalUser']}}</span></td>
                            </tr>

                            <tr>
                                <td>2.</td>
                                <td>Total Opportunity</td>
                                <td>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-danger" style="width: 99%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-green">{{$stats['totalOppr']}}</span></td>
                            </tr>

                            <tr>
                                <td>3.</td>
                                <td>Total Groups</td>
                                <td>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-danger" style="width: 99%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-green">{{$stats['totalGrps']}}</span></td>
                            </tr>

                            <tr>
                                <td>4.</td>
                                <td>Total Alerts</td>
                                <td>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-danger" style="width: 99%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-green">{{$stats['totalAlerts']}}</span></td>
                            </tr>

                            <tr>
                                <td>5.</td>
                                <td>Total Tracking</td>
                                <td>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-danger" style="width: 99%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-green">{{$stats['totalTracking']}}</span></td>
                            </tr>

                            <tr>
                                <td>6.</td>
                                <td>Total Oppr. Members</td>
                                <td>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-danger" style="width: 99%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-green">{{$stats['totalOpperMembers']}}</span></td>
                            </tr>

                            <tr>
                                <td>7.</td>
                                <td>Total NewsFeed</td>
                                <td>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-danger" style="width: 99%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-green">{{$stats['totalNewsFeed']}}</span></td>
                            </tr>

                            <tr>
                                <td>8.</td>
                                <td>Total Friends</td>
                                <td>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-danger" style="width: 99%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-green">{{$stats['totalFriends']}}</span></td>
                            </tr>

                            <tr>
                                <td>9.</td>
                                <td>Total Chats</td>
                                <td>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-danger" style="width: 99%"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-green">{{$stats['totalChat']}}</span></td>
                            </tr>


                        </table>
                    </div>
                </div>
            </div>
        </div>

        <br>
        @if($isProceeded)
            <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Results</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>Successfully Deleted Users <span class="label label-success">{{count($response['proceededIds'])}}</span></th>
                                <th>Not Deleted Users <span class="label label-danger">{{count($response['errorsIds'])}}</span></th>
                            </tr>
                            <tr>
                                <td>{{implode(',' ,$response['proceededIds'])}}</td>
                                <td>{{implode(',' ,$response['errorsIds'])}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </section>
</div>

@include('admin.include.admin_footer')
