<div class="form-body">
     <b>{!! Form::label('Subject') !!}</b>
        {!! Form::text('emailparams[subject]', old('emailparams.subject'), ['class' => 'form-control emailparams']) !!}
    <div class="row p-t-20">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::textarea('value', old('value'), ['class' => 'form-control birthday_email','rows'=>5, 'id' => 'birthday_email']) }}
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
                selector: "#birthday_email",
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
                                        text: 'employee.short_name',
                                        onclick: function () {
                                            editor.insertContent("{employee.short_name}");
                                        }
                                    },
                                    {
                                        text: 'employee.designation_name',
                                        onclick: function () {
                                            editor.insertContent("{employee.designation_name}");
                                        }
                                    },
                                    {
                                        text: 'employee.age',
                                        onclick: function () {
                                            editor.insertContent("{employee.age}");
                                        }
                                    }, {
                                        text: 'employee.tomorrow date',
                                        onclick: function () {
                                            editor.insertContent("{employee.tomorrow}");
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
                            },
                            {
                                text: 'Other Fields',
                                menu: [
                                    {
                                        text: 'other.current_Month_Year',
                                        onclick: function () {
                                            editor.insertContent("{other.current_Month_Year}");
                                        }
                                    },
                                    {
                                        text: 'other.current_Year',
                                        onclick: function () {
                                            editor.insertContent("{other.current_Year}");
                                        }
                                    }
                                ]
                            },
                            {
                                text: 'Payslip Fields',
                                menu: [
                                    {
                                        text: 'payslip.basic_pay',
                                        onclick: function () {
                                            editor.insertContent("{payslip.basic_pay}");
                                        }
                                    },
                                    {
                                        text: 'payslip.dearness_allowance',
                                        onclick: function () {
                                            editor.insertContent("{payslip.dearness_allowance}");
                                        }
                                    },
                                    {
                                        text: 'payslip.house_rent_allowance',
                                        onclick: function () {
                                            editor.insertContent("{payslip.house_rent_allowance}");
                                        }
                                    },
                                    {
                                        text: 'payslip.epf',
                                        onclick: function () {
                                            editor.insertContent("{payslip.epf}");
                                        }
                                    },
                                    {
                                        text: 'payslip.esi',
                                        onclick: function () {
                                            editor.insertContent("{payslip.esi}");
                                        }
                                    },
                                    {
                                        text: 'payslip.special_allowance',
                                        onclick: function () {
                                            editor.insertContent("{payslip.special_allowance}");
                                        }
                                    },
                                    {
                                        text: 'payslip.leaves',
                                        onclick: function () {
                                            editor.insertContent("{payslip.leaves}");
                                        }
                                    },
                                    {
                                        text: 'payslip.total_deduction',
                                        onclick: function () {
                                            editor.insertContent("{payslip.total_deduction}");
                                        }
                                    },
                                    {
                                        text: 'payslip.net_pay',
                                        onclick: function () {
                                            editor.insertContent("{payslip.net_pay}");
                                        }
                                    },
                                    {
                                        text: 'payslip.gross_pay',
                                        onclick: function () {
                                            editor.insertContent("{payslip.gross_pay}");
                                        }
                                    },
                                    {
                                        text: 'payslip.tds',
                                        onclick: function () {
                                            editor.insertContent("{payslip.tds}");
                                        }
                                    },
                                ]
                            },
                        ]
                    });
                },
            });
        });
    </script>
@endpush
