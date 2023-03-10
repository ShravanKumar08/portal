<div class="ml-auto fixedhead hide hidden-monthlyReports-options">
        <ul class="list-inline">
            <li>
                <h6 class="text-muted text-success"><i class="fa fa-circle font-15 m-r-15 "></i>Sunday (<span id="countSunday"></span>)</h6> </li>
            <li>
                <h6 class="text-muted  text-warning"><i class="fa fa-circle font-15 m-r-15"></i> Official Holiday ({{ count($holidays) }})</h6> </li>
            <li>
                <h6 class="text-danger"><i class="fa fa-circle font-15 m-r-15"></i> Leave ({{ $leaveitems->sum('days') }})</h6> </li>
            <li>
                <h6 style="color: #ff99cf"><i class="fa fa-circle font-15 m-r-15"></i> Permission ({{ count($permissions) }})</h6> </li>
        </ul>
    </div>
    
    
    <div class="card">
        <div class="card-body">        
            <table width="100%" cell-padding="15" cell-spacing="15" data-toggle="table" data-height="250" data-mobile-responsive="true" class="table table-responsive " id="monthlyDailyItems" style="display: inline-table !important;">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th style="text-align: left">Day</th>
                        <th>Project</th>
                        <th>Total Hours</th>
                        <th>Break Hours</th>
                        <th>Worked Hours</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 1; $i <= $days_in_month; $i++)
                        @php
                            $obj = Carbon\Carbon::create($year, $month, $i);
                            $date = $obj->toDateString();
    
                            $report = $worked->firstWhere('date', $date);
                            $holiday = $holidays->firstWhere('date', $date);
                            $leaveitem = $leaveitems->firstWhere('date', $date);
                            $permission = $permissions->firstWhere('date', $date);
                        @endphp
                        @if($permission)
                            @php @$color = 'permissionColor'; @endphp
                        @elseif($leaveitem)
                            @php @$color = 'leaveColor'; @endphp
                        @elseif($holiday)
                            @php @$color = 'holidayColor'; @endphp
                        @elseif($obj->format('l') == 'Sunday')
                            @php @$color = 'sundayColor'; @endphp
                        @elseif($report)
                            @php @$color = ''; @endphp
                        @else
                            @php @$color = 'norecordColor'; @endphp
                        @endif
                        <tr class="datas {{ @$color }}"> 
               
                @if($permission)
                @if($report)
                <td data-toggle="collapse" class="accordion-toggle mytooltip tooltip-effect-5 textalign" style="cursor:pointer; white-space:nowrap;" onclick="getReport('{{@$report->id}}');"><i class=" iclass{{@$report->id}} fa fa-plus"></i></span>&nbsp;&nbsp;<span class="tooltip-item" style="background: none !important;"> {{ Carbon\Carbon::parse($date)->format('d-m-Y') }} </span>
                        <span class="tooltip-content clearfix">
                            <span class="tooltip-text p-t-10">Start Time : {{ Carbon\Carbon::parse($permission->start)->format('H:i') }} <br/> End Time : {{ Carbon\Carbon::parse($permission->end)->format('H:i') }} <br/> Reason : {{ $permission->reason }}</span> 
                        </span>
                    </td> 
                @endif               
                @elseif($leaveitem)    
                    @if($leaveitem->days == '0.5')
                    @if($report)
                        <td class="mytooltip tooltip-effect-5 textalign"><span class="tooltip-item btn btn-primary" style="background: none !important;cursor:pointer;" onclick="getReport('{{@$report->id}}');"><i class=" iclass{{@$report->id}} fa fa-plus"></i> {{ Carbon\Carbon::parse($date)->format('d-m-Y') }} </span>
                            <span class="tooltip-content clearfix">
                                <span class="tooltip-text p-t-10">Date : {{ Carbon\Carbon::parse($leaveitem->date)->format('d-m-Y') }} <br/> LeaveType : {{ @$leaveitem->leavetype->name }} </span> 
                            </span>
                        </td>
                        @else 
                        <td class="mytooltip tooltip-effect-5 textalign"><span class="tooltip-item" style="background: none"></i> {{ Carbon\Carbon::parse($date)->format('d-m-Y') }} </span>
                            <span class="tooltip-content clearfix">
                                <span class="tooltip-text p-t-10">Date : {{ Carbon\Carbon::parse($leaveitem->date)->format('d-m-Y') }} <br/> LeaveType : {{ @$leaveitem->leavetype->name }} </span> 
                            </span>
                        </td>
                        @endif
                    @else
                        <td class="textalign">{{ Carbon\Carbon::parse($date)->format('d-m-Y') }}</td>
                    @endif
                @else
                @if($report)
                    <td data-toggle="collapse" class="textalign accordion-toggle" style="cursor:pointer;" onclick="getReport('{{@$report->id}}');"><i class=" iclass{{@$report->id}} fa fa-plus"></i></span>&nbsp;&nbsp;{{ Carbon\Carbon::parse($date)->format('d-m-Y') }}</td>
                    @else 
                    <td class="textalign">{{ Carbon\Carbon::parse($date)->format('d-m-Y') }}</td>
                    @endif
                @endif
                
                
                @if($obj->format('l') != 'Sunday')
                    <td style="text-align: left" class="textalign">{{ $obj->format('l') }}</td>
                @else
                <td style="text-align: left" class="textalign" id="counts" colspan='5' class="textalign">{{ 'Sunday' }}</td>
                @endif
                
                
                @if($report)
                    @php 
                    $project_names = $report->reportitems()->whereHas('technology', function($q){
                     $q->where('exclude', 0);
                     })->get()->implode('project.name', ',');
                    @endphp
                    
                    <td style="text-align: left" class="textalign">{{ AppHelper::insertStringAndImplode($report->projectNames) }}</td>
                    <td class="textalign">{{ Carbon\Carbon::parse($report->workedhours)->format('H:i') ?: '-' }}</td>
                    <td class="textalign">{{ Carbon\Carbon::parse($report->breakhours)->format('H:i') ?: '-' }}</td>
                    <td class="textalign">{{ Carbon\Carbon::parse($report->totalhours)->format('H:i') ?: '-' }}</td>
                @elseif($holiday)
                    <td class="textalign" colspan='5'>{{ $holiday->name ?: '-' }}</td>
                @elseif($leaveitem)
                    <td class="textalign" colspan='5'>{{ $leaveitem->leave->reason ? : '-' }}</td>
                @elseif($obj->format('l') != 'Sunday')
                <td class="textalign" colspan='5'>{{ 'No record' }}</td>
                @endif  
            </tr>
            @if($report)
        <tr  class="acc_id{{@$report->id}}">
                <td colspan="6" class="hiddenRow{{@$report->id}} accstyle"><div class="accordian-body collapse reportDetail{{@$report->id}}"></div> </td>
            </tr>
            @endif
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
    