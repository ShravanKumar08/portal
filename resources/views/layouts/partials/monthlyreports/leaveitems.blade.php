<?php
    use Illuminate\Support\Str;
?>
<table border='0' cellspacing='1' cellpadding='10' width='100%'>
    <tr>
        <th width="5%">S.No</th>
        <th width="9%">Date</th>
        <th width="9%">Days</th>
        <th width="9%">Leave Type</th>
        <th width="50%">Reason</th>
    </tr>
    @forelse($leaveitems as $leaveitem)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ Carbon\Carbon::parse($leaveitem->date)->format('d-m-Y') }}</td>
            <td>{{ $leaveitem->days }}</td>
            @php
                $type_color_class = AppHelper::getLabelColorByType(@$leaveitem->leavetype->name);
            @endphp
            <td><span class='label label-{{ $type_color_class }}'>{{ Str::title(@$leaveitem->leavetype->name) }}</span></td>
            <td>{!! $leaveitem->leave->reason !!}</td>
    @empty
        <tr class="text-center">
            <td colspan="5">No records found</td>
        </tr>
    @endforelse
</table>