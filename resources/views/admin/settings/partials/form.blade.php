@if($Model->name == 'THEME_COLOR')
    @include('layouts.partials.theme_color')
@elseif($Model->isMailsetting)
    @include('admin.settings.partials._mail_setup')
@elseif($Model->isEmailTemplate)
    @include('admin.settings.partials._email_template')
    <div class="form-actions">
            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
            <button type="reset" class="btn btn-inverse">Reset</button>
            <a href="" type="button" class="btn btn-warning pull-right"data-toggle="modal" data-target="#emailid_popup">Preview</a>
     </div>
@elseif($Model->name == 'OFFICIAL_PERMISSION_LEAVE_DAYS')
    @include('admin.settings.partials._permission_saturdays')
@elseif($Model->name == 'CAMSUNIT_AUTH_TOKEN')
    @include('admin.settings.partials._camsunit_form')
@elseif($Model->name == 'LATE_ENTRY_COUNT')
    @include('admin.settings.partials._late_entry_setup')
@elseif($Model->name == 'PAYSLIP_CALCULATIONS')
    @include('admin.settings.partials._payslip_calculation_setup')
@else
    <div class="form-body">
        <div class="row p-t-20">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('value', 'Value *', ['class' => '']) }}
                    @if($Model->fieldtype == 'multiselect')
                        {{ Form::select('value[]', $selectvalues, old('value'), ['class' => 'form-control searchablemultiselect', 'multiple' => true]) }}
                    @elseif($Model->fieldtype == 'file')
                        <input type="file" class="dropify" name="value" data-height="350"
                               @if($Model->value)data-default-file="{{ $Model->value }}"@endif/>
                    @elseif($Model->fieldtype == 'textarea')
                        @include('admin.settings.partials._textarea')
                       
                    @else
                        {{ Form::text('value', old('value'), ['class' => 'form-control']) }}
                    @endif
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                    <button type="reset" class="btn btn-inverse">Reset</button>
                    <a href="" type="button" class="btn btn-warning pull-right"data-toggle="modal" data-target="#emailid_popup">Preview</a>
                 </div>
            </div>
        </div>
    </div>
@endif


@if($Model->fieldtype == 'file')
    @push('scripts')
        <link rel="stylesheet" href="{{ asset('assets/plugins/dropify/dist/css/dropify.min.css') }}">

        <script src="{{ asset('assets/plugins/dropify/dist/js/dropify.min.js') }}"></script>

        <script type="text/javascript">
            $(document).ready(function () { 
                $('.dropify').dropify(); 
            });
        </script>
    @endpush

@elseif($Model->fieldtype == 'multiselect')
    @include('layouts.partials.multiselect_scripts')
@endif

@push('scripts')
    <link href="{{ asset('assets/plugins/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/toast-master/js/jquery.toast.js') }}"></script>
    <script src="{{ asset('js/toastr.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#email_preview').submit(function (e) {
                e.preventDefault();
                 $.ajax({
                    url: "{{ route('setting.emailpreview') }}",
                    method: 'POST', 
                    data: {
                        email_id: $('.to_emailid').val(),
                        emailparams: $('.emailparams').val(),
                        email_content: $('.birthday_email').val(),
                    },
                    beforeSend: function() { 
                        $("#prev_submit").html('Sending ...');
                        $("#prev_submit").prop('disabled', true); // disable button
                    },
                    success: function () {
                        $('#emailid_popup').modal('hide');
                        $.toast({
                            heading: 'Send preview mail',
                            text: 'Ready to send',
                            position: 'top-right',
                            loaderBg:'#ff6849',
                            icon: 'success',
                            hideAfter: 3500, 
                            stack: 6
                        });
                    },
                    complete:function(data){
                        $("#prev_submit").html('Send');
                        $("#prev_submit").prop('disabled', false);
                    }
                                
                });
            
            });
        });
    </script>
@endpush
    