@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => 'trainee.userpermission.store', 'class' => 'form-horizontal']) }}
                    @include('employee.userpermissions.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection