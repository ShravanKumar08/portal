@extends('layouts.master')

@section('content')
    <div class="row justify-content-md-center">
        <div class="col-5">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => 'tempcard.store', 'class' => 'form-horizontal']) }}
                    @include('admin.tempcard.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection