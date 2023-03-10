<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('name','Name*',['class'=>'']) }}
                {{ Form::text('name', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('exclude','Exclude',['class'=>'']) }}<span> (Exclude from work hours calculations for reports)</span>
                <div class="switch">
                    <label>
                        {{ Form::hidden('exclude', 0) }}
                        {{ Form::checkbox('exclude', 1) }}<span class="lever switch-col-blue"></span>
                    </label>
                </div>
            </div>
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
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
    </div>
</div>