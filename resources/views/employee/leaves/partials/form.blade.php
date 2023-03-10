<div class="form-body">
    <div class="row p-t-20">        
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('start','Start Date*',['class'=>'']) }}
                {{ Form::text('start', old('start'), ['class' => 'form-control datepicker', 'autocomplete' => 'off','id' => 'start']) }}
            </div>
        </div>   
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('end','End Date*',['class'=>'']) }}
                {{ Form::text('end', old('end'), ['class' => 'form-control datepicker', 'autocomplete' => 'off','id' => 'end']) }}
            </div>
        </div>
    </div>
    <div class="row p-t-20">        
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('days', 'Leave Days*', ['class' => '']) }}
                {{ Form::select('days',[], '',['class' => 'form-control','id'=>'days','placeholder' => 'Select Days']) }}
            </div>
            <div class="form-group">
                {{ Form::label('halfday', 'Choose Half day', ['class' => '']) }}
                {{ Form::select('halfday',[], old('halfday'),['class' => 'form-control','id'=>'halfday','placeholder' => 'Select Date']) }}
            </div>
        </div>
        {{ Form::hidden('leavedates','',['class' => 'form-control','id' => 'leavedates']) }}
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('reason', 'Reason*', ['class' => '']) }}
                {{ Form::textarea('reason', old('reason'), ['class' => 'form-control','rows' => 4]) }}
            </div>
        </div>
    </div>
    @if($auth_employee->getAvailableCompensationCount())
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('compensation','Do you want to take compensation leave',['class'=>'']) }} <br/>
                <div class="switch">
                    <label>
                        {{ Form::hidden('compensate', 0) }}
                        {{ Form::checkbox('compensate', 1 ,  '') }}<span class="lever switch-col-blue"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="form-actions">
        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
        <button type="reset" class="btn btn-inverse">Reset</button>
    </div>
</div>

@push('scripts')
@include('layouts.partials.leaveform_scripts')
@endpush
