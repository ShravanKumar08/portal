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
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('date','Date*',['class'=>'']) }}
                {{ Form::text('date', old('date'), ['class' => 'form-control datepicker','id' => 'date-format']) }}
            </div>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
        <button type="reset" class="btn btn-inverse">Reset</button>
    </div>
</div>
@push('stylesheets')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>  
@endpush

@push('scripts')
    <script src="{{ asset('/assets/plugins/moment/moment.js') }}"></script>
    <link href="{{ asset('assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>

    <script type="text/javascript">
        $(function () {
            $('#date-format').datetimepicker({
                format : 'YYYY-MM-DD hh:mm' ,
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