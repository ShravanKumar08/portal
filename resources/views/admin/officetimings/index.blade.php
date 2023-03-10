@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'display nowrap table table-hover table-striped table-bordered', 'id' => 'datatable-buttons']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="TimingsModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Create OfficeTimings</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>  
                {{ Form::open(['route' => [ "officetiming.store"], 'class' => 'form-horizontal']) }}
                <div class="modal-body">                
                    {{ Form::label('name','Title*',['class'=>'']) }}
                        {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
                        {{ Form::hidden('employeetype', @request()->scope, ['class' => 'form-control']) }}
                </div>
                <div class="modal-footer">
                    {!! Form::button('Submit', array('class'=>'btn btn-success', 'type' => 'submit')) !!}
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
                </div>
                {{ Form::close() }}
            </div>
            <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>
@endsection

@include('layouts.partials.datatable_scripts')