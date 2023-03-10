<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="form-body">
                    @if($Model->id)
                        {{ Form::model($Model,['route' => [ "leave.update", $Model->id],'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'leaveform']) }}
                    @else
                        {{ Form::open(['route' => 'leave.store', 'class' => 'form-horizontal', 'id' => 'leaveform']) }}
                    @endif

                    <div class="form-body">
                        @if($Model->id)
                            <div class="row p-t-20">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ Form::label('employee','Employee: ',['class'=>'']) }}
                                        <h4>{{ $Model->employee->name }}</h4>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row p-t-20">
                            {{ Form::hidden('employee_id', $Model->employee_id) }}
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('start','Start Date*',['class'=>'']) }}
                                    {{ Form::text('start', old('start'), ['class' => 'form-control datepicker', 'autocomplete' => 'off','id' => 'start']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('end','End Date*',['class'=>'']) }}
                                    {{ Form::text('end', old('end'), ['class' => 'form-control datepicker', 'autocomplete' => 'off','id' => 'end']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('days', 'Leave Days*', ['class' => '']) }}
                                    {{ Form::select('days',[], old('days'),['class' => 'form-control','id'=>'days','placeholder' => 'Select Days']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('halfday', 'Choose Half day', ['class' => '']) }}
                                    {{ Form::select('halfday',[], old('halfday'),['class' => 'form-control','id'=>'halfday','placeholder' => 'Select Date']) }}
                                </div>
                            </div>
                            {{ Form::hidden('leavedates','',['class' => 'form-control','id' => 'leavedates']) }}
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
                                                {!! Form::radio('status', $id, (old('status') == $id), ['class' => 'custom-control-input'] ); !!} {{$s}}
                                                <span class="custom-control-indicator"></span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                            <button type="reset" class="btn btn-inverse">Reset</button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>

@isset($includeScripts)
    @include('layouts.partials.leaveform_scripts')
@endisset