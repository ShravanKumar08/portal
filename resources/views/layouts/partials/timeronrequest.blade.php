@if (@$report_date)
    <div class="form-group">
        {{ Form::hidden('rep_date', 'rep_date') }}
        {{ Form::label('date', 'Date *', ['class' => '']) }}
        {{ Form::select('date',@$report_date, old('date'), ['class' => 'form-control select2','placeholder' => 'Select Date']) }}
    </div>
@endif
<div class="form-group">
    {{ Form::label('start', 'Start Time *', ['class' => '']) }}
    {{ Form::text('start', @$Reportitem->start ? \AppHelper::formatTimestring(@$Reportitem->start, 'H:i') : '',
    ['class' => 'form-control form-control-line clockpicker', 'autocomplete' => 'off',
    'data-autoclose'=>"true"]) }}
    <span class="font-13 text-muted">HH:mm</span>
</div>
<div class="form-group">
    {{ Form::label('reason', 'Reason *', ['class' => '']) }}
    {{ Form::textarea('reason', old('reason'), ['class' => 'form-control form-control-line','rows' => 4]) }}
</div>
<div class="form-actions text-center">
    <button type="submit" class="btn btn-success" onclick="this.disabled=true;this.form.submit();"><i class="fa fa-check"></i> Send Request</button>
    <button type="reset" class="btn btn-inverse">Reset</button>
</div>