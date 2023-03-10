@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{ Form::open(['route' => 'employee.lectures.store', 'class' => 'form-horizontal', 'id' => 'lecture-form']) }}
                @include('employee.lectures.partials.form')
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection