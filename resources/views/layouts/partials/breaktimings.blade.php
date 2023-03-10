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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="">Employee</label>
                                        {{ Form::select('employee_id[]', $employees_list, $request->employee_id, ['class' => 'form-control selectpicker','multiple data-style' => 'form-control btn-secondary']) }}
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="">Month</label>
                                    <div class="input-daterange input-group" id="date-range">
                                        {{ Form::text('month_year', $request->month_year ? $request->month_year : date('Y-m'), ['class' => 'form-control', 'placeholder' => 'Select Month', 'autocomplete' => 'off']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="">&nbsp;</label>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Search
                                </button>
                                <button type="button" class="btn btn-inverse"
                                onclick="location.href ='{{ route($is_admin_route ? 'employee.break_timings' : 'employee.report.monthlyreports.breaktimings') }}'">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div> 
                <!-- .left-aside-column-->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered" id='datatable'>
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    @if($is_admin_route)
                                    <th>Name</th>
                                    @endif
                                    <th>Total  Break Hours</th>
                                    <th>Total  Permission Hours</th>
                                    <th>Exceeded  Days</th>
                                    <th>Exceeded  Hours</th>
                                    <th>Unused  Days</th>
                                    <th>Unused  Hours</th>
                                    <th>Action</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $all_employees_lessbreaking_seconds  = 0;
                                    $all_employees_elapsedbreaking_seconds = 0;
                                    $all_employees_breaking_seconds = 0;
                                    $all_employees_permissions = 0;
                                @endphp
                                @forelse($employees as $employee)
                                    @php
                                        $employeeHelper = new \App\Helpers\EmployeeHelper($employee);
                                        $data = $employeeHelper->getEmployeeBreakTiming($start , $end);
                                        $prefix = $is_employee_route ? 'employee.' : '';
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        @if($is_admin_route)
                                        <td>
                                            <a href="{{ route('employee.show', $employee->id) }}" target="_blank" title="{{ $employee->name }}">
                                                {{ $employee->shortname }}
                                            </a>
                                        </td>
                                        @endif
                                        <td>{{ $data['breakhours'] }}</td>
                                        <td>{{ $data['permissionhours'] }}</td> 
                                        @if($data['exceeded_break'] == '0')
                                        <td>{{ $data['exceeded_break'] = '-' }}</td>
                                        @else
                                        <td style='color:red'>{{ $data['exceeded_break']}}</td>    
                                        @endif
                                        <td>{{$data['ExceedBreak']}}</td>
                                        @if($data['less_break'] == '0')
                                        <td>{{ $data['less_break'] = '-' }}</td>
                                        @else
                                        <td style='color:green'>{{ $data['less_break']}}</td>    
                                        @endif
                                        <td>{{$data['lessBreak']}}</td>
                                        <td>
                                        <button class="btn btn-default btn-view {{ App\Helpers\SecurityHelper::hasAccess('employee.monthlybreaks') }}" title="View" data-id="{{ $employee->id }}" data-toggle="modal" data-target="#DatatableViewModal"><i class="fa fa-eye"></i></button>
                                        </td>
                                    </tr>
                                    @php 
                                            // explode H:M in breaking hours and permission hours and elapsedbreak hours
                                            $all_employees_lessbreaking_seconds += AppHelper::getSecondsFromTime($data['lessBreak'].':00');
                                            $all_employees_elapsedbreaking_seconds += AppHelper::getSecondsFromTime($data['ExceedBreak'].':00');
                                            $all_employees_breaking_seconds += AppHelper::getSecondsFromTime($data['breakhours'].':00');
                                            $all_employees_permissions += AppHelper::getSecondsFromTime($data['permissionhours'].':00');
                                    @endphp
                            
                                @empty
                                    <tr class="text-center">
                                        <td colspan="5">No records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- .left-aside-column-->
                </div>
                <!-- /.left-right-aside-column-->
            </div>
        </div>
    </div>
</div>


<div id="DatatableViewModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">View BreakTimings</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body" id="datatableViewContent"></div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
</div>

@push('stylesheets')    
<link rel="stylesheet" href="{{ asset('/css/datatable.css') }}">
<link href="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" /> 
<link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ mix('/js/datatable.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>
<link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>

<script>
    $(document).ready(function () {
        $('.select2').select2();

        $('#date-range').datepicker({
                format: "yyyy-mm",
                viewMode: "months",
                minViewMode: "months",
                autoclose: true, todayHighlight: true,
        });

        $('#datatable').dataTable({
            "pageLength": 100
        });

        $('#DatatableViewModal').on("shown.bs.modal", function (e) {
            var $relElem = $(e.relatedTarget);
         
            $.ajax({
                method: "GET",
                url: '{{ route($is_admin_route ? 'employee.monthlybreaks' : 'employee.report.monthlybreaks') }}', 
                data: {
                    employee_id: $relElem.data('id'),
                    start: '{{ $start }}',
                    end: '{{ $end }}',
                },
                success: function (html) {
                    $('#datatableViewContent').html(html);
                },
                error: function (jqXhr) {
                    $('#DatatableViewModal').modal('hide');
                    swalError(jqXhr);
                }
            });
        }).on("hidden.bs.modal", function (e) {
            $('#DatatableViewModal .modal-body').html($('#loader-content').html());
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
