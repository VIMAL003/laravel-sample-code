

<!-- jQuery 2.0.2 -->
<script src="{{asset('theme/admin-lte/js/jquery.min.js')}}" type="text/javascript"></script>
<!-- Toastr -->
<script src="{{asset('theme/admin-lte/js/toastr.min.js')}}" type="text/javascript" ></script>
<!-- jQuery UI 1.10.3 -->
<script src="{{asset('theme/admin-lte/js/jquery-ui-1.10.3.min.js')}}" type="text/javascript"></script>
<!-- Bootstrap -->
<script src="{{asset('theme/admin-lte/js/bootstrap.min.js')}}" type="text/javascript"></script>
<!-- Select2 -->
<script src="{{asset('theme/admin-lte/select2/dist/js/select2.full.min.js')}}" type="text/javascript" ></script>
<!-- Sparkline -->
<script src="{{asset('theme/admin-lte/js/plugins/sparkline/jquery.sparkline.min.js')}}" type="text/javascript"></script>
<!-- jvectormap -->
<script src="{{asset('theme/admin-lte/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/admin-lte/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}" type="text/javascript"></script>
<!-- fullCalendar -->
<script src="{{asset('theme/admin-lte/js/plugins/fullcalendar/fullcalendar.min.js')}}" type="text/javascript"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('theme/admin-lte/js/plugins/jqueryKnob/jquery.knob.js')}}" type="text/javascript"></script>
<!-- daterangepicker -->
<script defer src="{{asset('theme/admin-lte/js/plugins/daterangepicker/daterangepicker.js')}}" type="text/javascript"></script>
<!-- date picker -->
<script src="{{asset('theme/admin-lte/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
<!-- time picker -->
<script defer src="{{asset('theme/admin-lte/js/bootstrap-timepicker.min.js')}}" type="text/javascript"></script>
<!-- Datetime picker -->
<script src="{{asset('theme/admin-lte/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<!-- Sweet alert -->
<script src="{{asset('theme/admin-lte/js/sweetalert.js')}}" type="text/javascript"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{asset('theme/admin-lte/js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}" type="text/javascript"></script>
<!-- iCheck -->
<script src="{{asset('theme/admin-lte/js/plugins/iCheck/icheck.min.js')}}" type="text/javascript"></script>

<!-- AdminLTE App -->
<script src="{{asset('theme/admin-lte/js/AdminLTE/app.js')}}" type="text/javascript"></script>      


<!-- AdminLTE for demo purposes -->
<script src="{{asset('theme/admin-lte/js/AdminLTE/demo.js')}}" type="text/javascript"></script>
<!-- <script src="{{asset('theme/admin-lte/js/AdminLTE/adminlte.min.js')}}" type="text/javascript"></script> -->

<!-- Jquery Validation -->
<script src="{{asset('theme/admin-lte/js/jquery-validation/js/jquery.validate.min.js')}}" type="text/javascript" ></script>

<script type="text/javascript">
  $(document).ready(function() {
    
    var start = moment().subtract(29, 'days');
    var end = moment();

    $('.select2').select2()

    $('#job_det_date').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy'
    });
    $('#site_date_of_issue').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy'
    });
    $('#prt_date').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy'
    });

    $('#arrive_site_time').datetimepicker({
      format:'d/m/Y H:i:s'
    });

    $('#depart_site_time').datetimepicker({
      format:'d/m/Y H:i:s'
    });

    $('#week_ending').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy'
    });

    $("#sel-date-range").datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy'
    });

    function cb(start, end) {
        $('#date-range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#date-range').daterangepicker({
      startDate: start,
      endDate: end,
      ranges: {
       'Today': [moment(), moment()],
       'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
       'This Month': [moment().startOf('month'), moment().endOf('month')],
       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
     }
   });

    /*$('#arrive_site_date').timepicker({
      showInputs: false
    })*/
  });
</script>

@yield('admin_js')