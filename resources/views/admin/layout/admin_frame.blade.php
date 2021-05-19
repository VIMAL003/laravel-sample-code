<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <title> Surf City Cranes | Dashboard</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
         <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{asset('img/favicn.png')}}" type="image/gif" sizes="16x16">
        <!-- bootstrap 3.0.2 -->
        <link href="{{asset('theme/admin-lte/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="{{asset('theme/admin-lte/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="{{asset('theme/admin-lte/css/ionicons.min.css')}}" rel="stylesheet" type="text/css" />
        
        
        <!-- jvectormap -->
        <link href="{{asset('theme/admin-lte/css/jvectormap/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css" />
        <!-- fullCalendar -->
        <link href="{{asset('theme/admin-lte/css/fullcalendar/fullcalendar.css')}}" rel="stylesheet" type="text/css" />
        <!-- Daterange picker -->
        <link href="{{asset('theme/admin-lte/css/daterangepicker/daterangepicker-bs3.css')}}" rel="stylesheet" type="text/css" />
        <!-- Date picker -->
        <link href="{{asset('theme/admin-lte/css/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Time picker -->
        <link href="{{asset('theme/admin-lte/css/bootstrap-timepicker.min.css')}}" rel="stylesheet" type="text/css" />

        <!-- DateTime picker -->
        <link href="{{asset('theme/admin-lte/css/jquery.datetimepicker.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="{{asset('theme/admin-lte/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Custom style -->
        <link href="{{asset('theme/admin-lte/css/custom.css')}}" rel="stylesheet" type="text/css" />        
        <!-- Toastr -->
        <link href="{{asset('theme/admin-lte/css/toastr.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Select2 -->
        <link href="{{asset('theme/admin-lte/select2/dist/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="{{asset('theme/admin-lte/css/AdminLTE.css')}}" rel="stylesheet" type="text/css" />
        <!-- <link href="{{asset('theme/admin-lte/css/AdminLTE.min.css')}}" rel="stylesheet" type="text/css" /> -->
        

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <style type="text/css" media="screen">
          .no-print {display: none;}
          .skin-blue .logo,  .skin-blue .logo:hover {background-color:#fff;}
          .alert{ margin: 0px !important; }
        </style>
        @yield('admin_css')
    </head>
    @php
$data['loginuser'] = Session::get('loginuser');
@endphp
    @include('admin.include.admin_header')
    <body class="skin-blue">

         <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
             @include('admin.include.admin_side_nav')
             
          @yield('admin_content')
         </div>
         @include('admin.include.admin_footer')
    </body>
    @yield('more_js')

</html>
