@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">{{ $Model->key }}</h3>
                    {{ Form::model($Model, ['route' => [ "schedule.update", $Model->id],'method' => 'PUT', 'class' => 'form-horizontal', 'files' => true]) }}
                    <hr>
                    <div class="form-group">
                        <h5 class="box-title">Schedule Date</h5>
                        {{ Form::text('schedule_date', $Model->schedule_date ? \Carbon\Carbon::parse($Model->schedule_date)->toDateString() : '', ['class' => 'form-control datepicker', 'autocomplete' => 'off']) }}
                    </div>
                    @if($Model->key == 'OFFICE_TIMING_SLOT')
                        @include('admin.schedules.partials._office_timing_slot')
                    @elseif($Model->key == 'OFFICIAL_PERMISSION_LEAVE_DAYS')
                        @include('admin.schedules.partials._permission_leave_saturday')
                    @elseif($Model->key == 'TRAINEE_TO_PERMANENT')
                        @include('admin.schedules.partials._trainee_to_permanent')
                    @endif
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <div id="DayslotsModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Day Slots</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>  
                {{ Form::open(['route' => [ "schedule.slotSave" ],'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'submitForm']) }}
                <div class="modal-body">    
                    <div id="cal_chose_date" class="mb-2"></div>            
                    {{ Form::select("slot", $slots, '', ['class' => 'form-control', 'id' => 'slot', 'placeholder' => 'Select Slot']) }}
                    {{ Form::hidden("schedule_id", @$Model->id, ['id' => 'officetiming_id']) }}
                    {{ Form::hidden("day", '' ,['id' => 'Day']) }}
                </div>
                <div class="modal-footer">
                    {!! Form::button('Submit', array('class'=>'btn btn-success', 'type' => 'submit', 'id' => 'submitBtn')) !!}
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
                </div>
                {{ Form::close() }}
            </div>
            <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>
@endsection

@push('scripts')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true, todayHighlight: true,
            });
        });
    </script>
@endpush
