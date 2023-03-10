@extends('layouts.login')

@section('content')
    <section id="wrapper" class="login-register login-sidebar" style="background-image:url('{{ asset('assets/images/background/login-register.jpg') }}');">
        <div class="login-box card">
            <div class="card-body">

                <form class="form-horizontal form-material" id="loginform" action="{{ route('login') }}" method="post">
                    @csrf
                    <a href="javascript:void(0)" class="text-center db">
                        <img src="{{ $logo_light_icon }}" alt="Home" class="dark-logo" />
                        <br/>
                        <img src="{{ $logo_light_text }}" class="login-text" alt="Home" style=" height: 100px; margin-top: 25px; "/>
                    </a>
                    <div class="mt-3">
                        @include('auth.partials.sociallogin')
                    </div>
                    <div class="form-group m-t-40">
                        <div class="col-xs-12">
                            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="text" required="" name="email" placeholder="Email" value="{{ old('email') }}" autofocus>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" required="" name="password" placeholder="Password">
                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="checkbox checkbox-primary pull-left p-t-0">
                                <input id="checkbox-signup" type="checkbox" {{ old('remember') ? 'checked' : '' }} name="remember" >
                                <label for="checkbox-signup"> Remember me </label>
                            </div>
                            <a href="{{ route('password.request') }}" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Forgot pwd?</a> </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Log In</button>
                        </div>
                    </div>
                </form>
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
            </div>
        </div>
    </section>
@endsection
