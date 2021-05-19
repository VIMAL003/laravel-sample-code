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
                <h3 class="box-title">Attribute Lookup</h3>
            </div>

            <form action="{{url('admin/attribute-adddata')}}" method="POST">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{$attribute->id}}">

            <div class="col-md-12 p5">
                <div class="box box-success">
                    <div class="box-body">
                        <div class="col-md-6 p5">
                            <div class="form-group">
                                <label for="title">Attribute Key</label>
                                <input {{(!empty($attribute->id))? 'readonly' : 'required' }}  name="attributekey" value="{{$attribute->attributekey}}" type="text" class="form-control" id="attributekey" placeholder="Attribute Key">
                            </div>
                        </div>

                        <div class="col-md-6 p5">
                            <div class="form-group">
                                <label for="type">Attribute Name</label>
                                <input required name="attributename" value="{{$attribute->attributename}}" type="text" class="form-control" id="attributename" placeholder="Attribute Name">
                            </div>
                        </div>

                        <div class="col-md-12 p5">
                            <div class="form-group">
                                <label for="orgIds">Attribute Organization Ids</label>
                                <select class="form-control select2Cls" name="sync_org_owners[]" id="orgIds" multiple>
                                    @foreach($organizations as $id => $org)
                                        <option value="{{$id}}" {{in_array($id ,$attribute->sync_org_owners) ? 'selected' : ''}} >{{$org}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 p5">

                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea id="attributedescr" name="attributedescr" placeholder="Place some text here" rows="3" style="width: 100%; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{$attribute->attributedescr}}</textarea>
                            </div>

                            <div class="box-footer">
                                <a href="{{route('admin.attributes')}}" class="btn btn-default">Cancel</a>
                                <button type="submit" class="btn btn-success pull-right">Save</button>
                            </div>

                            
                        </div>

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
