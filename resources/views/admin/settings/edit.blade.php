@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">{{ $Model->settingName }}</h3>
                    <hr />
                    {{ Form::model($Model, ['route' => [ "setting.update", $Model->id],'method' => 'PUT', 'class' => 'form-horizontal', 'files' => true]) }}
                    @include('admin.settings.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="emailid_popup" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="email_preview">
                    <div class="modal-header">
                            <h4 class="modal-title">Preview</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        
                    </div>
                    <div class="modal-body">
                            {!! Form::label('eMail id') !!}
                            {!! Form::email('to_emailid','', ['class' => 'form-control to_emailid', 'required']) !!}
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="prev_submit" class="btn btn-primary">Send</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>         
        </div>
    </div>
        
@endsection