    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('name', 'Name*', ['class' => '']) }}
                {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('email', 'Email', ['class' => '']) }}
                {{ Form::text('email', old('email'), ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('phone', 'Phone', ['class' => '']) }}
                {{ Form::text('phone', old('phone'), ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('location', 'Location', ['class' => '']) }}
                {{ Form::text('location', old('location'), ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('remarks', 'Remarks', ['class' => '']) }}
                {{ Form::textarea('remarks', old('remarks'), ['class' => 'form-control', 'rows' => '5', 'id' => 'emailTemplate']) }}
            </div>
        </div>
        <div class="col-md-6">
            @include('layouts.partials.custom_fields_form')
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
        <button type="reset" class="btn btn-inverse">Reset</button>
    </div>
</div>