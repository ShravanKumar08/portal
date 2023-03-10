@php
    $reportitems = \App\Models\ReportItem::where('project_id', $request->id)
    ->whereHas('report',function($q) use ($request){
        if($empl_id = $request->emp_id){
            $q->whereIn('employee_id', explode(',', $empl_id));
        }
        if($request->filtertype == 'M'){
            list($year, $month) = explode('-', $request->month_year);
            $q->monthYear('date', $year, $month);
        }else{
            $q->whereBetween('date', [$request->from_date, $request->to_date]);
        }
    })->get();
@endphp
<div class="table-responsive">
    <table class="table color-table success-table color-bordered-table success-bordered-table" id="items">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Date</th>
            <th>Summary</th>
            <th>Time</th>
            <th>Elapsed</th>
{{--            <th>Notes</th>--}}
        </tr>
        </thead>
        <tbody>
        @forelse ($reportitems as $reportitem)
            <tr>
                <td>{{ $loop->iteration }}</td>
               <td>{{ $reportitem->report->employee->shortname }}</td>
                <td>{{ $reportitem->report->date }}</td>
                <td>{{ $reportitem->works }}</td>
                <td>{{ \Carbon\Carbon::parse(@$reportitem->start)->format('H:i')  }} to {{ @$reportitem->end ? \Carbon\Carbon::parse(@$reportitem->end)->format('H:i') : '-' }} </td>
                <td>{{ $reportitem->getElapsedTime() ?: '-' }}</td>
{{--                <td>{{ $reportitem->notes }}</td>--}}
            </tr>
        @empty
            <tr class="text-center">
                <td colspan="5">No data found</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
    
    
    
