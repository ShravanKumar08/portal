@component('mail::message')
<h4>Dear {{$leave->employee->name}},</h4>
<p>Your {{$leave->days}} days leave request due to ({{$leave->reason }}) on {{$leave->leavedates}} has been {{ $leave->statusname }}.</p>
@endcomponent