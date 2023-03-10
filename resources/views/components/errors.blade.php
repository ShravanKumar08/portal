<section id="wrapper" class="error-page">
    <div class="error-box">
        <div class="error-body text-center">
            <h1>{{ @$code }}</h1>
            <h3 class="text-uppercase">{{ @$message }}</h3>
            {{--<p class="text-muted m-t-30 m-b-30">YOU SEEM TO BE TRYING TO FIND HIS WAY HOME</p>--}}
            <a href="{{ URL::previous() }}" class="btn btn-info btn-rounded waves-effect waves-light m-b-40">Back</a>
            <a href="{{ url('/') }}" class="btn btn-warning btn-rounded waves-effect waves-light m-b-40">Home</a>

            @guest
                <a href="{{ url('login') }}" class="btn btn-danger btn-rounded waves-effect waves-light m-b-40">Login</a>
            @endguest
        </div>
    </div>
</section>
