@component('mail::message')
<h4> Dear {{$report->employee->name}} </h4>
<p>Your Daily Report Status on  {{ Carbon\Carbon::parse($report->date)->format('d-m-Y') }} is {{ $report->statusname }}.</p>
@endcomponent