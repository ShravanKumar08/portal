@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::model(@$Model,['route' => [ "officetimingslot.store"], 'class' => 'form-horizontal']) }}
                    @include('admin.officetimingslots.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection