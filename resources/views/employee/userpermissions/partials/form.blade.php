<div class="form-body">
    <div class="row p-t-20">                       
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('date','Date*',['class'=>'']) }}
                {{ Form::text('date', old('date'), ['class' => 'form-control datepicker', 'autocomplete' => 'off']) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('start','Start Time*',['class'=>'']) }}
                {{ Form::text('start', old('start'), ['class' => 'form-control clockpicker', 'autocomplete' => 'off','data-autoclose'=>"true"]) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('end', 'End Time*', ['class' => '']) }}
                {{ Form::text('end', old('end'), ['class' => 'form-control clockpicker', 'autocomplete' => 'off','data-autoclose'=>"true"]) }}
            </div>
        </div>
    </div>
    <div class="row p-t-20">                
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('reason', 'Reason*', ['class' => '']) }}
                {{ Form::textarea('reason', old('reason'), ['class' => 'form-control','rows' => 4]) }}
            </div>
        </div>
    </div>
    @if($auth_employee->getAvailablePermissionCompensationCount())
        <div class="row p-t-20">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('compensation','Do you want to take compensation permission',['class'=>'']) }} <br/>
                    <div class="switch">
                        <label>
                            {{ Form::hidden('compensate', 0) }}
                            {{ Form::checkbox('compensate', 1 ,  '') }}<span class="lever switch-col-blue"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="form-actions">
        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
        <button type="reset" class="btn btn-inverse">Reset</button>
    </div>
</div>

@push('stylesheets')
    <link href="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    <!--Auto complete Search-->
    <script type="text/javascript">
        $(document).ready(function () {
             var holidays = {!! json_encode($Holidays) !!};
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true, todayHighlight: true,
                daysOfWeekDisabled: [0],
                datesDisabled: holidays,
            });
        });
        $('.clockpicker').clockpicker({
            donetext: 'Done',
        });
    </script>
@endpush