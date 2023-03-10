@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($Model,['route' => [ "trainee.leave.update", $Model->id],'method' => 'PUT', 'class' => 'form-horizontal']) }}
                    @include('employee.leaves.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection