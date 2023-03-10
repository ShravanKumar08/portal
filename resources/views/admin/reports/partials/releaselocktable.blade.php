<table border='0' cellspacing='15' cellpadding='15' width='100%'>
        <tr>
            <th>S.No</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Action</th>
        </tr>
        @forelse($reportitems as $reportitem)    
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $reportitem->start }}</td>
                <td>{{ $reportitem->end }}</td>
                <td>
                    <div class="checkbox">
                        <label>     
                            {{ Form::hidden('release_request['.$reportitem->id.']', null) }}
                            {{ Form::checkbox('release_request['.$reportitem->id.']', 1, $reportitem->release_request, ['data-toggle' => 'toggle', 'class' => 'toggle_values' ]) }}
                        </label>
                    </div>
                </td>
            </tr>            
        @empty
        <tr class="text-center">
            <td colspan="5">No records found</td>
        </tr>
        @endforelse    
    </table>