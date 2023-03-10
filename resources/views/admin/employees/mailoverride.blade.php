@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Override Report Notification Mail</h3>
                    <hr />
                    {{ Form::model(['route' => [ "employee.mailoverride", $Model->id],'method' => 'PUT', 'class' => 'form-horizontal', 'files' => true]) }}
                    @include('admin.settings.partials._mail_setup')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

@endsection