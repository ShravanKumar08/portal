<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('name','Title*',['class'=>'']) }}
                {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('bg_color','Background Color*',['class'=>'']) }}
                {{ Form::text('bg_color', old('bg_color'), ['class' => 'colorpicker form-control']) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('text_color','Text Color*',['class'=>'']) }}<br />
                {{ Form::text('text_color', old('text_color'), ['class' => 'colorpicker form-control']) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('report_sort','Report Sort',['class'=>'']) }} <br/>
                <div class="switch">
                    <label>
                        {{ Form::hidden('report_sort', 0) }}
                        {{ Form::checkbox('report_sort', 1,  old('report_sort')) }}<span class="lever switch-col-blue"></span>
                    </label>
                </div>
            </div>
    </div>
    </div>
    <h3 class="card-title">Slots</h3>
    <hr>
    <div class="row p-t-20">
        @foreach ($timings as $key => $timing)
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label($key,$timing['text'],['class'=>'']) }}
                {{ Form::text("value[$key]", @$Model->value->$key, ['class' => 'form-control '.($timing['time'] == 'true' ? 'clockpicker' : 'datetimepicker'), 'autocomplete' => 'off','data-autoclose'=>"true"]) }}
            </div>
        </div>
        @endforeach
    </div>
    @if(!(@$Model->id))
    <div class="col-md-4">
        <div class="form-group">
            {{ Form::label('new_timing','Create new office timing (for assign employees)',['class'=>'']) }} <br/>
            <div class="switch">
                <label>
                    {{ Form::hidden('new_timing', 0) }}
                    {{ Form::checkbox('new_timing', 1,['checked'],['class'=>'show_employee','id'=>'new_timing']) }}<span class="lever switch-col-blue"></span>
                </label>
            </div>
        </div>
    </div> 
  

    <div class="employee">
        <h3 class="card-title">Employees</h3>
        <div class="row p-t-20">
            <div class="col-md-12">
                <div class="form-group">
                    <h5 class="box-title">Select Employees</h5>
                    {{ Form::select('employee_ids[]',  @$Employees ,'', ['class' => 'form-control searchablemultiselect', 'multiple' => true]) }}
                </div>
            </div>
        </div>
        <hr>
    </div>

    @endif  

    <div class="form-actions">
        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
    </div>
</div>

@include('layouts.partials.multiselect_scripts')

@push('stylesheets')
<link href="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css">
<link href="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
<!--Color Picker-->
<link href="{{ asset('assets/plugins/jquery-asColorPicker-master/css/asColorPicker.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.js') }}" type="text/javascript"></script>
<!-- Color Picker Plugin JavaScript -->
<script src="{{ asset('assets/plugins/jquery-asColorPicker-master/libs/jquery-asColor.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-asColorPicker-master/libs/jquery-asGradient.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {

      
        $(".show_employee").change(function() {
            if(this.checked) {
                $('.employee').show()
            }
            else{
                $('.employee').hide()
            }
        });


        $('.clockpicker').clockpicker({
            donetext: 'Done',
        });
        $(".colorpicker").asColorPicker();
        $('.datetimepicker').datetimepicker({
            format: 'HH:mm',
            icons: {
                up: "fa fa-chevron-circle-up",
                down: "fa fa-chevron-circle-down",
                next: 'fa fa-chevron-circle-right',
                previous: 'fa fa-chevron-circle-left'
            }
        });
    });
</script>
@endpush
