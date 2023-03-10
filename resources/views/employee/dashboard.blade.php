@extends('layouts.master')

@section('content')
        <div class="">
              <!-- Dashboard Widgets -->
                @include('layouts.partials.dashboard_widgets.hurraytext_widget')
                @include('layouts.partials.dashboard_widgets.top_widgets')
        </div>
            

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Dashboard Calendar -->
                            @include('layouts.partials.dashboard_widgets.dashboard_calendar')
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <!-- Employee Birthdays -->
            @include('layouts.partials.dashboard_widgets.office_timings')
            @include('layouts.partials.dashboard_widgets.employee_birthdays')
            </div>
        </div>
        <div class="row">
            {{-- <div class="col-lg-6">
                <!-- Latest Reports -->
            @include('layouts.partials.dashboard_widgets.latest_reports')
            <!-- Column -->
            </div> --}}

            <div class="col-lg-6">
                <!-- Time In/Out -->
                @include('layouts.partials.dashboard_widgets.time_in_out')
            </div>

            {{--<div class="col-lg-4">--}}
                {{--<!-- Permissions -->--}}
            {{--@include('layouts.partials.dashboard_widgets.leave_requests')--}}
            {{--<!-- Column -->--}}
            {{--</div>--}}

            {{--<div class="col-lg-4">--}}
                {{--<!-- Leaves -->--}}
            {{--@include('layouts.partials.dashboard_widgets.permission_requests')--}}
            {{--<!-- Column -->--}}
            {{--</div>--}}

            {{--<div class="col-lg-4">--}}
                {{--@include('layouts.partials.dashboard_widgets.official_holidays')--}}
            {{--</div>--}}
        </div>
    </div>

@endsection
