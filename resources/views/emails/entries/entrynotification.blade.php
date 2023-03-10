@component('mail::message')
<h4> Dear {{$entry->employee->name}} </h4>
<p> Your Entry Status on  {{ Carbon\Carbon::parse($entry->date)->format('d-m-Y') }} is {{ $entry->statusname }}.</p>
@endcomponent