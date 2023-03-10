@extends('layouts.master')
@push('stylesheets')
<link rel="stylesheet" href="{{ asset('/css/jquery.steps.css') }}"> 
<link rel="stylesheet" href="{{ asset('/css/interview.css') }}">
<link rel="stylesheet" href="{{ asset('/plugins/select2/css/dist/select2.min.css') }}">
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($CandidateCall,['route' => [ "interviewcall.update", $CandidateCall->id],'id'=>'interveiwcall_post','method' => 'PUT', 'class' => 'form-horizontal']) }}
                    @include('admin.interviewcall.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

