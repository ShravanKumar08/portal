<div class="form-body">
     @if($Model->id)
        <div class="row p-t-20">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('employee','Employee: ',['class'=>'']) }}
                    <h4>{{ $Model->employee->name }}</h4>
                </div>
            </div>
        </div>
    @endif
    <div class="row p-t-20">
        {{ Form::hidden('employee_id', $Model->employee_id) }}
        <div class="col-md-8">
            <div class="form-group">
                {{ Form::label('start', 'Start Time:', ['class' => '']) }}
                {{ Form::text('start', old('start'), ['class' => 'form-control clockpicker', 'autocomplete' => 'off',
                        'data-autoclose'=>"true"]) }}
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                {{ Form::label('date', 'Date', ['class' => '']) }}
                {{ Form::text('date', old('date'), ['class' => 'form-control datepicker', 'autocomplete' => 'off']) }}
            </div>
        </div>
    </div>
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
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true, todayHighlight: true,
            });
        });
        $('.clockpicker').clockpicker({
            donetext: 'Done',
        });
    </script>
@endpush