@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="row">
                    <div class="col-xlg-12 col-lg-12 col-md-12 bg-light-part b-l">
                        <div class="card-body">
                            <h3 class="card-title text-center">Generate New Payslip</h3>
                            <div class="row">
                                <div class="col-md-4">
                                    {{ Form::open(['route' => "setting.calculatepayslip",'method' => 'POST', 'class' => 'form-horizontal', 'id' =>'payslip_form_1']) }}
                                        <div class="form-group">
                                            {!! Form::label('month', 'Employee') !!}
                                            {{ Form::text('month', $current_month, ['class' => 'form-control monthpicker', 'id' => 'month-picker', 'placeholder' => 'Select month']) }}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('toemployee', 'Employee') !!}
                                            {{ Form::select('toemployee', $employees, '', ['class' => 'form-control select2', 'id' => 'to-selectbox', 'placeholder' => 'Choose Employee']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('gross_pay', 'Gross Pay*', ['class' => '']) }}
                                            {{ Form::text('gross_pay','', ['class' => 'form-control']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('lop', 'LOP', ['class' => '']) }}
                                            {{ Form::text('lop','', ['class' => 'form-control']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('tds', 'TDS', ['class' => '']) }}
                                            {{ Form::text('tds','', ['class' => 'form-control']) }}
                                        </div>
                                        <div class="col text-center">
                                            <button type="submit" class="btn btn-primary" id="btn-calculate" ><i class="fa fa-calculator"></i> Calculate</button>
                                        </div>
                                    {{ Form::close() }}
                                </div>
                                <div class="col-md-8" id = "calculated_payslip">
                                    @include('admin.settings.partials.generate_payslip')
                                </div>
                            </div>
                            {{ Form::open(['route' => "setting.generate_payslip",'method' => 'POST', 'class' => 'form-horizontal', 'id' =>'payslip_form_3']) }}

                            <div id="mail-content"></div>
                            <div class="col text-center">
                                <button type="submit" class="btn btn-success" id="btn-submit"><i class="fa fa-envelope-o"></i> Send</button>
                            </div>
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
    <link rel="stylesheet" href="{{ asset('assets/plugins/html5-editor/bootstrap-wysihtml5.css') }}"/>

    <style>
        .bootstrap-tagsinput {
            width: 100% !important;
        }

        .bootstrap-tagsinput input {
            min-width: 500px;
        }
    </style>

    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <!--wysihtml-->
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.select2').select2();
            $('#btn-submit').hide();

            $(".monthpicker").datepicker( {
                format: "mm-yyyy",
                viewMode: "months", 
                minViewMode: "months"
            });
            
        // $('#to-selectbox').change(function (e) {
         
        $('body').on('submit','#payslip_form_1', function (e) {
            e.preventDefault();
            $this = $(this);

            $.ajax({
                method: $this.attr('method'),
                url: $this.attr('action'),
                data: $this.serialize(),
                beforeSend: function () {
                    $this.find(':submit').buttonLoading();
                },
                complete: function () {
                    $this.find(':submit').buttonReset();
                },
                success: function (data) {
                    $("#calculated_payslip").html(data);
                },
                error: function (jqXhr) {
                    swalError(jqXhr);
                }
            });
        });

        $('body').on('submit','#payslip_form_2', function (e) {
            e.preventDefault();
            $this = $(this);

            $.ajax({
                method: $this.attr('method'),
                url: $this.attr('action'),
                data: $this.serialize(),
                beforeSend: function () {
                    $this.find(':submit').buttonLoading();
                },
                complete: function () {
                    $this.find(':submit').buttonReset();
                },
                success: function (data) {
                    tinymce.EditorManager.editors = [];
                    $('#btn-submit').show();

                    $('#mail-content').html(data);
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
                },
                error: function (jqXhr) {
                    swalError(jqXhr);
                }
            });
        });

        $('#payslip_form_3').on('submit', function (e) {
            e.preventDefault();
            $this = $(this);
            
            $('#mail_content').val( tinymce.get('mail_content').getContent() );
            $('#pdf_content').val( tinymce.get('pdf_content').getContent() );

            $.ajax({
                method: $this.attr('method'),
                url: $this.attr('action'),
                data: $this.serialize(),
                beforeSend: function () {
                    $this.find(':submit').buttonLoading();
                },
                complete: function () {
                    $this.find(':submit').buttonReset();
                },
                success: function (data) {
                    $('#mail-content').html('');
                    $('select[name="toemployee"]').val('').trigger('change');
                    swal("Done!", "Payslip Sent.", "success");},
                error: function (jqXhr) {
                    swalError(jqXhr);
                }
            });
        });
    });
    </script>
@endpush
