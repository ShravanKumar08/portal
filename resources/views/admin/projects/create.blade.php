@extends('layouts.master')

@section('content')
    <div class="row justify-content-md-center">
        <div class="col-7">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => 'project.store', 'class' => 'form-horizontal']) }}
                    @include('admin.projects.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection