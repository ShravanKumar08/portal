@component('mail::message')
<h4> Dear {{ $report->employee->name }}, </h4>
<p>Your request to release the break hour timings approved successfully!</p>
@endcomponent