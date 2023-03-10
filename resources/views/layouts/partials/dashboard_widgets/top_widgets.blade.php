@php
    $now = Carbon\Carbon::now();

    $year = $now->year;
    $month = $now->month;
    $date = $now->toDateString();

    $empHelper = new \App\Helpers\EmployeeHelper($auth_employee);
    $monthlyData = $empHelper->getEmployeeMonthlyAssessment($now->startOfMonth()->toDateString(), $now->endOfMonth()->toDateString());

    $workedHours = $monthlyData['workedhours'];
    $lateEntries = $monthlyData['late_entries'];
    $Userpermissions = $monthlyData['permissions'];
    $pendingReports = $auth_employee->reports()->whereIn('status', ['P', 'A'])->whereYear('date', $year)->count();

    $worked_calc = ($monthlyData['workedseconds']/ (60 * 60 * 192)) * 100;
    $available_count = $auth_employee->getCompensationCount() + $auth_employee->getAllowedCasualCount();
    $leave_calc = $available_count ? (($auth_employee->getTotalLeaveCount()) / ($available_count) * 100) : 0;
    $permisions_calc = ($Userpermissions / $auth_employee->getAllowedPermissionCount()) * 100;

    $late_count = \App\Models\Setting::fetch('LATE_ENTRY_COUNT')['count'];

    $lectures_count   =  \App\Models\Lecture::withoutGlobalScope(App\Scopes\EmployeeScope::class)->where('date', '>', $date)->count();
@endphp

<div class="card-group">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <a href="{{ route('employee.report.index') }}">
                        <h2 class="m-b-0"><i class="mdi  mdi-clock text-info"></i></h2>
                        <h3 class="">{{ $workedHours }}</h3>
                        <h6 class="card-subtitle">Worked Hours</h6>
                    </a>
                </div>
                <div class="col-12">
                    <div class="progress">

                        <div class="progress-bar bg-info {{ AppHelper::getDashboardWidgetsProgressbar(100 - $worked_calc) }}"
                             role="progressbar" style="width: {{ $worked_calc }}%; height: 6px;" aria-valuenow="25"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <a href="{{ route('employee.userpermission.index') }}">
                        <h2 class="m-b-0"><i class="mdi mdi-briefcase-check text-info"></i></h2>
                        <h3 class=""><big>{{ $Userpermissions }}</big>
                            / {{ $auth_employee->getAllowedPermissionCount() }}</h3>
                        <h6 class="card-subtitle">Permissions</h6></a>
                </div>
                <div class="col-12">
                    <div class="progress">
                        <div class="progress-bar bg-info {{ AppHelper::getDashboardWidgetsProgressbar($permisions_calc) }}"
                             role="progressbar" style="width: {{$permisions_calc}}%; height: 6px;" aria-valuenow="25"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <a href="{{ route('employee.late_entries.index') }}">
                        <h2 class="m-b-0"><i class="mdi mdi-clock-fast text-info"></i></h2>
                        <h3 class=""><big>{{ $lateEntries }}</big> / {{ $late_count }}</h3>
                        <h6 class="card-subtitle">Late Entries</h6></a>
                </div>
                <div class="col-12">
                    <div class="progress">
                        <div class="progress-bar bg-info {{ AppHelper::getDashboardWidgetsProgressbar(($lateEntries / $late_count) * 100) }}"
                             role="progressbar" style="width: {{ ($lateEntries / $late_count) * 100 }}%; height: 6px;"
                             aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <a href="{{ route('employee.leave.index') }}">
                        <h2 class="m-b-0"><i class="mdi  mdi-file-document text-info"></i></h2>
                        <h3 class=""><big>{{ $auth_employee->getTotalLeaveCount() }} </big>
                            / {{  $auth_employee->getExisingCompensationCount($year) + $auth_employee->getAllowedCasualCount() }}
                        </h3>
                        <h6 class="card-subtitle">Total Leaves</h6>
                    </a>
                </div>
                <div class="col-12">
                    <div class="progress">

                        <div class="progress-bar bg-info {{ AppHelper::getDashboardWidgetsProgressbar($leave_calc) }}"
                             role="progressbar" style="width: {{ $leave_calc }}%; height: 6px;" aria-valuenow="25"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($pendingReports)
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <a href="{{ route('employee.report.index').'?scope=pendingApproved' }}">
                        <h2 class="m-b-0"><i class="mdi mdi-library-books text-info"></i></h2>
                        <h3 class=""><big>{{ $pendingReports }}</big></h3>
                        <h6 class="card-subtitle">Pending Reports</h6></a>
                </div>
                <div class="col-12">
                    <div class="progress">
                        <div class="progress-bar bg-info {{ AppHelper::getDashboardWidgetsProgressbar(($pendingReports / 5) * 100) }}"
                             role="progressbar" style="width: {{( $pendingReports / 5) * 100 }}%; height: 6px;"
                             aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
     @if($lectures_count)
         <div class="card">
                <div class="card-body">
                    <div class="col-12">
                        <a href="{{route('employee.lectures.index').'?scope=Others' }}">
                        <h2 class="m-b-0"><i class="mdi mdi-laptop-chromebook text-info"></i></h2>
                        <h3 class=""><big>{{$lectures_count}}</big></h3>
                        <h6 class="card-subtitle">Upcoming Lectures</h6></a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
