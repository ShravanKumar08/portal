<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('value[token]', 'Auth Token *', ['class' => '']) }}
                {{ Form::text('value[token]', @$Model->value['token'], ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('value[status]', 'Status',['class'=>'']) }} <br/>
                <div class="switch">
                    <label>
                        {{ Form::hidden('value[status]', 0) }}
                        {{ Form::checkbox('value[status]', 1,  @$Model->value['status']) }}<span class="lever switch-col-blue"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
