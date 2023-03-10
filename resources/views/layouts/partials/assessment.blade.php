@php
    $monthly_worked_seconds = 0;
    $holidays = App\Models\Holiday::whereBetween('date', [$start, $end])->pluck('date')->toArray();

    for($i = $start; $i <= $end; $i++)
    {
        if(!in_array($i, $holidays) && Setting::isOfficialLeaveToday($i) == false && date("l", strtotime($i)) != "Sunday"){
            $monthly_worked_seconds += ((Setting::isOfficialPermissionToday($i) ? 6 : 9) * 3600);
        }
    }
@endphp
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="">Exclude employee</label>
                                        {{ Form::select('exclude_employee_id[]', $employees_list, $request->exclude_employee_id, ['class' => 'form-control selectpicker','multiple data-style' => 'form-control btn-secondary']) }}
                                        
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
                            @if($is_admin_route)
                                <div class="col-md-3 text-center">
                                    <label for="checkbox3">Show Inactive</label>
                                    <div class="form-group pt-2">
                                        {{ Form::checkbox('inactive_employee', 1,$request->inactive_employee, ['class' => 'checkbox checkbox-success','id' => 'checkbox3']) }}
                                        <label for="checkbox3"></label>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <label class="">&nbsp;</label>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Search
                                </button>
                                <button type="button" class="btn btn-inverse"
                                        onclick="location.href ='{{ route($is_admin_route ? 'report.monthlyreports.assessment' : 'employee.report.monthlyreports.assessment') }}'">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <div id="over_all_productivity_top">
                    </div>
                    <table border='0' cellspacing='1' cellpadding='10' width='100%'
                           class="display nowrap table table-hover table-striped table-bordered" id="datatable">
                        <thead>
                        <tr>
                            <th width="5%">S.No</th>
                            @if($is_admin_route)
                            <th width="50%">Name</th>
                            @endif
                            <th width="10%">Worked <br/> Hours</th>
                            <th width="10%">Break <br/> Hours</th>
                            <th width="10%">Permissions</th>
                            <th width="10%">Leaves</th>
                            <th width="10%">Late entries</th>
                            <th width="10%">Productivity</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php
                                $productivity = 0;
                                $i =0;
                                $all_employees_working_seconds = 0;
                                $all_employees_breaking_seconds = 0;
                                $all_employees_permissions = 0;
                                $all_employees_leaves = 0;
                                $all_employees_late_entries = 0;
                            @endphp
                        @forelse($employees as $employee)
                            @php
                                $employeeHelper = new \App\Helpers\EmployeeHelper($employee);
                                $data = $employeeHelper->getEmployeeMonthlyAssessment($start, $end);
                                $prefix = $is_employee_route ? 'employee.' : '';
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                @if($is_admin_route)
                                <td>
                                    <a href="{{ route('employee.show', $employee->id) }}"          target="_blank" title="{{ $employee->name }}">
                                        {{ $employee->shortname }}
                                    </a>
                                </td>
                                @endif
                                <td>{{ $data['workedhours'] }}</td>
                                <td>{{ $data['breakhours'] }}</td>
                                <td>
                                    <a href="{{ route($prefix.'userpermission.index').'?employee_id='.$employee->id.'&from_date='.$start.'&to_date='.$end }}">
                                        {{ $data['permissions'] }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route($prefix.'leave.index').'?employee_id='.$employee->id.'&from_date='.$start.'&to_date='.$end }}">
                                        {{ $data['leaves'] }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route($prefix.'late_entries.index').'?employee_id='.$employee->id.'&from_date='.$start.'&to_date='.$end }}">
                                    {{ $data['late_entries'] }}
                                    </a>
                                </td>
                                <td>
                                    {{ $productivity_individual = round($data['workedseconds'] / $monthly_worked_seconds * 100,2)}} %
                                </td>
                            </tr>
                            @php 
                                if($data['workedhours'] > 0)
                                {
                                    $productivity += $productivity_individual;
                                    $i++;
                                    // explode H:M in working hours
                                    $all_employees_working_seconds += AppHelper::getSecondsFromTime($data['workedhours'].':00');

                                    // explode H:M in breaking hours
                                    $all_employees_breaking_seconds += AppHelper::getSecondsFromTime($data['breakhours'].':00');

                                    $all_employees_permissions += $data['permissions'];
                                    $all_employees_leaves += $data['leaves'];
                                    $all_employees_late_entries += $data['late_entries'];
                                }
                            @endphp
                        @empty
                            <tr class="text-center">
                                <td colspan="5">No records found</td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($is_admin_route)
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-right"><b>Total</b></td>
                                    <td><b>{{ AppHelper::secondsToHours($all_employees_working_seconds) }}</b></td>
                                    <td><b>{{ AppHelper::secondsToHours($all_employees_breaking_seconds) }}</b></td>
                                    <td><b>{{$all_employees_permissions}}</b></td>
                                    <td><b>{{$all_employees_leaves}}</b></td>
                                    <td><b>{{$all_employees_late_entries}}</b></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('/css/datatable.css') }}">
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css" rel="stylesheet">
    <link href="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />   
@endpush

@push('scripts')
    <script src="{{ mix('/js/datatable.js') }}"></script>
    {{--<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>--}}
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
    <script src="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>

    <script type="text/javascript">
        $('.select2').select2();
        $(document).ready(function () {
            $('#date-range').datepicker({
                format: "yyyy-mm",
                viewMode: "months",
                minViewMode: "months",
                autoclose: true, todayHighlight: true,
            });

            $('#datatable').dataTable({
                fixedHeader: {
                    header: true,
                    headerOffset: $('.navbar.top-navbar').outerHeight(),
                },
                "bPaginate": false,
                'pageLength': 50,
                'dom': 'lBfrtip',
                'buttons': [
                    {
                        extend: 'collection',
                        text: '<i class="fa fa-download"></i> Export',
                        buttons: [
                            {
                                extend: 'csvHtml5',
                                text: '<i class="fa fa-file-excel-o"></i> CSV',
                            },
                            {
                                extend: 'pdfHtml5',
                                text: '<i class="fa fa-file-pdf-o"></i> PDF',
                            },
                        ]
                    },
                ]
            });

            $('#collapseAdvanced').on('hidden.bs.collapse', function () {
                $('#advance-search-btn').html('Show Advanced Search');
                $('input[name="show"]').val('');
            }).on('shown.bs.collapse', function () {
                $('#advance-search-btn').html('Hide Advanced Search');
                $('input[name="show"]').val(1);
            });
            @if($i != 1 && $productivity)
             $( "#over_all_productivity_top" ).append( "<b><label>Cumulative productivity :</label></b> {{ round($productivity / $i ,2) }} %<br/>" );
             @endif
             $( "#over_all_productivity_top" ).append( "<b><label>Baseline Working Hours :</label></b> {{ $monthly_worked_seconds/3600 }} hours" );
        });
    </script>
@endpush
