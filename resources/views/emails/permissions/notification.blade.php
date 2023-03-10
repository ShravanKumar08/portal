@component('mail::message')
<h4>Dear {{$permission->employee->name}},</h4>
<p>Your Permission Request on  {{ Carbon\Carbon::parse($permission->date)->format('d-m-Y') }} (From {{$permission->start}} to {{$permission->end}}) is {{ $permission->statusname }}.</p>
@endcomponent