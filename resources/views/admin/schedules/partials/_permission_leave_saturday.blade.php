<?php
    use Illuminate\Support\Str;
?>
@php
    $values = @$Model->value['value'];
@endphp
@foreach(['permission', 'leave'] as $mode)
    <div class="row p-t-20">
        <div class="col-md-12">
            <h3>{{ Str::title($mode) }}</h3>
        </div>
        @for($i = 1; $i <= 5; $i++)
            <div class="col-md-3">
                <div class="form-group">
                    <div class="switch">
                        {{ Form::label("value[value][{$mode}][$i][value]", $numberFormatter->format($i)." week", ['class'=>'']) }}
                        <label>
                            {{ Form::hidden("value[value][{$mode}][$i][value]", 0) }}
                            {{ Form::checkbox("value[value][{$mode}][$i][value]", 1,@$values[$mode][$i]['value']) }}<span class="lever switch-col-blue"></span>
                        </label>
                    </div>
                    {{ Form::select("value[value][{$mode}][$i][dayOfWeek]", \App\Helpers\AppHelper::getWeekDays(), @$values[$mode][$i]['dayOfWeek'], ['class' => 'form-control']) }}
                </div>
            </div>
        @endfor
    </div>
@endforeach
<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
