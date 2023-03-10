@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{ Form::open(['route' => 'entry.store', 'class' => 'form-horizontal', 'id' => 'entry_form']) }}
                @include('admin.entries.partials.form')
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection