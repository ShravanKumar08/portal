@extends('layouts.master')

@section('content')
    <div class="row justify-content-md-center">
        <div class="col-7">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">{{ $Model->userSettingName }}</h3>
                    <hr />
                    {{ Form::model($Model,['route' => [ "employee.usersettings.update", $Model->id],'method' => 'PUT', 'class' => 'form-horizontal']) }}
                    @include('employee.usersettings.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection