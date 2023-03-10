@extends('layouts.master')

@section('content')
<style>
    .nopadding{
        padding-right:0px;
        padding-left:0px;
    }
    .padding-top30{
        padding-top: 30px;
    }
</style>
    <div class="container-fluid"> 
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
                                @if($yearly != 'yearlyLeaves')
                                    <div class="form-group">
                                        <label class="">Month</label>
                                        <div class="input-daterange input-group" id="date-range">
                                            {{ Form::text('month_year', $request->month_year ? $request->month_year : date('Y-m') ,  ['class' => 'form-control', 'placeholder' => 'Select Month', 'autocomplete' => 'off']) }}
                                        </div>
                                    </div>
                                @else
                                     <div class="form-group">
                                        <label class="">Year</label>
                                        <div class="input-daterange input-group" id="year-range">
                                            {{ Form::text('month_year', $request->month_year ? $request->month_year : date('Y') ,  ['class' => 'form-control', 'placeholder' => 'Select Month', 'autocomplete' => 'off']) }}
                                        </div>
                                    </div>
                                @endif
                                </div>
                                <div class="col-md-4">
                                    <label class="">&nbsp;</label>
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Search</button>
                                    @if($yearly != 'yearlyLeaves')    
                                        <button type="button" class="btn btn-inverse"
                                                    onclick="location.href ='{{ route($is_admin_route ? 'report.monthlyreports.leavereport' : 'employee.report.monthlyreports.leavereport') }}'">
                                                Reset
                                            </button>
                                    @else
                                        <button type="button" class="btn btn-inverse"
                                                    onclick="location.href ='{{ route($is_admin_route ? 'report.yearlyreports.leave' : 'employee.report.yearlyreports.leave') }}'">
                                                Reset
                                            </button>
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

        </div>
            
           @foreach($employees as $employee)
                <div class="col-sm-12 col-xlg-6">
                    <div class="card card-body">
                        <div class="row justify-content-md-center">
                            <div class="col-md-4 col-lg-2 text-center"  style="padding-top: 30px;">
                                <a href="{{ route('employee.show', $employee->id) }}" target="_blank"><img src="{{ $employee->avatar }}" alt="user"
                                                 class="img-circle img-responsive"></a>
                            </div>
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-lg-9 padding-top30 text-center">
                                    @if($yearly != 'yearlyLeaves')
                                        <h6>{{ $request->month_year ? Carbon\Carbon::parse($request->month_year)->format('M Y') : date('M Y') }}</h6>
                                    @else
                                        <h6>{{ $request->month_year ?: date('Y') }}</h6>
                                    @endif
                                        <h3 class="box-title m-b-0">{{ $employee->name }} </h3>
                                        <small>{{ $employee->designation->name }}</small>
                                    </div>
                                    <div class="col-lg-3 padding-top30">
                                        @if($yearly != 'yearlyLeaves')
                                        <button type="button" data-toggle="modal" data-employee_id="{{ $employee->id }}" data-employee_name="{{ $employee->name }}" data-month_year="{{ $request->month_year }}" data-target="#leaveModal" class="btn waves-effect waves-light btn-rounded btn-info">View</button>
                                        @else
                                        <button type="button" data-toggle="modal" data-employee_id="{{ $employee->id }}" data-employee_name="{{ $employee->name }}" data-month_year="{{ $request->month_year }}" data-target="#yearlyleaveModal" class="btn waves-effect waves-light btn-rounded btn-info">View</button>
                                        @endif
                                        <input type="hidden" value="{{ $request->month_year }}" style="display:none" id="hidden-month-year">
                                    </div>
                                </div>
                                <div class="row text-center">
                                    @php
                                    $allowed_count = $employee->getAllowedCasualCount($year);
                                    $remain_count = $employee->getRemainingCasualCount($year, $month);
                                    $notused_compensations = $employee->getAvailableCompensationCount($year);
                                    $total_compensation = $employee->getExisingCompensationCount($year);
                                    
                                    if($yearly == 'yearlyLeaves'){
                                        $casual_count = $employee->getCasualCount($year);
                                        $casual_calc_value = 12;
                                        $paid_count = $employee->getPaidCount($year);
                                        $compen_count = $employee->getCompensationCount($year);
                                        $total_calc_value =  $allowed_count ;
                                    }else{
                                        $casual_count = $employee->getCasualCount($year, $month);
                                        $casual_calc_value = 1;
                                        $paid_count = $employee->getPaidCount($year, $month);
                                        $compen_count = $employee->getCompensationCount($year, $month);
                                        $total_calc_value =  1 ;
                                    } 
                                     
                                    $casual_calc = (($casual_count / $casual_calc_value) * 100);
                                    $paid_calc = ($paid_count / 1 ) * 100;
                                    $compen_calc = ($compen_count / 1 ) * 100;
                                    if($total_calc_value != 0){
                                        $total_calc = (($casual_count + $paid_count + $compen_count) / $total_calc_value ) * 100;
                                    }
                                    $total_count = ($casual_count + $paid_count + $compen_count);
                                    
                                    @endphp 
                                     
                                    <div class="col-sm-2">
                                        <div class="chart pie-chart pie-chart-1" data-percent="{{ $casual_calc }}">
                                            <span><big>{{ $casual_count }} </big></span>
                                        </div>
                                        <h6>Casual</h6>
                                     </div><span style="line-height: 140px;"> + </span>
                                     
                                    <div class="col-sm-2">
                                        <div class="chart pie-chart pie-chart-6" data-percent="{{ $paid_calc  }}">
                                            <span>{{ $paid_count }}</span>
                                        </div>
                                        <h6>Loss of Pay</h6>
                                    </div><span style="line-height: 140px;"> + </span>
                                    
                                    <div class="col-sm-2">
                                        <div class="chart pie-chart pie-chart-5" data-percent="{{ $compen_calc }}">
                                            <span>{{ $compen_count }}</span>
                                        </div>
                                        <h6>Compensate</h6>
                                    </div><span style="line-height: 140px;"> = </span>
                                    
                                    <div class="col-sm-2">
                                        <div class="chart pie-chart pie-chart-3" data-percent="{{ $total_calc }}">
                                            <span>{{ $total_count  }}</span>
                                        </div>
                                        <h6>Total</h6>
                                    </div>
                                    
                                    <div class="col-sm-2">
                                        <div class="chart pie-chart pie-chart-2" data-percent="{{ $allowed_count ? (($remain_count / $allowed_count) * 100) : '0' }}">
                                            <span><big>{{ $remain_count + $notused_compensations }} </big><small class="text-muted">/ {{ $allowed_count + $total_compensation }}</small></span>
                                        </div>
                                        <h6>Remaining</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @if(method_exists($employees, 'links'))
            <div class="col-md-8 col-lg-9">
                {{ $employees->appends(request()->input())->links() }}
            </div>
            @endif
        </div>
    </div>

    <!--Leave Modal-->
   <div class="modal fade" id="leaveModal"tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-xl" role="document" style="padding-top: 60px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4><span class="empl-name"> </span>'s Leaves in 
                        @if(!$request->month_year)
                        {{ date('M Y')}}
                        @else
                        <span id="selectedmonthyear"></span>
                        @endif
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" style="overflow: hidden;"></div>
            </div>
        </div>
    </div>
        
        <div class="modal fade" id="yearlyleaveModal"tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-xl" role="document" style="padding-top: 60px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4><span class="empl-name"> </span>'s Leaves in 
                        @if(!$request->month_year)
                        {{ date('Y')}}
                        @else
                        <span id="selectedyear"></span>
                        @endif
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" style="overflow: hidden;"></div>
            </div>
        </div>
    </div>
@endsection

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('/css/datatable.css') }}">
    
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css" rel="stylesheet">
    <link href="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />   
@endpush 

@push('scripts')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>

    <script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
    <script src="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>
    
    <script type="text/javascript">
        $(document).ready(function () {
            $('.select2').select2(); 
            
            $('#date-range').datepicker({
                format: "yyyy-mm",
                viewMode: "months",
                minViewMode: "months",
                autoclose: true, todayHighlight: true,
            });
            
            $('#year-range').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                autoclose: true, todayHighlight: true,
            });

            var mon = $('#hidden-month-year').val();
            var d=new Date(mon); 
            month =  moment(d.getMonth()+1, 'MM').format('MMM') + ' ' + d.getFullYear();
            $('.hidden-monthyear-display').text(month);
            
            $('#leaveModal .modal-body').html($('#loader-content').html());
 
            $('#leaveModal').on('shown.bs.modal', function (e) {
                var $relElem = $(e.relatedTarget);
                var employee_id = $relElem.data('employee_id');
                $('.empl-name').html($relElem.data('employee_name'));
                var month_year = $relElem.data('month_year');
                var d=new Date(month_year);  //converts the string into date object
                month =  moment(d.getMonth()+1, 'MM').format('MMM') + ' ' + d.getFullYear();
                $('#selectedmonthyear').text(month);
                $.ajax({
                    method: 'POST',
                    url: "{{ $is_admin_route ? route('report.monthlyreports.getleaveitems') : route('employee.report.monthlyreports.getleaveitems')}}",
                    data: { 'employee_id': employee_id , 'month_year': month_year},
                    success: function (html) {
                        $("#leaveModal .modal-body").html(html);
                    },
                    error: function (jqXhr) {
                        swalAlert(jqXhr);
                    }
                });
            }).on('hidden.bs.modal', function () {
                $('#leaveModal .modal-body').html($('#loader-content').html());
            });
            
            
            $('#yearlyleaveModal').on('shown.bs.modal', function (e) {
                var $relElem = $(e.relatedTarget);
                var employee_id = $relElem.data('employee_id');
                $('.empl-name').html($relElem.data('employee_name'));
                var year = $relElem.data('month_year');
                $('#selectedyear').text(year);
                $.ajax({
                    method: 'POST',
                    url: "{{ $is_admin_route ? route('report.yearlyreports.getleaveitems') : route('employee.report.yearlyreports.getleaveitems')}}",
                    data: { 'employee_id': employee_id , 'month_year': year, 'yearly': 'yearlyLeaves'},
                    success: function (html) {
                        $("#yearlyleaveModal .modal-body").html(html);
                    },
                    error: function (jqXhr) {
                        swalAlert(jqXhr);
                    }
                });
            }).on('hidden.bs.modal', function () {
                $('#yearlyleaveModal .modal-body').html($('#loader-content').html());
            });

            $('#collapseAdvanced').on('hidden.bs.collapse', function () {
                $('#advance-search-btn').html('Show Advanced Search');
                $('input[name="show"]').val('');
            }).on('shown.bs.collapse', function () {
                $('#advance-search-btn').html('Hide Advanced Search');
                $('input[name="show"]').val(1);
            });

            $('.chart.pie-chart-1').easyPieChart({
                barColor : '#99d683',
                lineWidth: 3,
                scaleColor: false,
            });

            $('.chart.pie-chart-2').easyPieChart({
                barColor : '#13dafe',
                lineWidth: 3,
                scaleColor: false,
            });

            $('.chart.pie-chart-3').easyPieChart({
                barColor : '#00c292',
                lineWidth: 3,
                scaleColor: false,
            });

            $('.chart.pie-chart-4').easyPieChart({
                barColor : '#6164c1',
                lineWidth: 3,
                scaleColor: false,
            });

            $('.chart.pie-chart-5').easyPieChart({
                barColor : '#13dafe',
                lineWidth: 3,
                scaleColor: false,
            });
            
            $('.chart.pie-chart-6').easyPieChart({
                barColor : '#DC143C',
                lineWidth: 3,
                scaleColor: false,
            });
        });
    </script>
@endpush
