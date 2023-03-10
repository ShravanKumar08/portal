<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="form-body">
                    @if($Model->id)
                        {{ Form::model($Model,['route' => [ "compensation.update", $Model->id],'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'leaveform']) }}
                    @else
                        {{ Form::open(['route' => 'compensation.store', 'class' => 'form-horizontal', 'id' => 'compensationform']) }}
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
                                    {{ Form::label('date','Compensate Date*',['class'=>'']) }}
                                    {{ Form::text('date', old('date'), ['class' => 'form-control datepicker', 'autocomplete' => 'off','id' => 'start']) }}
                                </div>
                            </div>
                             <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('type','Type*',['class'=>'']) }}
                                    <div class="form-check">
                                        @foreach ($types as $id => $s)
                                            <label class="custom-control custom-radio compens_days">
                                                {!! Form::radio('type', $id, (old('type') == $id), ['class' => 'custom-control-input compens_days'] ); !!} {{$s}}
                                                <span class="custom-control-indicator"></span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 compen_days_class">
                                <div class="form-group">
                                    {{ Form::label('days', 'Choose days*', ['class' => '']) }}
                                    {{ Form::select('days',$days, old('days'),['class' => 'form-control','placeholder' => 'Select Day']) }}
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
    @include('layouts.partials.compensationform_scripts')
@endisset
