@component('mail::message')
<h4> Dear Sir/Madam </h4>
<p>{{$entry->employee->name}}'s Entry Status on  {{ Carbon\Carbon::parse($entry->date)->format('d-m-Y') }} is {{ $entry->statusname }}.</p>
@endcomponent