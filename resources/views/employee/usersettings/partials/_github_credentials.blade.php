<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('username', 'Username*', ['class' => '']) }}
                {{ Form::text('value[username]', @$value->username, ['class' => 'form-control', 'autocomplete' => 'off']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('personaltoken', 'Personal Access Token*', ['class' => '']) }}
                {{ Form::text('value[personalaccesstoken]', @$value->personalaccesstoken, ['class' => 'form-control', 'autocomplete' => 'off']) }}
                {{ Form::hidden('name') }}
            </div>
            <p><b>Note : </b>Choose <b>"Developer settings"</b> from <b>"Settings"</b> of your Github Account, click <b>"Personal access tokens"</b> there you could generate your new token.</p><br>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('showinreport', 'Show Git commits button', ['class' => '']) }}
                <div class="switch">
                    <label>
                        {{ Form::hidden('value[showinreport]', 0) }}
                        {{ Form::checkbox('value[showinreport]', 1,  @$value->showinreport) }}<span class="lever switch-col-blue"></span>
                    </label>
                </div>
                <p><b>Note : </b>This will enable git commit buttons in the report</p><br>
            </div>
        </div>
    </div>
</div>