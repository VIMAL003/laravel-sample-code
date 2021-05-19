@include('admin.include.admin_header')

@include('admin.include.admin_head_nav')
<!-- Left side column. contains the logo and sidebar -->
{{--@include('admin.include.admin_side_nav')--}}
<link href="{{ asset('admin/plugins/dropify/dist/css/dropify.min.css') }}" rel="stylesheet" type="text/css"/>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">

                    <div class="tips-wrapper" style="width: 95%">
                        <h3 class="text-success">Instructions</h3>
                        <p>Please format your data in CSV format with the following columns</p>

                        <p>Column 1: Example Field A</p>
                        <p>Column 2: Example Field B</p>
                        <p>Column 3: Example Field C</p>
                        <br/>

                        {{--<p>Upload a list of new users to automatically create new account for them.</p>--}}
                        <p>Download the example file to check the data format.</p>
                        <a href="{{ URL::to('/') }}/example_data.xls" class="text-success"><h3>Download Example
                                File</h3></a>

                    </div>

                    <section class="content">
                        {!! Form::open(array('url' => '/admin/load-csv-excel', 'method'=>'post', 'enctype'=>'multipart/form-data')) !!}
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="form-group">
                                    <label class="label-text">Import data from CSV file</label>
                                    <div class="wrapper_input">
                                        <input name="sample_file" type="file"  class="dropify" data-height="80" data-allowed-file-extensions="xls xlsx"/>
                                    </div>
                                    <div class="text-error"></div>
                                </div>

                                <p>OR</p>

                                <div class="form-group">
                                    <label class="label-text">Copy/Paste CSV Formatted Date Below</label>
                                    <div class="wrapper_input">
                                        <textarea class="form-control" rows="10" name="data">id,org_name,org_type,school_type,ein,nonprofit_org_type,first_name,last_name,user_role,user_name,email,password,birth_date,gender,zipcode,location,city,state,country,lat,lng,contact_number
1,Test Organization 11,1,2,NULL,NULL,NULL,NULL,organization,TestOrg 11,superperson1191@outlook.com,admin#123,02/02/2010,NULL,12534,"S 7th St, Hudson, NY, US",Hudson,NY,US,42.2259955,-73.77132,5122345832</textarea>
                                    </div>
                                    <div class="text-error"></div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Click To Import Data</button>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </section>
                </div>
            </div>
        </div>
    </section>

</div>

@include('admin.include.admin_footer')
<script type="text/javascript" src="{{ asset('admin/plugins/dropify/dist/js/dropify.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.dropify').dropify();
    });
</script>