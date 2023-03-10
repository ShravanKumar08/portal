<div class="form-body">
    @if(@$Model->id)
        <div class="row p-t-20">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('employee','Employee: ',['class'=>'']) }}
                    <h4>{{ @$Model->employee->name }}</h4>
                </div>
            </div>
        </div>
    @endif

    {{ Form::hidden('employee_id', @$Model->employee_id) }}
    {{ Form::hidden('model_id', @$Model->id, ['class' => 'model_id']) }}

    <div class="row p-t-20">        
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('date','Date*',['class'=>'']) }}
                {{ Form::text('date', @$leave ? @$leave->start : old('date'), ['class' => 'form-control datepicker', 'autocomplete' => 'off']) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('start','Start Time*',['class'=>'']) }}                
                {{ Form::text('start', @$start ? @$start : old('date'), ['class' => 'form-control clockpicker', 'autocomplete' => 'off','data-autoclose'=>"true"]) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('end', 'End Time*', ['class' => '']) }}                
                {{ Form::text('end', @$end ? @$end : old('date'), ['class' => 'form-control clockpicker', 'autocomplete' => 'off','data-autoclose'=>"true"]) }}
            </div>
        </div>
    </div>
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('reason', 'Reason*', ['class' => '']) }}
                {{ Form::textarea('reason', old('reason'), ['class' => 'form-control','rows' => 4]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('remarks', 'Remarks', ['class' => '']) }}
                {{ Form::textarea('remarks', old('remarks'), ['class' => 'form-control','rows' => 4]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('status','Status*',['class'=>'']) }}
                <div class="form-check">
                    @foreach ($status as $id => $s)
                    <label class="custom-control custom-radio">
                        {!! Form::radio('status', $id, ((@$Status ? $Status : old('status')) == $id), ['class' => 'custom-control-input'] ); !!} {{$s}}
                        <span class="custom-control-indicator"></span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        @if($Model->employee->getAvailablePermissionCompensationCount())
            <div class="row p-t-20">
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('compensation','Do you want to take compensation permission',['class'=>'']) }} <br/>
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
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="reset" class="btn btn-inverse">Reset</button>
    </div>
</div>
