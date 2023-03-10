@component('mail::message')
<h4>Dear {{$lecture->employee->name}},</h4>

<p>{{\Auth::user()->employee->name}} joined in lecture {{$lecture->title}}.</p>
@endcomponent