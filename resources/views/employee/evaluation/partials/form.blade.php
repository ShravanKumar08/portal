<div class="form-body">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('evaluation','Evaluation form*',['class'=>'']) }}
            <div class="form-body">
                <div class="row p-t-20">
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::textarea('evaluation', old('evaluation'), ['class' => 'form-control textarea','rows'=> 5]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions text-center">
            {!! Form::hidden('status', 1) !!}
            <button type="submit" class="btn btn-success "> <i class="fa fa-check"></i> Complete Evaluation</button>
        </div>
    
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

            // $('#assessment-form').submit(function(e){
            //     e.preventDefault();

            //     swal({
            //         title:"Are you sure ?",
            //         type: "warning",
            //         showCancelButton: true,
            //         confirmButtonColor: "#DD6B55",
            //         confirmButtonText: "Complete Evaluation",
            //         closeOnConfirm: false,
            //         showLoaderOnConfirm: true,
            //     }, function () {
            //         $('#assesmsent-form').unbind().submit();
            //     });
            // });

            tinymce.init({
                selector: ".textarea",
                theme: "modern",
                height: 300,
                menubar:false,
    statusbar: false,
    // toolbar: false
            });
        });
       
    </script>
@endpush