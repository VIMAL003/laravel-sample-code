@extends('admin.layout.admin_master')

@include('admin.include.datatablejs')

@section('admin_content')
  <aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Activity Log List
        <small>listing of Activity Log</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Activity Log</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
         <div class="box">
          <div class="box-header">
            <h3 class="box-title">Activity Log</h3>                                    
          </div>
          <div class="box-body table-responsive">
            <div class="row">
              <form name="frmFilter" id="frmFilter">
                @csrf
                
                <div class="col-md-4">
                  <input placeholder="Select date range" type="text" class="form-control pull-right" name="date-range" id="date-range">
                </div>
                
                <div class="col-md-3" >
                  <div class="form-group" >
                    <div id="select-staffs-section" >
                      <select class="form-control select2 m-b-30" name="selStaffId" id="selStaffId" >
                        <option selected disabled value="0">-- Select Staff --</option>
                        @foreach( $data['staff'] as $key => $value )
                        <option value="{{ $value['rfid_nfc'] }}">{{ $value['first_name'] }}&nbsp;{{ $value['last_name'] }} - {{ $value['rfid_nfc'] }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-md-3" >
                  <div class="form-group" >
                    <div id="select-job-section" >
                      <select class="form-control select2 m-b-30" name="selActivityCode" id="selActivityCode" >
                        <option selected disabled value="0">-- Select Activity --</option>
                        <option value="210">210</option>
                        <option value="211">211</option>
                        <option value="214">214</option>
                        <option value="215">215</option>
                        <option value="216">216</option>
                        <option value="217">217</option>
                        <option value="218">218</option>
                        <option value="219">219</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-md-2">
                  <button type="submit" name="btnFilter" id="btnFilter" class="btn btn-primary">Submit</button>
                  <button type="button" name="reset" id="reset" class="btn btn-danger">Reset</button>
                </div>

              </form>
            </div>
            <table id="logList" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Code</th>
                  <th>Title</th>
                  <th>Description</th>
                  <th>Start Time</th>
                  <th>End Time</th>
                  <th>Total Hours</th>
                  <th>Total Duration</th>
                  <th>Staff</th>
                  <th>Job Id</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <td colspan="5"><strong>Sum Total</strong></td>
                  <td></td>
                  <td colspan="4"></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</aside>
@endsection

@section('more_js')
<script type="text/javascript">

  function msToTime(s) {
    // Pad to 2 or 3 digits, default is 2
    function pad(n, z) {
      z = z || 2;
      return ('00' + n).slice(-z);
    }

    var ms = s % 1000;
    s = (s - ms) / 1000;
    var secs = s % 60;
    s = (s - secs) / 60;
    var mins = s % 60;
    var hrs = (s - mins) / 60;

    return pad(hrs) + ':' + pad(mins) + ':' + pad(secs);
  }
  
  $( document ).ready( function() {

    var dateRange = $('#date-range').val();
    var selStaffId = $('#selStaffId').val();
    var selActivityCode = $('#selActivityCode').val();

    var table = $('#logList').DataTable({
      dom: 'Bfrtlp',
      buttons: ['csv','excel'],
      'pageLength' : 10,
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
      'lengthMenu'  : [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "ajax": {
        url : "{{ route('ajaxlog') }}",
        dataType: "json",
        type : 'GET',
      },
      "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;

        // Remove the formatting to get integer data for summation
        var intVal = function ( i ) {
          return typeof i === 'string' ?
          i.replace(/[\$,]/g, '')*1 :
          typeof i === 'number' ?
          i : 0;
        };

        var sum_hours_estimated = api
          .column( 6 ) //hidden column
          .data()
          .reduce( function (a, b) {
              return intVal(a) + intVal(b);
        }, 0 );

        //convert seconds to hour:minute:seconds
        var secondsToHms = function(d) {
          d = Number(d);
          var h = ( "0" + Math.floor(d / 3600) ).slice(-2);
          var m = ( "0" + Math.floor(d % 3600 / 60) ).slice(-2);
          var s = ( "0" + Math.floor( d % 3600 % 60 ) ).slice(-2);

          return h + ":" + m + ":" + s;
        };

        //covert the seconds into hour.minute
        var hours_estimated = '<strong>'+secondsToHms(sum_hours_estimated)+'</strong>';

        $( api.column(5).footer()).html(hours_estimated);
      },
      "columnDefs": [
        {
            "targets": [ 6 ],
            "visible": false,
            "searchable": false
        }
    ]
    });

    $("#frmFilter").submit( function(e) {
      e.preventDefault();

       $.ajax({
        type:"POST",
        url: "{{ route('activity-log-filter') }}",
        data: new FormData(this),
        dataType: 'JSON',
        cache: false,
        contentType: false,
        processData: false,
        success: function(response)
        {
          console.log(response);
          var data = response.data;
          table.clear().draw();
          table.rows.add(data); // Add new data
          table.columns.adjust().draw();
          // $('#logList').DataTable().ajax.reload();
        }
      });
    });

    $("#reset").click( function(e) {
      e.preventDefault();

      $('#date-range').val("");
      $('#selStaffId').val(0);
      $('#selStaffId').select2().trigger('change');

      $('#selActivityCode').val(0);
      $('#selActivityCode').select2().trigger('change');

      $('#logList').DataTable().ajax.reload();
    });
  });
</script>
@endsection