<div class="form-body">
    <h3 class="card-title">Grade</h3>
    <hr>
    
    <div class="row p-t-20">
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('name','Name*',['class'=>'']) }}
                {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('level','Level*',['class'=>'']) }}
                {{ Form::text('level', old('level'), ['class' => 'form-control']) }}
                <div class="help-block">You can set from 0 to 10.</div>
            </div>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
    </div>
</div>