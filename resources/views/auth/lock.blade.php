@extends('layouts.login')

@section('content')
    <section id="wrapper">
        <div class="login-register" style="background-image:url({{ asset('assets/images/background/login-register.jpg') }});">
            <div class="login-box card">
                <div class="card-body">

                    @include('flash::message')
                    <form class="form-horizontal form-material" id="loginform" action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <div class="col-xs-12 text-center">
                                @php
                                    $avatar = @$last_logged->employee ? @$last_logged->employee->avatar : Setting::fetch('LOGO_LIGHT_ICON');
                                @endphp
                                <div class="user-thumb text-center"> <img alt="thumbnail" class="img-circle" width="100" src="{{ $avatar }}">
                                    <h3>{{ $last_logged->name }}</h3>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="email" value="{{ $last_logged->email }}">
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" required="" name="password" placeholder="Password" autofocus>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <div class="col-xs-12">
                                <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Login</button>
                                <button class="btn btn-danger btn-md btn-block text-uppercase waves-effect waves-light" type="button" onclick="location.href='{{ url('login?new=1') }}'">Not this user ?</button>
                            </div>
                        </div>
                        @include('auth.partials.sociallogin')
                    </form>
                </div>
            </div>
        </div>

    </section>

    {{--<section id="wrapper" class="login-register login-sidebar" style="background-image:url('{{ asset('assets/images/background/login-register.jpg') }}');">--}}
        {{--<div class="login-box card">--}}
            {{--<div class="card-body">--}}
                {{----}}
                {{--<form class="form-horizontal form-material" id="loginform" action="{{ route('login') }}" method="post">--}}
                    {{--@csrf--}}
                    {{--<a href="javascript:void(0)" class="text-center db"><img src="/assets/images/logo-icon.png" alt="Home" /><br/><img src="/assets/images/logo-text.png" alt="Home" /></a>--}}
                    {{--<div class="form-group m-t-40">--}}
                        {{--<div class="col-xs-12">--}}
                            {{--<input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="text" required="" name="email" placeholder="Email" value="{{ old('email') }}" autofocus>--}}
                            {{--@if ($errors->has('email'))--}}
                                {{--<span class="invalid-feedback">--}}
                                        {{--<strong>{{ $errors->first('email') }}</strong>--}}
                                    {{--</span>--}}
                            {{--@endif--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<div class="col-xs-12">--}}
                            {{--<input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" required="" name="password" placeholder="Password">--}}
                            {{--@if ($errors->has('password'))--}}
                                {{--<span class="invalid-feedback">--}}
                                        {{--<strong>{{ $errors->first('password') }}</strong>--}}
                                    {{--</span>--}}
                            {{--@endif--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<div class="col-md-12">--}}
                            {{--<div class="checkbox checkbox-primary pull-left p-t-0">--}}
                                {{--<input id="checkbox-signup" type="checkbox" {{ old('remember') ? 'checked' : '' }} name="remember" >--}}
                                {{--<label for="checkbox-signup"> Remember me </label>--}}
                            {{--</div>--}}
                            {{--<a href="javascript:void(0)" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Forgot pwd?</a> </div>--}}
                    {{--</div>--}}
                    {{--<div class="form-group text-center m-t-20">--}}
                        {{--<div class="col-xs-12">--}}
                            {{--<button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Log In</button>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row">--}}
                        {{--<div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">--}}
                            {{--<div class="social">--}}
                                {{--<a href="{{ url('login/github') }}" class="btn  btn-github" data-toggle="tooltip" title="Login with Github"> <i aria-hidden="true" class="fa fa-github"></i> </a>--}}
                                {{--<a href="{{ url('login/google') }}" class="btn btn-googleplus" data-toggle="tooltip" title="Login with Google"> <i aria-hidden="true" class="fa fa-google-plus"></i> </a>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</form>--}}
                {{--<form class="form-horizontal" id="recoverform" action="index.html">--}}
                    {{--<div class="form-group ">--}}
                        {{--<div class="col-xs-12">--}}
                            {{--<h3>Recover Password</h3>--}}
                            {{--<p class="text-muted">Enter your Email and instructions will be sent to you! </p>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="form-group ">--}}
                        {{--<div class="col-xs-12">--}}
                            {{--<input class="form-control" type="text" required="" placeholder="Email">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="form-group text-center m-t-20">--}}
                        {{--<div class="col-xs-12">--}}
                            {{--<button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</form>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</section>--}}
@endsection