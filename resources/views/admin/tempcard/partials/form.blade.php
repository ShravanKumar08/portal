<div class="form-body">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('employee_id','Choose Employee*',['class'=>'']) }}
            {{ Form::select('employee_id', $employees, old('employee_id'), ['class' => 'form-control select2','placeholder'=>'Select Employee']) }}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label class="">Date Range</label>
            <div class="input-daterange input-group" id="date-range">
                {{ Form::text('from',  old('from'), ['class' => 'form-control datepicker', 'placeholder' => 'From', 'autocomplete' => 'off']) }}
                <span class="input-group-addon bg-info b-0 text-white">to</span>
                {{ Form::text('to',  old('to'), ['class' => 'form-control datepicker', 'placeholder' => 'To', 'autocomplete' => 'off']) }}
            </div>
        </div>
    </div>   
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('tempcard', 'Temporary Access Card No*', ['class' => '']) }}
            {{ Form::text('tempcard', old('tempcard'), ['class' => 'form-control']) }}
        </div>
    </div>
    @if(@!$Model->id)
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('report_start', 'Report Start', ['class' => '']) }}
                {{ Form::text('report_start', old('report_start'), ['class' => 'form-control clockpicker', 'autocomplete' => 'off']) }}
            </div>
        </div>
    @endif
    <div class="col-md-2">
        <div class="form-group">
            {{ Form::label('active','Active',['class'=>'']) }} <br/>
            <div class="switch">
                <label>
                    {{ Form::hidden('active', 0) }}
                    {{ Form::checkbox('active', 1, @$Model->active) }}<span class="lever switch-col-blue"></span>
                </label>
            </div>
        </div>
    </div>  
    <div class="form-actions">
        {!! Form::hidden('id', @$Model->id) !!}
        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
        <button type="reset" class="btn btn-inverse">Reset</button>
    </div>
</div>

@push('stylesheets')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
        <!--Auto complete Search-->
        <script type="text/javascript">
            $(document).ready(function () {
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true, todayHighlight: true,
                    daysOfWeekDisabled: [0],
                });

                $('.clockpicker').clockpicker({
                    autoclose:true,
                });

                $('.select2').select2();
            });
    </script>
@endpush