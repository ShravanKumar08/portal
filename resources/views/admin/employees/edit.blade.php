@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($Employee, ['route' => ["employee.update", $Employee->id],'method' => 'PUT', 'class' => 'form-horizontal','files' => true]) }}
                    @include('admin.employees.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection