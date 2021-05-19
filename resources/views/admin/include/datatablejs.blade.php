@section('admin_css')

<link href="{{asset('theme/admin-lte/css/datatables/dataTables.bootstrap-old.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('theme/admin-lte/css/datatables/buttons.dataTables.min.css')}}" rel="stylesheet" type="text/css" />
<style type="text/css" media="screen">
  .dataTables_processing {
    position: absolute;
    margin-left: 35%;
    margin-top: 6%;
  }
  
</style>

@endsection

@section('admin_js')
<script src="{{asset('theme/admin-lte/js/plugins/datatables/jquery.dataTables.min.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/admin-lte/js/plugins/datatables/dataTables.bootstrap-old.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/admin-lte/js/plugins/datatables/buttons.html5.min.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/admin-lte/js/plugins/datatables/buttons.print.min.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/admin-lte/js/plugins/datatables/dataTables.buttons.min.js')}}" type="text/javascript"></script>

<script type="text/javascript">
  $(function() {
    $("#example1").dataTable();
     /* $('#example2').dataTable({
          "bPaginate": true,
          "bLengthChange": false,
          "bFilter": false,
          "bSort": true,
          "bInfo": true,
          "bAutoWidth": false
        });*/
        
      });
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  var table = $('#staffList').DataTable({
    processing: true,
    serverSide: true,
    language: 
    {          
      processing: "<img src='{{asset('theme/admin-lte/img/ajax-loader1.gif')}}' />",
    },
    ajax: "{{ route('ajaxstaff.index') }}",
    columns: [
    { data: 'staff_no' , name: 'staff_no'},
    { data: 'first_name' , name: 'first_name'},
    { data: 'last_name' , name: 'last_name'},
    { data: 'email' , name: 'email'},
    { data: 'rfid_nfc' , name: 'rfid_nfc'},
    { data: 'telephone', name: 'telephone' },
    {data: 'action', name: 'action', orderable: false, searchable: false},
    ]
  });
  var table = $('#fuelList').DataTable({
    processing: true,
    serverSide: true,
    language: 
    {          
      processing: "<img src='{{asset('theme/admin-lte/img/ajax-loader1.gif')}}' />",
    },
    ajax: "{{ route('ajaxfuel.index') }}",
    columns: [
    { data: 'crane_id' , name: 'crane_id'},
    { data: 'make_and_model', name: 'make_and_model'},
    { data: 'fuel_type' , name: 'fuel_type'},
    { data: 'odometer' , name: 'odometer'},
    { data: 'quantity' , name: 'quantity'},
    { data: 'created_at' , name: 'created_at'},
    { data: 'staff' , name: 'staff'}

    ]
  });
  var table = $('#roleList').DataTable({
    processing: true,
    serverSide: true,
    language: 
    {          
      processing: "<img src='{{asset('theme/admin-lte/img/ajax-loader1.gif')}}' />",
    },
    ajax: "{{ route('ajaxRole.index') }}",
    columns: [
    { data: 'title' , name: 'title'},
    { data: 'type', name: 'type'},
    { data: 'notes' , name: 'notes'},
    { data: 'status' , name: 'status', orderable: false, searchable: false},
    {data: 'action', name: 'action', orderable: false, searchable: false},
    ],
    "columnDefs": [
    {"className": "text-center", "targets": [1,3,4]}
    ],

  });
  var adminStafftable = $('#adminStaffList').DataTable({
    processing: true,
    serverSide: true,
    language: 
    {          
      processing: "<img src='{{asset('theme/admin-lte/img/ajax-loader1.gif')}}' />",
    },
    ajax: "{{ route('ajaxAdminStaff.index') }}",
    columns: [
    { data: 'username' , name: 'username'},
    { data: 'first_name' , name: 'first_name'},
    { data: 'last_name' , name: 'last_name'},
    { data: 'role' , name: 'role'},
    { data: 'email' , name: 'email'},
    { data: 'phone', name: 'phone' },
    {data: 'action', name: 'action', orderable: false, searchable: false},
    ]
  });
  var jobtable = $('#jobList').DataTable({
    processing: true,
    serverSide: true,
    language: 
    {          
      processing: "<img src='{{asset('theme/admin-lte/img/ajax-loader1.gif')}}' />",
    },
    ajax: "{{ route('ajaxjob.index') }}",
    columns: [
    { data: 'job_no' , name: 'job_no'},
    { data: 'staff_no1' , name: 'staff_no1'},
    { data: 'driver_first_name' , name: 'driver_first_name'},
    { data: 'driver_name' , name: 'driver_name'},
    { data: 'email' , name: 'email'},
    { data: 'customer_name' , name: 'customer_name'},
    { data: 'crane', name: 'crane' },
    { data: 'licence_no', name: 'licence_no' },
    {data: 'action', name: 'action', orderable: false, searchable: false},
    ]
  });

  

  
  /*$('document').ready(function(){
       
      $('#staffList').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            
            'ajax': {
                'url':"{{ route('get-stafflist')}}",
            },
            'columns': [
               { data: 'staff_no' },
               { data: 'first_name' },
               { data: 'last_name' },
               { data: 'email' },
               { data: 'telephone' }
            ]
      });
    });*/
  </script>

  @endsection

