@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($Model, ['route' => [ "myprofile"],'method' => 'POST', 'class' => 'form-horizontal']) }}
                    <h3 class="card-title">Person Info</h3>
                    <hr>
                    <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('name','Name*',['class'=>'']) }}
                                {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('email','Email*',['class'=>'']) }}
                                {{ Form::text('email', old('email'), ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('password','Password*',['class'=>'']) }}
                                {{ Form::password('password', ['class' => 'form-control']) }}
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
                        <button type="button" class="btn btn-inverse">Cancel</button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection