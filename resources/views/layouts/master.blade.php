<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('favicon.ico?v=2') }}" rel="icon" type="image/x-icon" />

    <!-- Mix Library CSS -->
    <link rel="stylesheet" href="{{ asset('css/lib.css') }}">    

    @stack('stylesheets')

    <!-- Mix App CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="{{ asset('css/colors/'.$theme_color.'.css') }}" id="theme" rel="stylesheet">
    <link href="{{ asset('css/custom.css?v='.File::lastModified('css/custom.css')) }}" id="theme" rel="stylesheet">
    <!-- Mix Head Scripts-->
    <script src="{{ asset('js/headscripts.js') }}"></script>
</head>

<body class="fix-header fix-sidebar card-no-border">

<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
    </svg>
</div>
<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div id="main-wrapper">
    @includeWhen($is_admin_route, 'layouts.partials.admin.topbar')
    @includeWhen($is_admin_route, 'layouts.partials.admin.left-sidebar')

    @includeWhen($is_employee_route, 'layouts.partials.employee.topbar')
    @includeWhen($is_employee_route, 'layouts.partials.employee.left-sidebar')

    @includeWhen($is_trainee_route, 'layouts.partials.trainee.topbar')
    @includeWhen($is_trainee_route, 'layouts.partials.trainee.left-sidebar')

    <div class="page-wrapper">
        @include('layouts.partials.breadcrumb')

        <div class="{{ $ContainerClass ?? 'container-fluid' }}">
            @include('flash::message')
            @yield('content')

            @include('layouts.partials.loader-content')
            @stack('container')
        </div>

        {{--                @include('layouts.partials.idle-session')--}}
        @include('layouts.partials.footer')
    </div>
</div>

<!-- Mix All Javascripts -->
<script src="{{ asset('js/all.js') }}"></script>
<script src="{{ asset('js/custom.js?v='.File::lastModified('js/custom.js'))  }}"></script>

<!-- ============================================================== -->
<!-- Style switcher -->
<!-- ============================================================== -->
{{--    <script src="{{ asset('assets/plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>--}}

@if(request()->route()->getPrefix() != '/admin')
    <script src="{{ asset('js/countimer.js') }}"></script>
@endif

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        $('#flash-overlay-modal').modal();
        // nice scroll
        //var cursor_color = '#b2beb5';
        //$("body").niceScroll({cursorborder: "", cursorcolor: cursor_color, boxzoom: true, scrollspeed: 60});  // The document page (body)

        //if ($(".table-responsive").length) {
        //    $(".table-responsive").niceScroll({cursorborder: "", cursorcolor: cursor_color, boxzoom: false});
        //}

        if ($('#countdown-timer').length) {
            @if($elaped = @$auth_employee->elapsedTime)
            $('#countdown-timer').countimer({
                enableEvents: false,
                autoStart: true,
                useHours: true,
                minuteIndicator: '',
                secondIndicator: '',
                separator: ' : ',
                leadingZeros: 2,
                // Initial time
                initHours: '{{ \Carbon\Carbon::parse($elaped)->hour }}',
                initMinutes: '{{ \Carbon\Carbon::parse($elaped)->minute }}',
                initSeconds: '{{ \Carbon\Carbon::parse($elaped)->second }}',
            });
            @endif
        }

        //Nice scroll resize when element height/width changed
        // new ResizeSensor($('body'), function(){
        //   $("body").getNiceScroll().resize();
        //});

        //new ResizeSensor($('.table-responsive'), function(){
        //    $(".table-responsive").getNiceScroll().resize();
        //});

        //Remove empty side bars
        $('#sidebarnav ul:not(:has(>li))').each(function () {
            $(this).closest('li').remove();
        });

        var cursor_color = '#b2beb5';
        if ($(".dashScrolling").length) {
            $(".dashScrolling").niceScroll({cursorborder: "", cursorcolor: cursor_color, boxzoom: false});
        }

    });
</script>

@stack('scripts')
</body>
</html>
