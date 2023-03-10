@extends('layouts.master')

@section('content')
    <div class="row justify-content-md-center">
        <div class="col-8">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($Holiday,['route' => [ "holiday.update", $Holiday->id],'method' => 'PUT', 'class' => 'form-horizontal']) }}
                    @include('admin.holidays.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection