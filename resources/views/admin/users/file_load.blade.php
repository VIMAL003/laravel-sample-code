@include('admin.include.admin_header')

@include('admin.include.admin_head_nav')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    @if(Session::has('success'))
                        <div class="alert alert-success">{{Session::get('success')}}</div>
                    @endif
                    @if(Session::has('error'))
                        <div class="alert alert-danger">{{Session::get('error')}}</div>
                    @endif

                    <div class="box-header">
                        <h2 class="box-title text-success">CSV Data</h2>
                    </div>

                    <div class="box-body table-responsive ">
                        <form id="importData" action="{{route('import-csv-excel')}}" method="post">
                            {!! csrf_field() !!}
                            <a href="{{url('admin/import-export-csv-excel')}}" class="btn btn-success pull-right"><i class="fa fa-arrow-left"></i> Go Back</a>
                            <input type="hidden" name="ignore_indexes" class="ignore_indexes" value="">
                        </form>

                        <table id="example4" class="table">
                            <thead>
                            <th>Ignore</th>
                            <th>Id</th>
                            <th>Org</th>
                            <th>Type</th>
                            <th>School Type</th>
                            <th>EIN</th>
                            <th>Nonprofit Org Type</th>
                            <th>F Name</th>
                            <th>L Name</th>
                            <th>Role</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>DOB</th>
                            <th>Gender</th>
                            <th>Zip</th>
                            <th>Location</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Country</th>
                            <th>Lat</th>
                            <th>Long</th>
                            <th>Contact</th>
                            </thead>
                            <tbody>
                            @foreach($dataArray->toArray() as  $index=>$row)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="check_all_id[]" checked class="select_all" value="{{$index}}">
                                    </td>
                                    @foreach($row as  $key => $value)
                                        <td>{{$value}}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <br/>
                        <button type="button" id="submitButton" class="btn btn-block btn-success">Load All Data Into System</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


@include('admin.include.admin_footer')

<script type="text/javascript">
    jQuery(document).ready(function($) {

        $('#submitButton').click(function(){
            var ids = [];

            $('input[name="check_all_id[]"]:not(:checked)').each(function() {
                ids.push(this.value);
            });

            $('.ignore_indexes').val(ids);
            $('#importData').submit();
        });

    });
</script>