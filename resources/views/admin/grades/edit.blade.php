@extends('layouts.master')

@section('content')
    <div class="row justify-content-md-center">
        <div class="col-8">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($Grade,['route' => [ "grade.update", $Grade->id],'method' => 'PUT', 'class' => 'form-horizontal']) }}
                    @include('admin.grades.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection