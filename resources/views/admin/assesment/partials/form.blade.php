    <div class="form-body">
        <div class="rowp-t-20">
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('employee_id', '') !!}
                    {{ Form::select('employee_id', $employees,'', ['class' => 'form-control select2', 'id' => 'to-selectbox', 'placeholder' => 'Choose Employee']) }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="">Date Range</label>
                    <div class="input-daterange input-group" id="date-range">
                        {{ Form::text('from','', ['class' => 'form-control', 'placeholder' => 'From', 'autocomplete' => 'off']) }}
                        <span class="input-group-addon bg-info b-0 text-white">to</span>
                        {{ Form::text('to','', ['class' => 'form-control', 'placeholder' => 'To', 'autocomplete' => 'off']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('self','Self Evaluation form*',['class'=>'']) }}
                <div class="form-body">
                    <div class="row p-t-20">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::textarea('self', $contents['SELF_EVALUATION_FORM']['content'], ['class' => 'form-control textarea','rows'=> 5]) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group ">
                {{ Form::label('report_head','Report heads*') }}
                {{ Form::select('report_head[]', $employees, [], ['class' => 'form-control searchablemultiselect', 'multiple' => true]) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('teamleader','Reporthead Evaluation form*',['class'=>'']) }}
                <div class="form-body">
                    <div class="row p-t-20">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::textarea('teamleader', $contents['REPORTHEAD_EVALUATION_FORM']['content'], ['class' => 'form-control textarea','rows'=> 5]) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
    <div class="form-actions">
        {!! Form::hidden('id', @$Model->id) !!}
        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
        <button type="reset" class="btn btn-inverse">Reset</button>
    </div>
</div>

@include('layouts.partials.multiselect_scripts')

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/plugins/html5-editor/bootstrap-wysihtml5.css') }}"/>
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">

    <style>
        .bootstrap-tagsinput {
            width: 100% !important;
        }

        .bootstrap-tagsinput input {
            min-width: 500px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
     <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.select2').select2();

            $('#date-range').datepicker({
                // toggleActive: true,
                format: 'yyyy-mm-dd',
                autoclose: true, todayHighlight: true,
            });

            tinymce.init({
                selector: ".textarea",
                theme: "modern",
                height: 300,
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "save table contextmenu directionality emoticons template paste textcolor"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
            });
        });
    </script>
@endpush