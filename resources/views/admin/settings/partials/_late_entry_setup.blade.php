<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('value[count]', 'Max. Late Count *', ['class' => '']) }}
                {{ Form::number('value[count]', @$Model->value['count'], ['class' => 'form-control']) }}
                <div class="help-block">Note: After this max. count, late entry will be considered as Half day leave</div>
            </div>
            <div class="form-group">
                {{ Form::label('value[perm_interval]', 'Permission Interval *',['class'=>'']) }} <br/>
                {{ Form::text('value[perm_interval]', @$Model->value['perm_interval'], ['class' => 'form-control']) }}
                <div class="help-block">You can set interval by comma separated. Ex: 3,6,9</div>
            </div>
        </div>
    </div>
</div>
