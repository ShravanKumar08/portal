
<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-12">
            <div class="col-md-12">
                @foreach($Model->value as $key => $value)
                    <div class="form-group">
                        {{ Form::label("value[title]", $value['title'], ['class' => '']) }}
                        <div class="input-group m-b-30">
                            {{ Form::text("value[$key][value]", $value['value'],  ['class' => 'form-control']) }}
                            {{ Form::hidden("value[$key][title]", $value['title'],  ['class' => 'form-control']) }}
                            {{ Form::hidden("value[$key][type]", $value['type'],  ['class' => 'form-control']) }}
                            <span class="input-group-addon">{{ $value['type'] }}</span>
                        </div>
                    </div>
                @endforeach
                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
            </div>
        </div>
    </div>
</div>
