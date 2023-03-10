@php
    $options = explode(',',$field->select_options);
@endphp

{{ Form::select($field->name, array_combine($options, $options) , old($field->name), ['class' => 'form-control','placeholder'=>'Select Options']) }}
