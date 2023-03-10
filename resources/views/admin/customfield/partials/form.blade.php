<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group">
                @php
                    if(@$Model->model_type){
                       $mtype = explode('\\',$Model->model_type);
                       $mtype = end($mtype);
                    }
                @endphp
                {{ Form::label('model_type', 'Model Type', ['class' => '']) }}
                {{ Form::select('model_type', \App\Models\CustomField::$model_types, (@$Model->model_type) ? strtolower($mtype) : old('model_type'), ['class' => 'form-control', 'disabled' => @$Model->id]) }}
            </div>
            <div class="form-group">
                {{ Form::label('name', 'Field Name *', ['class' => '']) }}
                <div class="input-group">
                    @if(!@$Model->id)
                        <span class="input-group-addon" id="basic-addon1">employee_</span>
                        {{ Form::text('field_name', old('field_name'), ['class' => 'form-control', 'aria-describedby'=>"basic-addon1", 'readonly' => @$Model->id, 'ondrop' => 'return false;', 'onpaste' => 'return false;', 'onkeypress' => 'return IsAlphaNumeric(event, this.type);']) }}
                    @else
                        {{ Form::text('name', old('name'), ['class' => 'form-control', 'readonly']) }}
                    @endif
                </div>
                <span>* Special characters / Numbers / Capitalize are not allowed</span>
            </div>
            <div class="form-group">
                {{ Form::label('label', 'Field Label *', ['class' => '']) }}
                {{ Form::text('label', old('label'), ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('field_type', 'Field Type', ['class' => '']) }}
                {{ Form::select('field_type', \App\Models\CustomField::$field_types, old('field_type'), ['class' => 'form-control']) }}
            </div>
            @php
                $selectoptions = (@$Model->field_type == "select") ? "" : "display: none;";
                $padding = (@$Model->field_type == "increment") ? "" : "display: none;";
            @endphp
            <div class="form-group" id="selectoptions" style="{{ $selectoptions }}">
                <div class="formgroup-default">
                    {{ Form::text('select_options', old('select_options'), ['class' => 'form-control', 'data-role'=>"tagsinput", 'placeholder'=>"Add select options"]) }}
                </div>
            </div>
            <div class="form-group" id="padding" style="{{ $padding }}">
                    <div class="formgroup-default">
                        {{ Form::text('padding', old('padding'), ['class' => 'form-control', 'placeholder'=>"padding"]) }}
                    </div>
                </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('required','Required',['class'=>'']) }} <br/>
                <div class="switch">
                    <label>
                        {{ Form::hidden('required', 0) }}
                        {{ Form::checkbox('required', 1,  old('required')) }}<span class="lever switch-col-blue"></span>
                    </label>
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('Form Group', 'formgroup', ['class' => '']) }}
                {{ Form::text('formgroup', old('formgroup'), ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('Default', 'default', ['class' => '']) }}
                {{ Form::text('default', old('default'), ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                @php
                    $access_roles = (@$Model->roles) ? $Model->roles->pluck('id') : [];
                @endphp
                {{ Form::label('roles[]', 'Access Roles', ['class' => '']) }}
                {{ Form::select('roles[]', $roles, $access_roles, ['class' => 'form-control searchablemultiselect', 'multiple' => true]) }}
            </div>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="reset" class="btn btn-inverse">Reset</button>
    </div>
</div>

@include('layouts.partials.multiselect_scripts')

@push('stylesheets')
    <link href="{{ asset('assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet"/>
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#model_type').on('change', function () {
                $("#basic-addon1").html($(this).val() + '_');
            });

            $('#field_type').on('change', function () {
                if ($(this).val() == "select") {
                    $("#selectoptions").show();
                } else {
                    $("#selectoptions").hide();
                }

                if ($(this).val() == "increment") {
                    $("#padding").show();
                } else {
                    $("#padding").hide();
                }
            });
        });

        var specialKeys = new Array();
        specialKeys.push(8); //Backspace
        specialKeys.push(9); //Tab
        specialKeys.push(46); //Delete
        specialKeys.push(36); //Home
        specialKeys.push(35); //End
        specialKeys.push(37); //Left
        specialKeys.push(39); //Right


        function IsAlphaNumeric(e, type) {
            var textcode = "";
            if (type != "text") {
                textcode = 32;
            }
            var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
            var ret = ((keyCode == textcode) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || (specialKeys.indexOf(e.keyCode) != -1 && (e.charCode != e.keyCode)));
            return ret;
        }
    </script>
@endpush