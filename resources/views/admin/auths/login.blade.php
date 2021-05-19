<!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="UTF-8">
        <title>Surf City Cranes | Log in</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="{{asset('theme/admin-lte/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="{{asset('theme/admin-lte/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="{{asset('theme/admin-lte/css/AdminLTE.css')}}" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <style type="text/css" media="screen">
            .loginBckColor{ background: #2CB0F4 !important; }
        </style>
    </head>
    <body class="bg-black">

        <div class="form-box" id="login-box">
            @if(Session::has('success'))
                <div class="alert alert-success">{{Session::get('success')}}</div>
            @endif
            @if(Session::has('warning'))
                <div class="alert alert-danger">{{Session::get('warning')}}</div>
            @endif
            <div>
                <img src="{{asset('img/logo_scc.png')}}" class="img img-responsive" alt="logo" style="height: 110px; margin: 15px auto">
            </div>
            <div class="loginBckColor header">Sign In</div>

            <form action="{{ route('admin-login-post') }}" method="post">
                    {{ csrf_field() }}
                <div class="body bg-gray">
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" placeholder="User ID"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password"/>
                    </div>          
                   
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="loginBckColor btn bg-olive btn-block ">Sign me in</button>  
                    
                    <!-- <p><a href="#">I forgot my password</a></p>
                    
                    <a href="register.html" class="text-center">Register a new membership</a> -->
                </div>
            </form>

           
        </div>


        <!-- jQuery 2.0.2 -->
        <script src="{{asset('theme/admin-lte/js/jquery-3.3.1.js')}}"></script>
        <!-- Bootstrap -->
        <script src="{{asset('theme/admin-lte/js/bootstrap.min.js')}}" type="text/javascript"></script>        

    </body>
</html>