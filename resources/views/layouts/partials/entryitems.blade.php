<table border='0' cellspacing='15' cellpadding='15' width='100%'>
    <thead>
        <th>#</th>
        <th>Start</th>
        <th>End</th>
        <th>In / Out</th>
        <th>Elapsed</th>
    </thead>
    @php
        $colspan = 5;
    @endphp

    <tbody>
    @forelse($items as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->start }}</td>
            <td>{{ $item->end }}</td>
            <td>
                @if($item->inout == 'I')
                    <label class="label label-success">In</label>
                @else
                    <label class="label label-danger">Out</label>
                @endif
            </td>
            <td>{{ $item->elapsed }}</td>
        </tr>
    @empty
        <tr class="text-center">
            <td colspan="{{ $colspan }}">No records found</td>
        </tr>
    @endforelse
    </tbody>

    <tfooter>
        <tr style="border-top: 1px solid #e9ecef">
            <td class="text-right" colspan="{{ $colspan - 1 }}">In Hours</td>
            <td>{{ $entry->total_in_hours }}</td>
        </tr>
        <tr>
            <td class="text-right" colspan="{{ $colspan - 1 }}">Out Hours</td>
            <td>{{ $entry->total_out_hours }}</td>
        </tr>
        <tr>
            <td class="text-right" colspan="{{ $colspan - 1 }}"><h3>Total Hours</h3></td>
            <td><h3>{{ $entry->total_hours }}</h3></td>
        </tr>
    </tfooter>
</table>