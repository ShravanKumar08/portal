<div class="row">
    <div class="col-12">
        <div class="text-right p-b-20">
            <a class="btn btn-sm btn-primary" id="advance-search-btn" data-toggle="collapse"
               href="#collapseAdvanced" role="button" aria-expanded="false" aria-controls="collapseAdvanced">
                {{ $request->show ? 'Hide' : 'Show' }} Advanced Search
            </a>
        </div>

        <div class="collapse {{ $request->show ? 'show' : '' }}" id="collapseAdvanced">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h4 class="m-b-0 text-white"><i class="fa fa-search"></i> Advanced search</h4>
                </div>
                <div class="card-body">
                    {{ Form::open(['url' => \URL::current(), 'class' => 'form-horizontal','method' => 'GET']) }}
                    {{ Form::hidden('show', $request->show) }}
                    <div class="form-body">
                        <div class="row">
                            @if($is_admin_route)
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="">Employee</label>
                                        {{ Form::select('employee_id[]', $employees_list, $request->employee_id, ['class' => 'form-control selectpicker','multiple data-style' => 'form-control btn-secondary']) }}
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="">Month</label>
                                    <div class="input-daterange input-group" id="date-range">
                                        {{ Form::text('month_year', $request->month_year ? $request->month_year : date('Y-m'), ['class' => 'form-control', 'placeholder' => 'Select Month', 'autocomplete' => 'off']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="">&nbsp;</label>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Search</button>
                                    <button type="button" class="btn btn-inverse"
                                            onclick="location.href ='{{ route($is_admin_route ? 'report.monthlyreports.dailyreport' : 'employee.report.monthlyreports.dailyreport') }}'">
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

    </div>
</div>

<div class="row justify-content-md-center">
    @foreach($employees as $emp)
        <div class="col-md-6 col-lg-6 col-xlg-4">
            <div class="card card-body">
                <div class="row">
                    <div class="col-md-4 col-lg-3 text-center">
                        <a target="_blank" href="{{ route('employee.show', $emp->id) }}"><img src="{{ $emp->avatar }}" alt="user"
                                                                                              class="img-circle img-responsive"></a>
                    </div>
                    <div class="col-md-8 col-lg-6">
                        <h6>{{ $request->month_year ? Carbon\Carbon::parse($request->month_year)->format('M Y') : date('M Y') }}</h6>
                        <h3 class="box-title m-b-0">{{ $emp->name }} </h3>
                        <small>{{ $emp->designation->name }}</small>
                        

                    </div>
                    <div class="col-md-8 col-lg-3">
                        <button type="button" data-toggle="modal" data-user="admin" data-employee_id="{{ $emp->id }}" data-employee_name="{{ $emp->name }}" data-month_year="{{ $request->month_year }}" data-target="#dailyModal" class="btn waves-effect waves-light btn-rounded btn-info">View</button>
                    </div>
                </div>
                <div class="row justify-content-md-center">
                    <div class="col-lg-8">
                        <div class="flot-chart">
                            <div class="flot-chart-content flot-pie-chart" data-chart="{{ $emp->getDailyReportChartData(Carbon\Carbon::parse($request->month_year)->year, Carbon\Carbon::parse($request->month_year)->month) }}"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@if(method_exists($employees, 'links'))
<div class="row">
    <div class="col-lg-12">
        {{ $employees->appends(request()->input())->links() }}
    </div>
</div>
@endif

<!--Modal -->
<div class="modal modal-fullwidth" id="dailyModal"tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4><span class="empl-name"> </span>'s Monthly Reports in
                    @if(!$request->month_year)
                        {{ date('M Y')}}
                    @else
                        <span id="selectedmonthlyreport"></span>
                    @endif
                </h4>
            <div class="ml-auto show-monthlyReports-options"></div>
            </div>
            <div class="modal-body" style="overflow: scroll;"></div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal"> Close </button>
                </div>
        </div>
    </div>
</div>

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('/css/datatable.css') }}">
    <link href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css" rel="stylesheet">
    <link href="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />   
@endpush 


@push('stylesheets')
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400" rel="stylesheet">
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/bootstrap-table/dist/bootstrap-table.min.css') }}" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .permissionColor {
            background-color: #ff99cf;
        }        
        .norecordColor {
            background-color: #bfbfbf;
            text-align: center;
        }        
        .sundayColor {
            background-color: #B4FF80;
        }        
        .holidayColor {
            background-color: #ffff80;
        }        
        .leaveColor {
            background-color: #ff8080;
        }        
        .fixedhead {
            position: fixed;
            background-color: white;
            top: 70px;
            left: 3px;
            right: 28px;
            z-index: 3;
        }
/*        .fixedthead {
            position: fixed;
            background-color: white;
            z-index: 3;
            display:table;
            table-layout: fixed;
            border-collapse: collapse;
            width: 96%;
        }*/
        .mytooltip {
            display: table-cell !important;
            z-index: 2 !important;
        }
        .textalign {
            text-align: center;
        }
        
        /* monthly table         */
        #monthlyDailyItems  tbody {
            height: 100px;       /* Just for the demo          */
            overflow-y: auto;    /* Trigger vertical scroll    */
            overflow-x: hidden;  /* Hide the horizontal scroll */
        }
        
        #monthlyDailyItems table {
             overflow-y: auto;
        }

    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/flot/excanvas.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.time.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.stack.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.crosshair.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot.tooltip/js/jquery.flot.tooltip.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>      
    <script src="{{ asset('js/custom.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-table/dist/bootstrap-table.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-table/dist/bootstrap-table.ints.js') }}"></script>
     
    <script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
    <script src="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>
    
   
    {{--<script src="js/flot-data.js"></script>--}}


    <script type="text/javascript">
        $(document).ready(function () {
            $('.select2').select2();

            $('#date-range').datepicker({
                format: "yyyy-mm",
                viewMode: "months",
                minViewMode: "months",
                autoclose: true, todayHighlight: true,
            });

            $('.flot-pie-chart').each(function () {
                $.plot($(this), $(this).data('chart'), {
                    series: {
                        pie: {
                            innerRadius: 0.45,
                            show: true
                        }
                    }
                    , grid: {
                        hoverable: true
                    }
                    , color: null
                    , tooltip: true
                    , tooltipOpts: {
                        content: "%s, %p.0%", // show percentages, rounding to 2 decimal places
                        shifts: {
                            x: 20
                            , y: 0
                        }
                        , defaultTheme: false
                    }
                });
            });
            
            $('#dailyModal .modal-body').html($('#loader-content').html());
            
             
            $('#dailyModal').on('shown.bs.modal', function (e) {
                var $relElem = $(e.relatedTarget);
                var employee_id = $relElem.data('employee_id');
                var employee_name = $relElem.data('employee_name');
                $('.empl-name').html(employee_name);
                var month_year = $relElem.data('month_year');
                var d=new Date(month_year);  //converts the string into date object
                month =  moment(d.getMonth()+1, 'MM').format('MMM') + ' ' + d.getFullYear();
                $('#selectedmonthlyreport').text(month);
                $.ajax({
                    method: 'POST',
                    url: "{{ $is_admin_route ? route('report.monthlyreports.getmonthlyreportitems') : route('employee.report.monthlyreports.getmonthlyreportitems')}}",
                    data: { 'employee_id': employee_id , 'month_year': month_year},
                    beforeSend: function () {
                        $(this).button('loading')
                    },
                    complete: function () {
                        $(this).button('reset')
                    },
                    success: function (html) {
                        $("#dailyModal .modal-body").html(html);
                        $("#dailyModal .modal-header .show-monthlyReports-options").html($('.hidden-monthlyReports-options').html());
                        var count = $('#monthlyDailyItems tr.datas td#counts').length;
                        $('#countSunday').text(count);
                        //$('[data-toggle="table"]').bootstrapTable();
                    },
                    error: function (jqXhr) {
                        swalError(jqXhr);
                    }
                });
            }).on('hidden.bs.modal', function () {
                $('#dailyModal .modal-body').html($('#loader-content').html());
            });
            
            $('#collapseAdvanced').on('hidden.bs.collapse', function () {
                $('#advance-search-btn').html('Show Advanced Search');
                $('input[name="show"]').val('');
            }).on('shown.bs.collapse', function () {
                $('#advance-search-btn').html('Hide Advanced Search');
                $('input[name="show"]').val(1);
            });
        });

        function getReport(reportId)
        {
            if($('.iclass'+reportId).hasClass("fa-minus") == true) {
                $('.iclass'+reportId).removeClass('fa-minus');
                $('.iclass'+reportId).addClass('fa-plus');
                $('.hiddenRow'+reportId).addClass('accstyle');
                $('.reportDetail'+reportId).collapse('toggle');
                return;
            }
            if(reportId != '') {                
                $('.hiddenRow'+reportId).removeClass('accstyle');
                $('.reportDetail'+reportId).collapse('toggle');
                
                $.ajax({
                    type: 'get',
                    url: '{{ url("admin/report") }}/' + reportId,
                    success: function (html) {
                    $('.iclass'+reportId).removeClass('fa-plus');
                    $('.iclass'+reportId).addClass('fa-minus');

                    $('.reportDetail'+reportId).html(html);
                    $(".hidecard").css("display","none");
                    $("#copy-button").css("display","none");
                    }
                });
            }

        }
    </script>
@endpush