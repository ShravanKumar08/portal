@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('assets/plugins/multiselect/css/multi-select.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('assets/plugins/multiselect/js/jquery.multi-select.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.quicksearch.js') }}"></script>
    {{--<script src="{{ mix('/js/multi-select.js') }}"></script>--}}

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                $('.searchablemultiselect').each(function () {
                    $(this).searchableMultiselect();
                });
            });
        </script>
    @endpush
@endpush
