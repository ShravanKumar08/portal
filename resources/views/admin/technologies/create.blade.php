@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => [ "technology.store"], 'class' => 'form-horizontal']) }}
                    @include('admin.technologies.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection