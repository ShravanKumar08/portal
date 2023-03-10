@extends('layouts.login')

@section('content')
<section id="wrapper" class="login-register login-sidebar" style="background-image:url('{{ asset('assets/images/background/login-register.jpg') }}');">
    <div class="login-box card">
        <div class="card-body">
            <div class="card-body">
                @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group ">
                        <div class="col-xs-12">
                            <h3>Recover Password</h3>
                            <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required placeholder="Email">

                            @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light">
                                {{ __('Reset') }}
                            </button>
                        </div>
                    </div>
                    <a href="{{ url('/login') }}" class="text-dark pull-right"><i class="fa fa-sign-in m-r-5"></i>Back to Login</a>
                </form>
            </div>
        </div>
</section>

@endsection
