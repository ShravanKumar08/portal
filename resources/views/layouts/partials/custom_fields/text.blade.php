{{ Form::text($field->name, old($field->name) ?: @$field->default, ['class' => 'form-control']) }}
