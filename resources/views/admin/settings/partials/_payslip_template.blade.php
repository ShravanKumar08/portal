<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::textarea('value', old('value'), ['class' => 'form-control','rows'=>5, 'id' => 'payslip_email']) }}
            </div>
        </div>
    </div>
</div>

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/plugins/html5-editor/bootstrap-wysihtml5.css') }}"/>
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {

            tinymce.init({
                selector: "#payslip_email",
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
                                        text: 'employee.{{ $Employee }}',
                                        onclick: function () {
                                            editor.insertContent("{" + "employee.{{ $Employee }}" + "}");
                                        }
                                    },
                                        @endforeach
                                    {
                                        text: 'employee.age',
                                        onclick: function () {
                                            editor.insertContent("{employee.age}");
                                        }
                                    }, {
                                        text: 'employee.month',
                                        onclick: function () {
                                            editor.insertContent("{employee.month}");
                                        }
                                    }
                                ]
                            },
                            {
                                text: 'Custom Fields',
                                menu: [
                                        @foreach(@$EmployeeCustomfields as $EmployeeCustomfield)
                                    {
                                        text: 'employee.{{ $EmployeeCustomfield }}',
                                        onclick: function () {
                                            editor.insertContent("{" + "employee.{{ $EmployeeCustomfield }}" + "}");
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