<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('name','Title*',['class'=>'']) }}
                {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
            </div>
        </div>
    </div>
    <hr>
    <h3 class="card-title">Employees</h3>
    <div class="row p-t-20">
        <div class="col-md-12">
            <div class="form-group">
                <h5 class="box-title">Select Employees</h5>
                {{ Form::select('employee_id[]', $Employees, @$Employee , ['class' => 'form-control searchablemultiselect', 'multiple' => true]) }}
            </div>
        </div>
    </div>
    <hr>
    <h3 class="card-title">Timing slots</h3>

    <div id="default-slots" class="slots-div {{ @$Model->isAllday ? '' : 'hide' }}">
        <div class="card-subtitle">
            You can choose a slot for all days in the current month OR you can
            <button class="btn btn-primary btn-xs btn-customize-days" type="button" data-target="#custom-slots">
                Customize
            </button>
            for each days
        </div>
        <div class="form-group row">
            {{ Form::label('default_slot', 'Default Slot (All Days)',['class'=>'col-sm-3 col-form-label']) }}
            <div class="col-sm-6">
                {{ Form::select("default_slot", $slots, @$Model->slots[1], ['class' => 'form-control', 'placeholder' => 'Select' , 'id' => 'default_slot']) }}
                @foreach(range(1, 31) as $day)
                    {{ Form::hidden("slots[$day]", @$Model->slots[$day], ['class' => 'custom-slots', 'id' => 'day-'.$day]) }}
                @endforeach
            </div>
        </div>
        <hr>
        <div class="form-actions text-center">
            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
            <button type="reset" class="btn btn-inverse">Reset</button>
        </div>
    </div>
    <div id="custom-slots" class="slots-div {{ @$Model->isAllday ? 'hide' : '' }}">
        <div class="card-subtitle">
            You can choose a slot for each day in the current month OR you can
            <button class="btn btn-primary btn-xs btn-customize-days" type="button" data-target="#default-slots">
                Customize
            </button>
            for All days
        </div>
        <div class="row justify-content-md-center">
            <div class="col-md-10">
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal none-border" id="my-event">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><strong>Add Event</strong></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success save-event waves-effect waves-light">Create event
                </button>
                <button type="button" class="btn btn-danger delete-event waves-effect waves-light"
                        data-dismiss="modal">Delete
                </button>
            </div>
        </div>
    </div>
</div>

@include('layouts.partials.multiselect_scripts')

@push('stylesheets')
    <link href="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/plugins/calendar/dist/fullcalendar.css') }}" rel="stylesheet"/>
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.js') }}"
            type="text/javascript"></script>
    <!--<script src="{{ asset('assets/plugins/calendar/jquery-ui.min.js') }}"></script>-->
    <script src="{{ asset('assets/plugins/calendar/dist/fullcalendar.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            var defaultSlot = $('#default_slot').val();
            if(defaultSlot){
                $('.custom-slots').val(defaultSlot);
            }
            
            $('select[name="default_slot"]').on('change', function () {
            var def_slot = $(this).val();

            if (def_slot) {
                $('.custom-slots').val(def_slot);
            }
            });

            $('.btn-customize-days').on('click', function () {
                $('.slots-div').addClass('hide');
                $($(this).data('target')).removeClass('hide');
                $('#calendar').fullCalendar('render');
            });
        });

        {{--Calendar scripts--}}
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


            /* on drop */
            CalendarApp.prototype.onDrop = function (eventObj, date) {
                var $this = this;
                // retrieve the dropped element's stored Event Object
                var originalEventObject = eventObj.data('eventObject');
                var $categoryClass = eventObj.attr('data-class');
                // we need to copy it, so that multiple events don't have a reference to the same object
                var copiedEventObject = $.extend({}, originalEventObject);
                // assign it the date that was reported
                copiedEventObject.start = date;
                if ($categoryClass)
                    copiedEventObject['className'] = [$categoryClass];
                // render the event on the calendar
                $this.$calendar.fullCalendar('renderEvent', copiedEventObject, true);
                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    eventObj.remove();
                }
            },
                /* on click on event */
                CalendarApp.prototype.onEventClick = function (calEvent, jsEvent, view) {
                    var $this = this;
                    $("#DayslotsModal").modal("show");
                    $('.modal-body #slot').val(calEvent.id);
                    var date = new Date(calEvent.start);
                    $('#cal_chose_date').html('On: ' + moment(date).format('DD/MM/YYYY'));
                    $('#Day').val(date.getDate());
                },
                /* on select */
                CalendarApp.prototype.onSelect = function (start, end, allDay) {
                    var $this = this;
                    var view = $('#calendar').fullCalendar( 'getView' );
                    var start = new Date(start);
                    var end = new Date(end);
                    var yesterdayMs = end.getTime() - 1000*60*60*24*1; // Offset by one day;
                    end.setTime( yesterdayMs );
                       console.log(end);

                    $('#Day').val(start.getDate() + '-' + (end.getDate()));
                    if(start.getMonth() == view.intervalStart.month()) {
                        $('#cal_chose_date').html('From: ' + moment(start).format('DD/MM/YYYY') + ' To: ' + moment(end).format('DD/MM/YYYY'));
                        $("#DayslotsModal").modal("show");                        
                    }
                    $this.$calendarObj.fullCalendar('unselect');
                },
                $('body').on('submit', '#DayslotsModal #submitForm', function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    $.ajax({
                        method: $this.attr('method'),
                        url: $this.attr('action'),
                        data: $this.serialize(),
                        success: function (data) {
                            $this[0].reset();
                            swal("Saved!", "Dayslot saved Successfully", "success");
                            $('#DayslotsModal').modal('hide');
                            $('#calendar').fullCalendar( 'refetchEvents');
                            $('#day-' + data.day).val(data.slot);
                        },
                        error: function (jqXhr) {
                            swalError(jqXhr);
                        }
                    });
                });
            CalendarApp.prototype.enableDrag = function () {
                //init events
                $(this.$event).each(function () {
                    // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                    // it doesn't need to have a start or end
                    var eventObject = {
                        title: $.trim($(this).text()) // use the element's text as the event title
                    };
                    // store the Event Object in the DOM element so we can get to it later
                    $(this).data('eventObject', eventObject);
                    // make the event draggable using jQuery UI
                    $(this).draggable({
                        zIndex: 999,
                        revert: true,      // will cause the event to go back to its
                        revertDuration: 0  //  original position after the drag
                    });
                });
            }
            /* Initializing */
            CalendarApp.prototype.init = function () {
                /*  Initialize the calendar  */
                var $this = this;
                $this.$calendarObj = $this.$calendar.fullCalendar({
                    slotDuration: '00:15:00', /* If we want to split day time each 15minutes */
                    // minTime: '08:00:00',
                    // maxTime: '19:00:00',
                    defaultView: 'month',
                    handleWindowResize: true,
                    header: {
                        left: '',
                        center: 'title',
                        right: ''
                    },
                    events: function (start, end, timezone, callback) {
                        $.ajax({
                            url: '{{ route('officetiming.slot_events') }}',
                            method: 'POST',
                            // dataType: 'xml',
                            data: {
                                // our hypothetical feed requires UNIX timestamps
                                start: start.unix(),
                                end: end.unix(),
                                officetiming_id: "{{ @$Model->id }}",
                            },
                            success: function (events) {
                                callback(events);
                            }
                        });
                    },
                    eventRender: function(event, element) {
                        element.attr("style", "color: "+ event.text_color +" !important; background-color: "+ event.bg_color +"");
                    },
                    // events: defaultEvents,
                     editable: false,
                    // droppable: true, // this allows things to be dropped onto the calendar !!!
                    // eventLimit: true, // allow "more" link when too many events
                    selectable: true,
                    // drop: function(date) { $this.onDrop($(this), date); },
                    select: function (start, end, allDay) {
                        $this.onSelect(start, end, allDay);
                    },
                    dayClick: function(date, allDay, jsEvent, view) {
                        $('#calendar').fullCalendar('clientEvents', function(event) {
                          // match the event date with clicked date if true render clicked date events
                          if (moment(date).format('YYYY-MM-DD') == moment(event._start).format('YYYY-MM-DD')) {
                            $('.modal-body #slot').val(event.id);
                          }
                        });
                    },
                    eventClick: function (calEvent, jsEvent, view) {
                        $this.onEventClick(calEvent, jsEvent, view);
                    }

                });

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

        }(window.jQuery),

            //initializing CalendarApp
            function ($) {
                "use strict";
                $.CalendarApp.init()
            }(window.jQuery);

    </script>
@endpush