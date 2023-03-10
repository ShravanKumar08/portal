@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => 'assesment.store', 'class' => 'form-horizontal']) }}
                    @include('admin.assesment.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection