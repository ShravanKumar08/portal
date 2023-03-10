{{ Form::open( ['url' => ['admin/schedule'],'method' => 'POST', 'class' => 'form-horizontal','id'=>'submitFormBulkChange']) }}
    <div class="form-group">
        {!! Form::label('date','Schedule Date') !!}
        {!! Form::text('date', null, ['class' => 'form-control', 'id' => 'datepicker', 'autocomplete' => 'off']) !!}
        {!! Form::hidden('type', $request->type) !!}
    </div>

    @if($request->type == 'TRAINEE_TO_PERMANENT')
        @php
            $trainees = \App\Models\Employee::query()->trainee()->active()->oldest('name')->pluck('name', 'id');
        @endphp
        <div class="form-group">
            {!! Form::label('model_id', 'Trainee') !!}
            {!! Form::select('model_id', $trainees, null, ['class' => 'form-control', 'placeholder' => 'Select']) !!}
        </div>
    @endif

    @if($request->type == 'OFFICE_TIMING_SLOT')
    @php
        $slot_names = \App\Models\Officetiming::query()->oldest('name')->pluck('name' , 'id');
    @endphp
    <div class="form-group">
        {!! Form::label('slot_id', 'SlotName') !!}
        {!! Form::select('slot_id', $slot_names, null, ['class' => 'form-control', 'placeholder' => 'Select']) !!}
    </div>
    @endif

    <div class="form-actions">
        {!! Form::button('Start', array('class'=>'btn btn-success', 'type' => 'submit')) !!}
        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
    </div>
{{ Form::close() }}