@extends('layouts.master')

@section('content')
    <div class="row justify-content-md-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($Model,['route' => [ "question.update", $Model->id],'method' => 'PUT', 'class' => 'form-horizontal', 'id'=>'question_form']) }}
                    @include('admin.questions.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection