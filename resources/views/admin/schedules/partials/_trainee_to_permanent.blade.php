<div class="form-body">
      <div class="row p-t-20">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('model_id', 'Trainee') !!}
                {!! Form::select('model_id', $trainees, null, ['class' => 'form-control', 'placeholder' => 'Select']) !!}
            </div>
        </div>
    </div>
</div>
<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>

