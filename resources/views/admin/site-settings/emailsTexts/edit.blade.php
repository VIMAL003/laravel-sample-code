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
                <h3 class="box-title">Email/Text Template Edit</h3>
            </div>

            <form action="{{route('admin.email.update')}}" method="POST">
                <input type="hidden" name="id" value="{{$email->id}}">
                {{csrf_field()}}

            <div class="col-md-6 p5">
                <div class="box box-success">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="type">Type</label>
                            <input readonly required name="mail_type" value="{{$email->type}}" type="text" class="form-control" id="Type" placeholder="Type">
                        </div>

                        <div class="form-group">
                            <label for="title">Title/Subject</label>
                            <input required name="title" value="{{$email->title}}" type="text" class="form-control" id="Title" placeholder="Title">
                        </div>
                        <br/>
                    </div>
                </div>
            </div>

            <div class="col-md-6 p5">
                <div class="box box-success">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="">Text Template</label>
                            <textarea name="text_body" placeholder="SMS body here" style="width: 100%; height: 101px; border: 1px solid #dddddd; padding: 5px;">{{$email->text_body}}</textarea>
                            <span class="w-100"></span>
                            <small id="KeywordsHelpBlock" class=" text-muted">
                                <i class="fa fa-question-circle"></i>
                                Please do not use "{link}" keyword here it will not replace
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 p5">
                <div class="box box-success">
                    <div class="box-body">
                        <div class="form-group mt-2">
                            <label for="type">Table Objects (userTbl, orgTbl, opprTbl)</label>
                            <input readonly name="table_obj" value="{{$email->table_obj}}" type="text" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Email Template</label>
                            <textarea name="mail_body" id="myNicEditor" placeholder="Place some text here" style="width: 100%; height: 350px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{!! $email->body !!}</textarea>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="{{route('admin.email.index')}}" class="btn btn-default">Cancel</a>
                        <button type="submit" class="btn btn-success pull-right">Save Email & Text</button>
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
