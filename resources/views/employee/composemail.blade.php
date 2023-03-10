@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="row">
                    <div class="col-xlg-12 col-lg-12 col-md-12 bg-light-part b-l">
                        <div class="card-body">
                            <h3 class="card-title">Compose New Message</h3>
                            {{ Form::open(['route' => [ "employee.composemail"], 'method' => 'POST', 'class' => 'form-horizontal','files' => true]) }}
                                    <div class="form-group">
                                        {{ Form::text('to', '', ['class' => 'form-control tagsinput', 'placeholder' => 'To:']) }}
                                    </div>
                                    <div class="form-group">
                                        {{ Form::text('subject', '', ['class' => 'form-control', 'placeholder' => 'Subject:']) }}
                                    </div>
<!--                                    <div class="form-group">
                                        {{ Form::select('email_template', $email_templates, '', ['class' => 'form-control', 'id' => 'emailTemplate', 'placeholder'=>'Select Email Template']) }}
                                    </div>-->
                                    <div class="form-group">
                                        {{ Form::textarea('content', '', ['class' => 'form-control textarea_editor','rows'=> 15]) }}
                                    </div>
                                    <div class="form-group">
                                        <h4><i class="ti-link"></i> Attachment</h4> <br/>
                                        <div id="image-upload" class="dropzone">
                                            {{ csrf_field() }}
                                        </div>
                                    </div>
                                    <div id="uploadedfile"></div>
                                    <button type="submit" class="btn btn-success m-t-20"><i class="fa fa-envelope-o"></i> Send</button>
                                    <button type="reset" class="btn btn-inverse m-t-20"><i class="fa fa-times"></i> Discard</button>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('stylesheets')
    <!--wysihtml-->
    <link rel="stylesheet" href="{{ asset('assets/plugins/html5-editor/bootstrap-wysihtml5.css') }}" />    
    <!-- Tags Input -->
    <link href="{{ asset('assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/typeahead.js-master/dist/typehead-min.css') }}" rel="stylesheet" />
    <!-- Dropzone -->
    <link href="{{ asset('assets/plugins/dropzone-master/dist/dropzone.css') }}" rel="stylesheet" type="text/css" />
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
    <!--wysihtml-->
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>    

    <script src="{{ asset('assets/plugins/typeahead.js-master/dist/typeahead.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <!--Dropzone-->
    <script src="{{ asset('assets/plugins/dropzone-master/dist/dropzone.js') }}"></script>
    <script type="text/javascript">
        Dropzone.autoDiscover = false;
        $(document).ready(function() {
            tinymce.init({
                selector: ".textarea_editor",
                theme: "modern",
                height: 300,
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "save table contextmenu directionality emoticons template paste textcolor"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons | employee_variables",
            });
            
            $("#emailTemplate").change(function() {
                var value = $(this).val(); 
                tinyMCE.activeEditor.setContent(value);
            });
            
            var emails = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                prefetch: {
                    url: "{{ route('employee.searchUserEmail') }}",
                    filter: function(lists) {
                        return $.map(lists, function(list) {
                            return { name: list.email }; });
                    }
                }
            });
            emails.initialize();

            $('.tagsinput').tagsinput({
                typeaheadjs: {
                    name: 'emails',
                    displayKey: 'name',
                    valueKey: 'name',
                    source: emails.ttAdapter()
                }
            });
                        
            var myDropzone = new Dropzone("div#image-upload", {
                url: "{{route('employee.composefileupload')}}",                
            });
            Dropzone.options.imageUpload = {
                method: 'POST',
                maxFilesize: 1,
                addRemoveLinks: true,
                dictRemoveFile: 'Remove',
            };
            myDropzone.on("sending", function(file, xhr, formData) {

                // Now, find your CSRF token
                var token = $("input[name='_token']").val();

                // Append the token to the formData Dropzone is going to POST
                formData.append('_token', token);
            });
            myDropzone.on("success", function(file, response) {
                 $("#uploadedfile").append($('<input type="hidden" ' + 'name="composefile[]" ' + 'value="' + response.filename + '">'));
            });
            myDropzone.on("addedfile", function(file) {
                // Create the remove button
                var removeButton = Dropzone.createElement("<a href='#' class='btn btn-danger'><i class='fa fa-trash'></i></a>");


                // Capture the Dropzone instance as closure.
                var _this = this;

                // Listen to the click event
                removeButton.addEventListener("click", function(e) {
                    var data = $.parseJSON(file.xhr.response);
                    // Make sure the button click doesn't submit the form:
                    e.preventDefault();
                    e.stopPropagation();

                    // Remove the file preview.
                    _this.removeFile(file);
                    
                    $.ajax({
                        url: "{{ route('employee.composeFileDelete') }}",
                        method: 'post',
                        data: {filename: data.filename},
                        success: function (data) {
                            $('input[value="' + data.filename + '"]').remove();
                        }
                    });
                    // If you want to the delete the file on the server as well,
                    // you can do the AJAX request here.
                });
                
                

                // Add the button to the file preview element.
                file.previewElement.appendChild(removeButton);
            });
            
        });
    </script>
@endpush