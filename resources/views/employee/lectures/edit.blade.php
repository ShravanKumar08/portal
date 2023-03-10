@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{ Form::open(['route' => [ "employee.lectures.update", $id],'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'entry_form']) }}
                @include('employee.lectures.partials.form')
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection