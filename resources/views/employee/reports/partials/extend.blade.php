{{ Form::open(['method' => 'POST', 'class' => 'form-horizontal','id'=>'extendForm']) }}
@php
    $elapsed = Carbon\Carbon::parse($reportitem->getElapsedTime());
@endphp
<div class="form-group">
    {!! Form::label('extend','Time Spent') !!}
    {!! Form::text('extend','',['class'=>'form-control','placeholder'=> "{$elapsed->hour}h {$elapsed->minute}m"]) !!}
</div>
<input type="hidden" name="report_id" value={{$reportitem->report_id}}>
<input type="hidden" name="reportitem_id" value={{$reportitem->id}}>
{{-- <input type="hidden" name="elapsed" id="elapsed" value={{$elapsed->format('H:i')}}> --}}
<div class="form-group">
    {{ Form::label('status', 'Status:', ['class' => '']) }}
    <br/>
    @foreach ($status as $id => $s)
    <label class="custom-control custom-radio">
        {!! Form::radio('status', $id, (@$Reportitem->status == $id), ['class' => 'custom-control-input', 'tabindex' => 5] ); !!} {{$s}}
        <span class="custom-control-indicator"></span>
    </label>
    @endforeach
</div>
<div class="form-group">
    {{ Form::label('include_break','Include Remaining Break Also',['class'=>'']) }} <br/>
    <div class="switch">
        <label>
            {{ Form::hidden('include_break', 0) }}
            {{ Form::checkbox('include_break', 1,  old('include_breakhours')) }}<span class="lever switch-col-blue"></span>
        </label>
    </div>
</div>   
<div class="modal-footer">
{!! Form::button('Save', array('class'=>'btn btn-success save', 'type' => 'submit')) !!}
<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
</div>
{{ Form::close() }}
