@php
    $rounds_fields = $round_custom_fields->groupBy('formgroup');
@endphp

@foreach($rounds_fields as $formgroup => $rounds_field)
    {{-- <h3 class="box-title m-t-40">{{ $formgroup }}</h3> --}}
    {{-- <hr> --}}
    @php
        $field_chunks = $rounds_field->chunk(2);
    @endphp

    @foreach($field_chunks as $k => $fields)
            @foreach($fields as $field)
                @php
                    $fieldName = $field->name;
                 
                    $field->name = "roundInf[$key][customfield][$field->name]";
                   
                @endphp
                <div class="col-md-12 {{ $field->field_type == 'hidden' ? 'hide' : '' }}">
                    <div class="form-group">

                        {{ Form::label($field->name, $field->label) }}{{ $field->required ? '*' : '' }}
                        @include("layouts.partials.custom_fields.$field->field_type")
                    </div>
                </div>
                @php
                    $field->name = $fieldName;
                @endphp
            @endforeach
    @endforeach
@endforeach

@push('stylesheets')
    <link href="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" rel="stylesheet">
@endpush

@push('scripts')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

    <!--Auto complete Search-->
    <script type="text/javascript">
        $(document).ready(function () {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true, todayHighlight: true,
            });
            
            $('.datetimepicker').datetimepicker({
                format : '{{ \App\Helpers\CustomfieldHelper::datetimeFormat_JS }}',
                icons: {
                up: "fa fa-chevron-circle-up",
                down: "fa fa-chevron-circle-down",
                next: 'fa fa-chevron-circle-right',
                previous: 'fa fa-chevron-circle-left',
                time: 'fa fa-clock-o',
                date: 'far fa-calendar',
            }
            });

            $('.clockpicker').clockpicker({
                donetext: 'Done',
            });
        });
    </script>
@endpush
