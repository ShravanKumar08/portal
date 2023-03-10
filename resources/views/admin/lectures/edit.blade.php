@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{ Form::open(['route' => [ "lectures.update", $id],'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'entry_form']) }}
                <div class="row p-t-20">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('employee_id','Select Lecturer*',['class'=>'']) }}
                            {{ Form::select('employee_id', $employees, @$employee_id, ['class' => 'form-control select2','placeholder'=>'Select Employee']) }}
                        </div>
                    </div>
                </div>
                @include('employee.lectures.partials.form')
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection