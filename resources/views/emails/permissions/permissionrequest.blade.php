@component('mail::message')
<h4>Dear Admin</h4>
<p><b>{{ $permission->employee->name }}</b> has applied Permission request due to {{ $permission->reason }} from {{ $permission->start }} to {{ $permission->end }} on {{ Carbon\Carbon::parse($permission->date)->format('d-m-Y') }}.</p>


@component('mail::button', ['url' => 'http://portal.arkinfotec.in/admin/userpermission' ])
Approve/Decline
@endcomponent

@endcomponent