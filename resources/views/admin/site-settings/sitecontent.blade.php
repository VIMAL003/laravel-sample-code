@include('admin.include.admin_header')

@include('admin.include.admin_head_nav')
<!-- Left side column. contains the logo and sidebar -->
@include('admin.include.admin_side_nav')

<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{asset('admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Site Contents<small>Settings</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Site Contents</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Site Contents</h3>
                    </div>


                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>ID</th>
                                <th>Title/Subject</th>
                                <th>Type</th>
                                 <th>Action</th>
                            </tr>
                            @foreach($siteContents as $content)
                            <tr>
                                <td>{{$content->id}}</td>
                                <td>{{$content->title}}</td>
                                <td><span class="label label-success">{{$content->type}}</span></td>
                                <td><a href="{{route('admin.sitecontent.edit' ,$content->id)}}" ><i class="fa fa-pencil"></i></a></td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@include('admin.include.admin_footer')
