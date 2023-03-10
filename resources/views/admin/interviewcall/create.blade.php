@extends('layouts.master')
@push('stylesheets')
<link rel="stylesheet" href="{{ asset('/css/jquery.steps.css') }}"> 
<link rel="stylesheet" href="{{ asset('/css/interview.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/dist/select2.min.css') }}">
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
                    {{ Form::open(['route' => 'interviewcall.store', 'class' => 'form-horizontal','id'=>'interveiwcall_post', 'enctype'=>"multipart/form-data"]) }}
                    @include('admin.interviewcall.partials.form')
                    {{ Form::close() }}
        </div>
    </div>
@endsection

