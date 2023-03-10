@if($is_admin_route)
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group">
                <strong> Name: </strong>
                {{ $Employee->name }} 
            </div>
        </div>
    </div>
@endif 

<div class="table-responsive">
    <table class="table color-table success-table color-bordered-table success-bordered-table">
        <thead>
        <tr>
            <th>Date</th>
            <th>Break</th>
            <th>Exceeded</th>
            <th>Unused</th>
        </tr>
        </thead>
        <tbody>
            @forelse($entries as $entry)
                <tr>
                    @if($entry->date == '')
                    <td>-</td>
                    @else
                    <td>{{$entry->date}}</td>
                    @endif
                    @if($entry->total_out_hours == '' || $entry->total_out_hours == '0:00')
                    <td>-</td>
                    @else
                    <td>{{$entry->total_out_hours}}</td>
                    @endif
                    @if($entry->exceedBreak == '-')
                    <td>-</td>
                    @else
                    <td><i class="fa fa-check-circle text-danger"></i>{{$entry->exceedBreak}}</td>
                    @endif
                    @if($entry->unusedBreak == '-')
                    <td>-</td>
                    @else
                    <td><i class="fa fa-check-circle text-success"></i>{{$entry->unusedBreak}}</td>
                    @endif
                </tr>
            @empty
                <tr class="text-center">
                    <td colspan="5">No records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
