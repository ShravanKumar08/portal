{{ Form::open(['route' => 'trainee.report.releaselockbreak','method' => 'POST', 'class' => 'form-horizontal','id'=>'submitreleaseForm']) }}
<table class="table color-table success-table color-bordered-table success-bordered-table" border='0'>
    <thead>
    <tr>
        <th>Start</th>
        <th>End</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>

    @forelse (@$releaselocks as $release)
        <tr>
            <td>{{ Carbon\Carbon::parse($release->start)->format('H:i')}}</td>
            <td>{{ $release->end ? Carbon\Carbon::parse($release->end)->format('H:i') : '-' }}</td>
            <td>
                <div class="checkbox">
                    <label>
                        {{ Form::hidden('release_request['.$release->id.']', null) }}
                        {{ Form::hidden('id', $release->report_id ,array('id'=>'report_id')) }}
                        {{ Form::checkbox('release_request['.$release->id.']', 1, $release->lock, ['data-toggle' => 'toggle', 'class' => 'toggle_value' ]) }}
                    </label>
                </div>
            </td>
    @empty
        <tr>
            <td colspan="3" class="text-center">No records</td>
        </tr>
    @endforelse
    </tbody>
</table>
  <div class="form-group">
        {!! Form::label('reason','Reason*') !!}
        {!! Form::textarea('reason',null,['class'=>'form-control','rows' => 4]) !!}
  </div>
<div class="modal-footer">
    {!! Form::button('Request to Relase Lock', array('class'=>'btn btn-success', 'type' => 'submit')) !!}
    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
</div>
{{ Form::close() }}
