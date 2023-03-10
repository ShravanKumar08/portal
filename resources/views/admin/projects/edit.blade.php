@extends('layouts.master')

@section('content')
    <div class="row justify-content-md-center">
        <div class="col-7">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($Model,['route' => [ "project.update", $Model->id],'method' => 'PUT', 'class' => 'form-horizontal']) }}
                    @include('admin.projects.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection