{{ Form::open( ['route' => ['leave.convertLeave'],'method' => 'POST', 'class' => 'form-horizontal','id'=>'submitForm']) }}
    @include('layouts.partials.permission_form')
    {{ Form::hidden('leave_id', @$leave->id) }}
{{ Form::close() }}

@include('layouts.partials.permissionform_scripts')