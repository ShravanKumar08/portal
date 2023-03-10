<?php
    use Illuminate\Support\Str;
?>
<div class="form-body">
    @foreach(['permission', 'leave','halfday_leave'] as $mode)
        <div class="row p-t-20">
            <div class="col-md-12">
                <h3>{{ Str::title($mode) }}</h3>
            </div>
            @for($i = 1; $i <= 5; $i++)
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="switch">
                            {{ Form::label("value[{$mode}][$i][value]", $numberFormatter->format($i)." week", ['class'=>'']) }}
                            <label>
                                {{ Form::hidden("value[{$mode}][$i][value]", 0) }}
                                {{ Form::checkbox("value[{$mode}][$i][value]", 1,  @$Model->value[$mode][$i]['value']) }}<span
                                        class="lever switch-col-blue"></span>
                            </label>
                        </div>
                        {{ Form::select("value[{$mode}][$i][dayOfWeek]", \App\Helpers\AppHelper::getWeekDays(), @$Model->value[$mode][$i]['dayOfWeek'], ['class' => 'form-control']) }}
                    </div>
                </div>
            @endfor
        </div>
    @endforeach
<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
</div>

@push('stylesheets')
@endpush

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
        });
    </script>
@endpush
