@component('mail::message')
<h4> Dear Admin </h4>
<p><b>{{ $leave->employee->name }}</b> has applied Leave request due to {{ $leave->reason }} from {{ Carbon\Carbon::parse($leave->start)->format('d-m-Y') }} to {{ Carbon\Carbon::parse($leave->end)->format('d-m-Y') }} for {{ $leave->days }} days.</p>

@component('mail::button', ['url' => 'http://portal.arkinfotec.in/admin/leave' ])
Approve/Decline
@endcomponent

@endcomponent