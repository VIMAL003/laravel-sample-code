@include('admin.include.admin_header')

@include('admin.include.admin_head_nav')
<!-- Left side column. contains the logo and sidebar -->
@include('admin.include.admin_side_nav')
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{asset('admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
    <section class="content">
        <div class="row">

            <div class="box-header with-border">
                <h3 class="box-title">Site Content</h3>
            </div>

            <form action="{{route('admin.sitecontent.update')}}" method="POST">
                <input type="hidden" name="id" value="{{$sitecontent->id}}">
                {{csrf_field()}}

            <div class="col-md-12 p5">
                <div class="box box-success">
                    <div class="box-body">
                        <div class="col-md-6 p5">
                            <div class="form-group">
                                <label for="title">{{$sitecontent->type == 'userRegistrationNotification' ? 'Email' : 'Title/Subject'}}</label>
                                <input required name="title" value="{{$sitecontent->title}}" type="text" class="form-control" id="Title" placeholder="Title">
                            </div>
                        </div>

                        <div class="col-md-6 p5">
                            <div class="form-group">
                                <label for="type">Type</label>
                                <input readonly required value="{{$sitecontent->type}}" type="text" class="form-control" id="Type" placeholder="Type">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 p5">
                <div class="box box-success">
                    <div class="box-body">
                       <div class="form-group">
                            <label for="">Contents</label>
                            <textarea name="html_body" id="myNicEditor" placeholder="Place some text here" style="width: 100%; height: 350px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{!! $sitecontent->html_body !!}</textarea>
                        </div>
                    </div>
                    <div class="box-footer">
						<a href="{{route('admin.sitecontent')}}" class="btn btn-default">Cancel</a>
                       <button type="submit" class="btn btn-success pull-right">Save</button>
                    </div>
                </div>
            </div>

            </form>

        </div>
    </section>
</div>
@include('admin.include.admin_footer')

<script src="{{asset('admin/plugins/nicEdit-editor/nicEdit-latest.js')}}"></script>

<script type="text/javascript">
    bkLib.onDomLoaded(function() {
        nicEditors.editors.push(
            new nicEditor().panelInstance(
                document.getElementById('myNicEditor')
            )
        );
    });
</script>
