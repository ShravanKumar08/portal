<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('name', 'Name*', ['class' => '']) }}
                {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {{ Form::label('active','Active',['class'=>'']) }} <br/>
            <div class="switch">
                <label>
                    {{ Form::hidden('active', 0) }}
                    {{ Form::checkbox('active', 1,  @$Model->active) }}<span class="lever switch-col-blue"></span>
                </label>
            </div>
        </div>
    </div> 
    <div class="form-actions">
        {!! Form::hidden('id', @$Model->id) !!}
        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
        <button type="reset" class="btn btn-inverse">Reset</button>
    </div>
</div>