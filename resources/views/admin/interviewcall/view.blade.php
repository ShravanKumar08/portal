@extends('layouts.master')
@push('stylesheets')
<link href="{{asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css"')}}" rel="stylesheet">
<style>
    .card .card-subtitle
    {
    font-weight: 900;
    margin-bottom: -13px !important;
    color: #2f897b !important;
    font-size: 20px;
    margin-top: 6px;
    text-transform: capitalize;
    }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
                <div class="row">
                        @include('admin.interviewcall.partials.show.LeftSideBlock')

                        @include('admin.interviewcall.partials.show.RightSideBlock')
                </div>
        </div>
@endsection
@push('stylesheets')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>  
@endpush

@push('scripts')
    <script src="{{ asset('/assets/plugins/moment/moment.js') }}"></script>
    <link href="{{ asset('assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>

    <script type="text/javascript">
        $(function () {
            $('#datetime-format').datetimepicker({
                format : 'YYYY-MM-DD hh:mm A' ,
                icons: {
                    time: "fa fa-clock-o",
                    date: "fa fa-calendar",
                      up: "fa fa-arrow-up",
                previous: "fa fa-arrow-left",
                    next: "fa fa-arrow-right",
                    down: "fa fa-arrow-down"
                }
            });
        });
    </script>
@endpush


