@extends('layouts.master')
@push('stylesheets')
<link rel="stylesheet" href="{{ asset('/css/jquery.steps.css') }}"> 
<link rel="stylesheet" href="{{ asset('/css/interview.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/dist/select2.min.css') }}">
@endpush
@section('content')

<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Pre-Screening</h3>
                    {{ Form::open(['route' => 'interviewprescreening.store', 'method' => 'post', 'class' => 'form-horizontal']) }}
                    @include('admin.interviewprescreening.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

