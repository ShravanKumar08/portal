<link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<script type="text/javascript">
    var LeaveDates;
    var holidays = {!! json_encode($Holidays) !!};
    var $elem_days = $('#days');
    var $elem_halfday = $('#halfday');

    $(document).ready(function () {
        $elem_halfday.closest('.form-group').addClass('hide');

        //For edit
        prepareLeaveDates();

        @if($Model->days)
            $elem_days.val({{ $Model->days }});
            setTimeout(function () {
                prepareHalfDayDates();
                $elem_halfday.val('{{ $Model->halfday }}');
            }, 500);
        @endif
        //End


        //On day count change
        $elem_days.on('change', function () {
            prepareHalfDayDates();
        });

        //Datepickers
        $('#start').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true, todayHighlight: true,
            daysOfWeekDisabled: [0],
            datesDisabled: holidays,
        }).on('changeDate', function (ev) {
            $('#end').datepicker('setStartDate', $("#start").val());
            prepareLeaveDates();
        });

        $('#end').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true, todayHighlight: true,
            daysOfWeekDisabled: [0],
            datesDisabled: holidays,
        }).on('changeDate', function (ev) {
            prepareLeaveDates();
        });

        $('#leaveform').on('submit', function (e) {
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
    });

    //prepare half day leave dates
    function prepareHalfDayDates() {
        var val = $elem_days.val();
        var has_digits = Math.floor(val) != val;
        
        $elem_halfday.empty();
        $elem_halfday.closest('.form-group')[has_digits && (LeaveDates.length > 1) ? 'removeClass' : 'addClass']('hide');

        var last_val;
        $.each(LeaveDates, function (key, val) {
            $elem_halfday.append('<option value="' + val + '">' + val + '</option>');
            last_val = val;          
        });
        
        $elem_halfday.val(last_val);
    }

    //prepare leave dates
    function prepareLeaveDates() {
        LeaveDates = getWorkingDaysCount(new Date($("#start").val()), new Date($("#end").val()));
        var numOfDates = LeaveDates.length;

        // $elem_days.prop('disabled', false);
        $elem_days.empty();

        if (numOfDates) {
            $elem_days.append('<option value="' + numOfDates + '">' + numOfDates + '</option>');
            $elem_days.append('<option value="' + (numOfDates - 0.5) + '">' + (numOfDates - 0.5) + '</option>'); 
        }

        $('#leavedates').val(LeaveDates.join(','));
        $elem_halfday.closest('.form-group').addClass('hide');       
    }

    //get working days count
    function getWorkingDaysCount(startDate, endDate) {
        var dates = [];
        var curDate = startDate;
        while (curDate <= endDate) {
            var dayOfWeek = curDate.getDay();
            var date = moment(curDate).format('YYYY-MM-DD');
            if (!(dayOfWeek == 0) && $.inArray(date, holidays) == -1) {
                dates.push(date);
            }
            curDate.setDate(curDate.getDate() + 1);
        }
        return dates;
    }
</script>
