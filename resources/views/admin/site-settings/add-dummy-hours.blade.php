@include('admin.include.admin_header')

@include('admin.include.admin_head_nav')
@include('admin.include.admin_side_nav')

<link rel="stylesheet" type="text/css" href="{{asset('/css/multi-select.css')}}">

<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #3bb44a;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered
    {
        color: #3bb44a;
        line-height: 22px;
    }

    .time-selector .select2 {
        display: none !important;
    }

</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Add bulk hours to Voluntiers
            <small>Hours</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Dashboard</a></li>
            <li class="active">Add bulk hours to Voluntiers</li>
        </ol>

        <br>
    </section>

    <section class="content">
        <div class="row">
            <div class="col" style="padding: 10px">
                <div class="box box-success">

                    <div class="box-header with-border">
                        <h3 class="box-title">Quick Data Remove</h3>
                    </div>

                    <form role="form" method="post" action="{{route('clear.test.data.post')}}">
                        {{csrf_field()}}

                        <div class="box-body">
                            <div class="form-group">
                                <label for="">Select Volunteers</label>
                                <select  name="user_ids[]" class="form-control select2Cls" id="" multiple required>
                                    @foreach($voluntiers as $user)
                                        <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}} - {{$user->id}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Select Opportunity</label>
                                <select  name="opportunity_id" class="form-control select2Cls" id="" required>
                                    @foreach($opportunities as $opportunity)
                                        <option value="{{$opportunity->id}}">{{$opportunity->title}} - {{$opportunity->contact_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12 col-lg-8 time-selector">
                                        <label class="label-text">Select Time Block:</label>
                                        <select id="time_block" multiple='multiple' required name="time_block[]">
                                            <?php
                                            $start = "00:00";
                                            $end = "24:00";
                                            $tStart = strtotime($start);
                                            $tEnd = strtotime($end);
                                            $tNow = $tStart;

                                            while($tNow <= strtotime('-30 minutes',$tEnd)){
                                            $time = date("h:i A",$tNow).'-'.date("h:i A",strtotime('+30 minutes',$tNow));
                                            $val = date("H:i",$tNow)
                                            ?>
                                            <option value="{{$val}}">{{$time}}</option>
                                            <?php    $tNow = strtotime('+30 minutes',$tNow);
                                            }?>
                                        </select>
                                        <span id="hourError"></span>
                                    </div>


                                    <div class="col-12 col-lg-4">
                                        <br>
                                        <div class="form-group">
                                            <label class="label-text">Date:</label>
                                            <div class="wrapper_input fa-icons">
                                                <input name="date" required class="form-control datePickerAm" type="date"  data-date-end-date="0d">
                                                <span class="focus-border"></span>
                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                            </div>

                                            <label class="label-text" style="padding-top: 20px">Logged Minutes:</label>
                                            <p id="hours_mins" class="mt-15">
                                                (0 minutes)
                                            </p>

                                            <br>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="is_auto_approve" type="checkbox"> AutoApprove by the Organization
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>


                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-right">Add Bulk Hours</button>
                        </div>
                    </form>
                </div>

            </div>

        </div>

        <br>
    </section>
</div>

@include('admin.include.admin_footer')

