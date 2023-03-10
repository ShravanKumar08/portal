<?php
    use Illuminate\Support\Str;
?>
<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-12">
          
            @foreach($Model->value as $key => $mail)
                <div class="form-group">
                    <div class="input-group m-b-30">
                        <span class="input-group-addon">{{ Str::title($key) }}</span>
                        {{ Form::text("value[$key]", $mail, ['class' => 'form-control tagsinput']) }}
                    </div>
                </div>
            @endforeach
            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>

        </div>
    </div>
</div>

@push('stylesheets')
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
    <!-- Tags Input -->
    <link href="{{ asset('assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/typeahead.js-master/dist/typehead-min.css') }}" rel="stylesheet" />

    <script src="{{ asset('assets/plugins/typeahead.js-master/dist/typeahead.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {

            var emails = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                prefetch: {
                    url: "{{ route('setting.searchUserEmail') }}",
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
        });
    </script>
@endpush