<div class="form-body">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('name','Team Name*',['class'=>'']) }}
            {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('lead_id','Team Lead*',['class'=>'']) }}
            {{ Form::select('lead_id', $leads, old('lead_id'), ['class' => 'form-control select2','placeholder'=>'Select Employee']) }}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('teammate_id','Team Mates*',['class'=>'']) }}
            {{ Form::select('teammate_id[]', $teamMates, $checkedTeamMates, ['class' => 'form-control selectpicker','multiple data-style' => 'form-control btn-secondary']) }}
        </div>
    </div>
    <div class="form-actions">
        {!! Form::hidden('id', @$Model->id) !!}
        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
        <button type="reset" class="btn btn-inverse">Reset</button>
    </div>
</div>

@push('stylesheets')
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>
        <!--Auto complete Search-->
        <script type="text/javascript">
            $(document).ready(function () {
                $('.select2').select2();
            });
    </script>
@endpush