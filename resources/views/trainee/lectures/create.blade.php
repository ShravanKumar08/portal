@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{ Form::open(['route' => 'trainee.lectures.store', 'class' => 'form-horizontal', 'id' => 'lecture-form']) }}
                @include('trainee.lectures.partials.form')
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection