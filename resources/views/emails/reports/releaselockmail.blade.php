@component('mail::message')
<h4> Dear Admin </h4>
<p><b>{{ $report->employee->name }}</b> has requested to release the break hour timings on {{ \Carbon\Carbon::parse($report->date)->format('d-m-Y') }}.</p>

<p>Timings: </p>
@foreach ($Reportitems as $Reportitem)
    <p>{{ \AppHelper::formatTimestring(@$Reportitem->start, 'H:i') }} to {{ \AppHelper::formatTimestring(@$Reportitem->end, 'H:i') }}</p>
@endforeach
     
<p>Reason : {{ $report->lock_reason }}</p>
    
@endcomponent
