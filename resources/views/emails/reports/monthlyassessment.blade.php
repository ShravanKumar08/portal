@component('mail::message')
@php
    $now = \Carbon\Carbon::now();
@endphp
<h4> Dear Sir/Madam </h4>
<p class="lead"> Here is Monthly Reports of employees for {{ $now->format('M Y') }}</p>
@php
    $start = $now->startOfMonth()->toDateString();
    $end = $now->endOfMonth()->toDateString();
@endphp
@php
    $monthly_worked_seconds = 0;
    $holidays = App\Models\Holiday::whereBetween('date', [$start, $end])->pluck('date')->toArray();
    for($i = $start; $i <= $end; $i++)
    {
        if(!in_array($i, $holidays) && Setting::isOfficialLeaveToday($i) == false && date("l", strtotime($i)) != "Sunday"){
            $monthly_worked_seconds += ((Setting::isOfficialPermissionToday($i) ? 6 : 9) * 3600);
        }
    }
    $productivity = 0;
    $i = 0;
    $all_employees_working_seconds = 0;
    $all_employees_breaking_seconds = 0;
    $all_employees_permissions = 0;
    $all_employees_leaves = 0;
    $all_employees_late_entries = 0;
@endphp
<p class="worked-hours"><b>From</b> : {{ $start }} <b>To: </b> {{ $end }}</p>

@component('mail::table')
| No.  | Name|Work |Break |Perm. |Leave |Late |Prod. |
| :-------------:|:-------------:|:--------:|:--------:|:--------:|:--------:|:--------:|:--------:|:--------:|
@foreach ($employees as $employee)
@php
    $employeeHelper = new \App\Helpers\EmployeeHelper($employee);
    $data = $employeeHelper->getEmployeeMonthlyAssessment($start, $end);
@endphp
@if($data['workedhours'] != '00:00')
@php
    $i++;
    $productivity_individual = round($data['workedseconds'] / $monthly_worked_seconds * 100,2);
    $productivity += $productivity_individual;
    $all_employees_working_seconds += AppHelper::getSecondsFromTime($data['workedhours'].':00');
    $all_employees_breaking_seconds += AppHelper::getSecondsFromTime($data['breakhours'].':00');
    $all_employees_permissions += $data['permissions'];
    $all_employees_leaves += $data['leaves'];
    $all_employees_late_entries += $data['late_entries'];
@endphp
|{{ $i }}|{{ $employee->shortname }}|{{ $data['workedhours'] }}|{{ $data['breakhours'] }} |{{ $data['permissions'] }} |{{ $data['leaves'] }} |{{ $data['late_entries'] }} |{{$productivity_individual }} % |
@endif
@endforeach
{{ ' ' }}|{{ 'Total' }}|{{AppHelper::secondsToHours($all_employees_working_seconds)}}|{{ AppHelper::secondsToHours($all_employees_breaking_seconds) }} |{{ $all_employees_permissions }} |{{ $all_employees_leaves }} |{{ $all_employees_late_entries }} |{{ round($productivity / $i ,2)}} % |
@endcomponent
@endcomponent
