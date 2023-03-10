{{ Form::open(['route' => 'report.releaserequest', 'class' => 'form-horizontal','method' => 'POST', 'class' => 'form-horizontal', 'id'=>'ReleaserequestForm']) }}

@include('admin.reports.partials.releaselocktable')  

    <div class="modal-footer">
        {!! Form::button('Save', array('class'=>'btn btn-success', 'type' => 'submit')) !!}
        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
    </div>
{{ Form::close() }}
