@component('mail::message')
<h4> Dear Sir/Madam </h4>
<p><b>{{ $report->employee->name }}</b> has not sent daily Status Report on {{ Carbon\Carbon::parse($report->date)->format('d-m-Y') }}</p>
@endcomponent