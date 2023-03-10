@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($Model, ['route' => [ "user.store"],'method' => 'POST', 'class' => 'form-horizontal','files'=>true]) }}
                    @include('admin.users.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection