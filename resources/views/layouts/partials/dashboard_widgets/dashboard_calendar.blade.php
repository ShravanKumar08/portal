@php
    $calendar_val = \App\Models\UserSettings::fetch('CALENDAR_LAST_VALUE');

    if($calendar_val){
        $filterEvents = json_decode($calendar_val, true);
        $filter_values = $filterEvents['filters'];
        $DbFilters = implode(',', $filter_values);
    }else{
        $filter_values = [];
        $DbFilters = '';
    }
@endphp

@push('stylesheets')
    <style>
        .fixedhead li{
            padding: 0 3px !important;
        }
    </style>
@endpush

    <div class="card-body b-l calender-sidebar">
        <div id="calendar"></div>
    </div>
    @php
        $role = \Auth::user()->hasRole('admin');
    @endphp

    <!--Event popup -->
    <div id="DatatableViewModal" data-url="" class="modal fade in" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">View</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body"></div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- End popup -->

@push('stylesheets')
    <link href="{{ asset('assets/plugins/calendar/dist/fullcalendar.css') }}" rel="stylesheet"/>
    <style type="text/css">
        .custombg {
            border: none;
            background-color: #FCF7F8;
        }

        .customPadding {
            padding: 0px 0px !important;
        }
    </style>
@endpush
@if(\Auth::user()->hasRole('super-user'))
@php $calendarUrl = route('employee.calendar_events') @endphp
@elseif(\Auth::user()->hasRole('employee'))
@php $calendarUrl = route('employee.calendar_events') @endphp
@else
@php $calendarUrl = route('calendar_events') @endphp
@endif
@push('scripts')
    <script src="{{ asset('assets/plugins/calendar/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/calendar/dist/fullcalendar.min.js') }}"></script>

    {{--Calendar scripts--}}
    <script type="text/javascript">
    var firstLoad = true;
        !function ($) {
            "use strict";

            var CalendarApp = function () {
                this.$body = $("body")
                this.$calendar = $('#calendar'),
                    this.$event = ('#calendar-events div.calendar-events'),
                    this.$categoryForm = $('#add-new-event form'),
                    this.$extEvents = $('#calendar-events'),
                    this.$modal = $('#my-event'),
                    this.$saveCategoryBtn = $('.save-category'),
                    this.$calendarObj = null
            };

            $('body').on('click', '.filter-button', function () {
                $(this).toggleClass('disabled');
                firstLoad = false;
                $('#calendar').fullCalendar('refetchEvents');
            });

            /* Initializing */
            CalendarApp.prototype.init = function () {
                /*  Initialize the calendar  */
                var $this = this;
                $this.$calendarObj = $this.$calendar.fullCalendar({
                    slotDuration: '00:15:00', /* If we want to split day time each 15minutes */
                    minTime: '08:00:00',
                    maxTime: '19:00:00',
                    defaultView: 'month',
                    handleWindowResize: true,

                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    dayRender: function (date, cell) {

                        var today = new Date();
                        if(today.getMonth() > date.get('month')){
                            cell.css("background-color", "#f5f5f5");
                        }

                    },
                    events: function (start, end, timezone, callback) {

                        var filterValue = [];

                        if(firstLoad){
                            //db
                            var initialLoad =  "{{ @$DbFilters }}";
                            var filterValue = initialLoad.split(',');
                        }else{
                        $(".filter-button").each(function () {
                            if (!($(this).hasClass("disabled"))) {
                                var enable_val = $(this).prop('disabled', false).data('filter');
                                filterValue.push(enable_val);
                            }else{
                                filterValue.push('null');
                            }
                        });
                    }
                        $.ajax({
                            method: 'POST',

                            url: "{{$calendarUrl}}",
                            data: {
                                // our hypothetical feed requires UNIX timestamps
                                start: start.unix(),
                                end: end.unix(),
                                filters: filterValue,
                            },
                            success: function (data) {
                                callback(data);
                            },
                            error: function (jqXhr) {
                                swalError(jqXhr);
                            }
                        });
                    },
                    // events: defaultEvents,
                    // editable: true,
                    // droppable: true, // this allows things to be dropped onto the calendar !!!
                    // eventLimit: true, // allow "more" link when too many events
                    // selectable: true,
                    // drop: function(date) { $this.onDrop($(this), date); },
                    select: function (start, end, allDay) {
                        $this.onSelect(start, end, allDay);
                    },
                    eventClick: function (calEvent, jsEvent, view) {
                        if(calEvent.data_url){
                            $('#DatatableViewModal').data('url', calEvent.data_url).modal("show");
                        }
                    },
                    eventRender: function(event, element) {
                        if(event.icon){          
                            element.find(".fc-title").prepend("<i class='"+event.icon+"'></i>&nbsp;");
                        }
                    }, 

                });

                addButtons();

                function addButtons() {
                    // create buttons
                    var role = '{!! $role !!}';
                    var str = '';
                    if (role){
                        str =  "<li><button class='btn btn-warning filter-button {{ in_array('interview_rounds', $filter_values) ? '' : 'disabled' }} ' data-filter='interview_rounds' style='background-color: #000000 !important; border: 1px solid  	#000000;'><i class='fa fa-phone'></i><span> Interview Calls </span></button> </li>";
                    }
                
                    var colorDots = $("<div/>")
                        .addClass("fc-button-agendaDay custombg")
                        .append("<div class='ml-auto fixedhead'><ul class='list-inline'>\n\
                                    <li><button class='btn btn-danger filter-button {{ in_array('leaves', $filter_values) ? '' : 'disabled' }} ' data-filter='leaves' ><i class='mdi mdi-file-document'></i><span> Leave </span></button> </li>\n\
                                    <li><button class='btn btn-success filter-button {{ in_array('holidays', $filter_values) ? '' : 'disabled' }} ' data-filter='holidays' ><i class='mdi mdi-star'></i><span> Holiday </span></button> </li>\n\
                                    <li><button class='btn btn-info filter-button {{ in_array('permissions', $filter_values) ? '' : 'disabled' }} ' data-filter='permissions' ><i class='mdi mdi-clipboard-check'></i><span> Permission </span></button> </li>\n\
                                    <li><button class='btn btn-warning filter-button {{ in_array('late_entries', $filter_values) ? '' : 'disabled' }} ' data-filter='late_entries' ><i class='mdi mdi-alarm-check'></i><span> Late Entry </span></button> </li>\n\
                                    <li><button class='btn btn-primary filter-button {{ in_array('birthdays', $filter_values) ? '' : 'disabled' }} ' data-filter='birthdays' style='background-color: #5c4ac7 !important; border: 1px solid #5c4ac7;'><i class='fa fa-birthday-cake'></i><span> Birthday </span></button> </li>"
                                    +str+
                                    "</ul></div>");

                    // create tr with buttons.
                    // Please note, if you want the buttons to be placed at the center or right,
                    // you will have to append more <td> elements
                    var toolbar = $("<div/>")
                        .addClass("fc-toolbar")
                        .addClass("fc-header-toolbar")
                        .addClass("customPadding")
                        .append(
                            $("<div/>")
                                .addClass("fc-center")
                                .append(colorDots)
                        );
                    toolbar.append($("<div/>", {"class": "fc-clear"}));

                    // insert row before title.
                    $(".fc-header-toolbar").after(toolbar);
                }

                //on new event
                this.$saveCategoryBtn.on('click', function () {
                    var categoryName = $this.$categoryForm.find("input[name='category-name']").val();
                    var categoryColor = $this.$categoryForm.find("select[name='category-color']").val();
                    if (categoryName !== null && categoryName.length != 0) {
                        $this.$extEvents.append('<div class="calendar-events" data-class="bg-' + categoryColor + '" style="position: relative;"><i class="fa fa-circle text-' + categoryColor + '"></i>' + categoryName + '</div>')
                        $this.enableDrag();
                    }

                });
            },

            //init CalendarApp
            $.CalendarApp = new CalendarApp, $.CalendarApp.Constructor = CalendarApp

            $('#DatatableViewModal .modal-body').html($('#loader-content').html());

            $('#DatatableViewModal').on("shown.bs.modal", function (e) {
                $.ajax({
                    method: "GET",
                    url: $(this).data('url'),
                    success: function (html) {
                        $('#DatatableViewModal .modal-body').html(html);
                    },
                    error: function (jqXhr) {
                        $('#DatatableViewModal').modal('hide');
                        swalError(jqXhr);
                    }
                });
            }).on("hidden.bs.modal", function (e) {
                $('#DatatableViewModal .modal-body').html($('#loader-content').html());
            });

        }(window.jQuery),
            //initializing CalendarApp
            function ($) {
                "use strict";
                $.CalendarApp.init()
        }(window.jQuery);
    </script>
@endpush
