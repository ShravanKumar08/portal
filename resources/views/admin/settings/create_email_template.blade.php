@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Create Email Template</h3>
                    <hr />
                    {{ Form::open(['route' => ["setting.store"],'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) }}
                    <div class="form-body">
                        <div class="row p-t-20">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('name', 'Name*', ['class' => '']) }}
                                    {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
                                </div>
                            </div>
                                {{ Form::hidden('fieldtype', 'text') }}
                        </div>
                        <div class="row p-t-20">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('value', 'Value', ['class' => '']) }}
                                    {{ Form::textarea('value', old('value'), ['class' => 'form-control', 'rows' => '5', 'id' => 'emailTemplate']) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
                            <button type="reset" class="btn btn-inverse">Reset</button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/plugins/html5-editor/bootstrap-wysihtml5.css') }}"/>
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {

            tinymce.init({
                selector: "#emailTemplate",
                theme: "modern",
                height: 300,
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "save table contextmenu directionality emoticons template paste textcolor"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons | employee_variables",
                setup: function (editor) {
                    editor.addButton('employee_variables', {
                        type: 'menubutton',
                        text: 'Employee Variables',
                        icon: false,
                        menu: [
                           {
                                text: 'Employee',
                                menu: [
                                        @foreach(@$Employees as $Employee)
                                    {
                                        text: '{{ $Employee }}',
                                        onclick: function () {
                                            editor.insertContent("{" + "{{ $Employee }}" + "}");
                                        }
                                    },
                                        @endforeach
                                    {
                                        text: 'age',
                                        onclick: function () {
                                            editor.insertContent("{age}");
                                        }
                                    }, {
                                        text: 'tomorrow date',
                                        onclick: function () {
                                            editor.insertContent("{tomorrow}");
                                        }
                                    }
                                ]
                            },
                            {
                                text: 'Custom Fields',
                                menu: [
                                        @foreach(@$EmployeeCustomfields as $EmployeeCustomfield)
                                    {
                                        text: '{{ $EmployeeCustomfield }}',
                                        onclick: function () {
                                            editor.insertContent("{" + "{{ $EmployeeCustomfield }}" + "}");
                                        }
                                    },
                                    @endforeach
                                ]
                            } 
                        ]
                    });
                },
            });
        });
    </script>
@endpush