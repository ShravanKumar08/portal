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
            <th>Permission</th>
            <th>Exceeded</th>
            <th>Unused</th>
        </tr>
        </thead>
        <tbody>
            @forelse($reports as $report )
                @php
                    $org_break = $report->employee->officetiming->value->break_hours.':00';
                    $break_hours = \Carbon\Carbon::parse($report->breakhours)->format('H:i');
                    $permission_hours = \Carbon\Carbon::parse($report->permissionhours)->format('H:i');
                
                @endphp
                <tr>
                    @if($report->date == '')
                        <td>-</td>
                    @else
                        <td>{{$report->date}}</td>
                    @endif
                    @if($report->breakhours == '0:00' ||  $report->breakhours == '' )
                        <td>-</td>
                    @else
                        <td>{{$break_hours}}</td>
                    @endif
                    @if($report->permissionhours == '0:00' ||  $report->permissionhours == '')
                        <td>-</td>
                    @else
                        <td>{{$permission_hours}}</td>
                    @endif
                    @if($report->breakhours > $org_break) 
                        <td><i class="fa fa-check-circle text-danger"></i>
                            ({{ $report->ExceedBreak}})
                        </td>
                    @else
                        <td>-</td>
                    @endif
                    @if($report->breakhours < $org_break) 
                    <td><i class="fa fa-check-circle text-success"></i>
                        ({{ $report->lessBreak}})
                    </td>
                    @else
                        <td>-</td>
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
