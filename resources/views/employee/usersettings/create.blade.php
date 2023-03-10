@extends('layouts.master')

@section('content')
    <div class="row justify-content-md-center">
        <div class="col-7">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Create Settings</h3>
                    <hr />
                    {{ Form::open(['route' => 'employee.usersettings.store','class' => 'form-horizontal']) }}
                    @include('employee.usersettings.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection