<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('employee','Employee: ',['class'=>'']) }}
                {{ Form::select('employee_id', $employees, old('employee_id'), ['class' => 'form-control','placeholder'=>'Select Employee']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('date','Date*',['class'=>'']) }}
                {{ Form::text('date', old('date'), ['class' => 'form-control datepicker', 'autocomplete' => 'off']) }}
            </div>
        </div>
    </div>
    <div class="row p-t-20"> 
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('start','Start Time*',['class'=>'']) }}
                {{ Form::text('start', old('start'), ['class' => 'form-control clockpicker', 'autocomplete' => 'off','data-autoclose'=>"true"]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('end', 'End Time', ['class' => '']) }}
                {{ Form::text('end', old('end'), ['class' => 'form-control clockpicker', 'autocomplete' => 'off','data-autoclose'=>"true"]) }}
            </div>
        </div>
    </div>
    <div class="row p-t-20"> 
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('inip','In IP Address',['class'=>'']) }}
                <div id="inip_elem"></div> <br/>
                {{ Form::hidden('inip', 0) }}
                <button class="btn btn-info setip" data-input="#inip_elem" type="button">setIp</button>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('outip', 'Out IP Address', ['class' => '']) }}
                <div id="outip_elem"></div> <br/>
                {{ Form::hidden('outip', 0) }}
                <button class="btn btn-info setip" data-input="#outip_elem" type="button">setIp</button>
            </div>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="reset" class="btn btn-inverse">Reset</button>
    </div>
</div>

@push('stylesheets')
    <link href="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/ipInput.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <!--Input mask-->
    <script src="{{ asset('js/ipInput.js') }}"></script>
    <!--Auto complete Search-->
    <script type="text/javascript">
        $(document).ready(function () {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true, todayHighlight: true,
            });
            $('.clockpicker').clockpicker({
                donetext: 'Done',
            });

            $('#inip_elem').ipInput().setIp('{{ @$Model->inip }}');
            $('#outip_elem').ipInput().setIp('{{ @$Model->outip }}');

            $('.setip').on('click', function(e) {
                var $elem = $($(this).data('input')).ipInput();
                $elem.setIp("{{ \Request::ip() }}");
            });

            $('#entry_form').on('submit', function(e) {
                $('input[name="inip"]').val($('#inip_elem').getIp());
                $('input[name="outip"]').val($('#outip_elem').getIp());
            });
        });
    </script>
@endpush