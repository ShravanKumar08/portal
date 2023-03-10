@component('mail::message')
<h4> Dear Sir/Madam </h4>
<p><b>{{ $entry->employee->name }}</b> has not stopped their timer, on {{ Carbon\Carbon::parse($entry->date)->format('d-m-Y') }}</p>
@endcomponent