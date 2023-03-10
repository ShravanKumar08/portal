@extends('layouts.master')

@section('content')
    <div class="row justify-content-md-center">
        <div class="col-7">
            <div class="card">
                <div class="card-body">
                    <div class="form-body">
                        {{ Form::open(['route' => "lectures.create", 'class' => 'form-horizontal','method' => 'GET']) }}
                        <div class="row p-t-20">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('employee_id','Choose Employee*',['class'=>'']) }}
                                    {{ Form::select('employee_id', $employees, $Model->employee_id, ['class' => 'form-control select2','placeholder'=>'Select Employee']) }}
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="leaveFormDiv">
    </div>
@include('layouts.partials.multiselect_scripts')
@endsection
@push('stylesheets')
    <link href="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/ipInput.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <!--Input mask-->
    <script src="{{ asset('js/ipInput.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.select2').select2();

            $('select[name="employee_id"]').on('change', function () {
                var eid = $(this).val();
                var $formDiv = $("#leaveFormDiv");

                if(eid){
                    $.ajax({
                        url: "{{ route('lectures.form') }}",
                        method: 'GET',
                        data: {
                            employee_id: eid,
                        },
                        beforeSend: function(){
                            $formDiv.html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
                        },
                        success: function (result) {
                            $formDiv.html(result);
                        }
                    });
                }else{
                    $formDiv.html('');
                }
            });
        });
    </script>
@endpush
