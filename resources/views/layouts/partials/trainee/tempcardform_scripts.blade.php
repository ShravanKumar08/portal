    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <!--Auto complete Search-->
    <script type="text/javascript">
        $(document).ready(function () {
            var holidays = {!! json_encode($Holidays) !!};
            $('#permissionform').on('submit', function (e) {
            e.preventDefault();

            $this = $(this);

            $.ajax({
                url: $this.attr('action'),
                method: $this.attr('method'),
                data: $this.serialize(),
                beforeSend: function(){
                    $this.find(':submit').buttonLoading();
                },
                complete: function(){
                    $this.find(':submit').buttonReset();
                },
                success: function (result) {
                    window.location.href = result.redirect;
                },
                error: function (jqXhr) {
                    swalError(jqXhr);
                }
            });
        });
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true, todayHighlight: true,
                daysOfWeekDisabled: [0],
                datesDisabled: holidays,
            });

            $('.clockpicker').clockpicker({
                donetext: 'Done',
            });
        });
    </script>