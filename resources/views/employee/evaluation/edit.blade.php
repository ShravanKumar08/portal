@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($id,['route' => [ 'employee.evaluation.update', $id ],'method' => 'PUT', 'class' => 'form-horizontal','id' => 'assessment-form']) }}
                        @include('employee.evaluation.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection