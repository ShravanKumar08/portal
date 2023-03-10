@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($Model,['route' => [ "officetiming.update", $Model->id],'method' => 'PUT', 'class' => 'form-horizontal']) }}
                    @include('admin.officetimings.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

    <div id="DayslotsModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Day Slots</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>  
                {{ Form::open(['route' => [ "officetiming.slotSave" ],'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'submitForm']) }}
                <div class="modal-body">    
                    <div id="cal_chose_date" class="mb-2"></div>            
                    {{ Form::select("slot", $slots, '', ['class' => 'form-control', 'id' => 'slot', 'placeholder' => 'Select Slot']) }}
                    {{ Form::hidden("officetiming_id", @$Model->id, ['id' => 'officetiming_id']) }}
                    {{ Form::hidden("day", '' ,['id' => 'Day']) }}
                </div>
                <div class="modal-footer">
                    {!! Form::button('Submit', array('class'=>'btn btn-success', 'type' => 'submit', 'id' => 'submitBtn')) !!}
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
                </div>
                {{ Form::close() }}
            </div>
            <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>
@endsection