@include('admin.include.admin_header')

@include('admin.include.admin_head_nav')
<!-- Left side column. contains the logo and sidebar -->
@include('admin.include.admin_side_nav')

<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{asset('admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Attribute Lookups
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Attribute Lookup</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title text-success">Attributes</h3>
                        <a class="btn btn-success" style="float: right" href="{{url('admin/attribute-add')}}">Add Attribute Lookup</a>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>ID</th>
                                <th>Key</th>
                                <th>Name</th>
                                 <th>Action</th>
                            </tr>
                            @if(!empty($attributes))
                                @foreach($attributes as $content)
                                <tr>
                                    <td>{{$content['id']}}</td>
                                    <td><span class="label label-success">{{$content['attributekey']}}</span></td>
                                    <td>{{$content['attributename']}}</td>
                                    <td><a href="{{route('admin.attribute.edit' ,$content['id'])}}" ><i class="fa fa-pencil"></i></a></td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" style="text-align:center;"> No record found!!!</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@include('admin.include.admin_footer')
