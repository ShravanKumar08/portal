@component('mail::message')
<h4> Dear Sir/Madam </h4>
<p class="lead"> Here is my ({{ $report->employee->name }}) Daily Status Report on {{ Carbon\Carbon::parse($report->date)->format('d-m-Y') }}</p>
<p class="worked-hours"><b>Worked Hours</b> : {{  $report->workedhours }}</p>
<p class="Break-hours"><b>Break Hours</b> : {{  $report->breakhours }}</p>

@component('mail::table')
<style>
    table{
        width:800px;
    }
    .align{
        width:200px; 
        height:30px;
    }
</style>
<table>
    <tr>
        <th style="width:50px" >No.</th>
        <th class="align" >Project</th>
        <th class="align" >Time.</th>
        <th class="align" >Summary</th>
        <th class="align" >Status</th>
    </tr>
    @foreach($Reportitems as $Reportitem)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ @$Reportitem->project->name ?: '-' }}</td>
        <td>{{ \AppHelper::formatTimestring(@$Reportitem->start, 'H:i') }} - {{ \AppHelper::formatTimestring(@$Reportitem->end, 'H:i') }}</td>
        <td>{{ preg_replace( "/\r|\n/", " ", $Reportitem->works ) }}</td>
        <td>{{ $Reportitem->status_name ?: '-' }} </td>
    </tr>
    @endforeach 
</table>
@endcomponent

<center data-parsed="">
    <table align="center" class="menu float-center thank-you">
        <tr>
            <td>
                <table>
                    <tr>
                        <th class="menu-item float-center">
                            Thank you
                        </th>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</center>
@endcomponent