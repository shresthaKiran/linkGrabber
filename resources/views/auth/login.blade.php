@extends('auth.registration')
@section('title', ' Welcome to Grocery Management system')
@section('content')
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 text">
            <h1><strong>Grocery Management System</strong></h1>
            <div class="description">
                <p>
                    A System to manage grocery items of your house.</strong>
                </p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3 form-box">
            <div class="form-top">
                <div class="form-top-left">
                    <h3>Login to our site</h3>
                    <p>Enter your username and password to log on:</p>
{{--                    @include('includes.errors')--}}
                </div>
                <div class="form-top-right">
                    <i class="fa fa-lock"></i>
                </div>
            </div>
            <div class="form-bottom">
                {!! form($form) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 form-box">
                Don't have an account? Click here to <a href="{{route('auth.register')}}"><strong>Sign
                        Up</strong></a>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        $.backstretch("../img/backgrounds/1.jpg");
    </script>
@endsection