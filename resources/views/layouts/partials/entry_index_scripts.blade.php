<div id="AttendanceModal" class="modal fade in" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Attendance on <span id="entry-date"> </span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>



@push('stylesheets')
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.select2').select2();
            
            $('#AttendanceModal .modal-body').html($('#loader-content').html());

            $('#date-range').datepicker({
                toggleActive: true,
                format: 'yyyy-mm-dd',
                autoclose: true, todayHighlight: true,
            });

            $('#AttendanceModal').on('shown.bs.modal', function (e) {
                $this = $(this);
                var $relElem = $(e.relatedTarget);
                $('#entry-date').text($relElem.data('date'));
                $.ajax({
                    method: 'GET',
                    url: "{{$entryurl}}" + '/' + $relElem.data('id'),
                    success: function (html) {
                        $("#AttendanceModal .modal-body").html(html);
                    },
                    error: function (jqXhr) {
                        swalError(jqXhr);
                    }
                });
            }).on('hidden.bs.modal', function () {
                $('#AttendanceModal .modal-body').html($('#loader-content').html());
            });

            $('#collapseAdvanced').on('hidden.bs.collapse', function () {
                $('#advance-search-btn').html('Show Advanced Search');
                $('input[name="show"]').val('');
            }).on('shown.bs.collapse', function () {
                $('#advance-search-btn').html('Hide Advanced Search');
                $('input[name="show"]').val(1);
            });
        });
    </script>
@endpush