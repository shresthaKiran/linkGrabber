<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title')</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{asset('css/sb-admin.css')}}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{asset('css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

</head>

<body>

<div id="wrapper">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
    @include('includes.navbar')
    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    @include('includes.sidebar')
    <!-- /.navbar-collapse -->
    </nav>
    <div class="page-wrapper">
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="{{asset('js/jquery.js')}}"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{asset('js/bootstrap.min.js')}}"></script>
@yield('foot')
</body>
</html>
