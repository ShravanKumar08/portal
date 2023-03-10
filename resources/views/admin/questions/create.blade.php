@extends('layouts.master')

@section('content')
    <div class="row justify-content-md-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => [ "question.store"], 'class' => 'form-horizontal', 'id'=>'question_form']) }}
                    @include('admin.questions.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection