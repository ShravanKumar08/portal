<div class="table-responsive">
    <table class="table color-table success-table color-bordered-table success-bordered-table" id="items">
        <thead>
        <tr>
            <th>#</th>
            <th>Project</th>
            <th>Category</th>
            <th>Summary</th>
            <th>Time</th>
            <th>Elapsed</th>
            <th>Status</th>
{{--            @if(!$action)--}}
{{--                <th>Notes</th>--}}
{{--            @endif--}}
            @if($action)
                <th>Action</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @forelse ($Reportitems as $Reportitem)
            @php
                $exclude = @$Reportitem->technology->exclude;
            @endphp
            <tr data-index="{{ $loop->iteration }}" data-position="{{$Reportitem->id}}">
                <td class="order">{{ $loop->iteration }}</td>
                

                @if($exclude)
                    <td>-</td>
                    <td>{{ @$Reportitem->technology->name }}</td>
                    <td>-</td>
                @else
                    <td>{{ @$Reportitem->project->name }}</td>
                    <td>{{ @$Reportitem->technology->name }}</td>
                    <td>{{ $Reportitem->works }}</td>
                @endif

                <td data-name="start">{{ \Carbon\Carbon::parse(@$Reportitem->start)->format('H:i')  }} to {{ @$Reportitem->end ? \Carbon\Carbon::parse(@$Reportitem->end)->format('H:i') : '-' }} </td>
                <td>{{ $Reportitem->getElapsedTime() ?: '-' }}</td>

                @if($exclude)
                    <td>-</td>
{{--                    @if(!$action)--}}
{{--                        <td>-</td>--}}
{{--                    @endif--}}
                @else
                    <td>{{ $Reportitem->status_name }}</td>
{{--                    @if(!$action)--}}
{{--                        <td>{{ $Reportitem->notes }}</td>--}}
{{--                    @endif--}}
                @endif

                {{--X-Editables use if you need--}}
                {{--<td><a href="javascript:void(0)" data-name = 'project' class="editdata" data-pk="{{ $Reportitem->id }}" data-type="text" data-placement="right" data-title="Edit Report">{{$Reportitem->project->name}}</a></td>--}}
                {{--<td><a href="javascript:void(0)" data-name = 'technology_id' class="edittech" data-prepend="Select Category" data-pk="{{ $Reportitem->id }}" data-value="{{ $Reportitem->technology_id }}" data-type="select" data-placement="right" data-title="Edit Report">{{$Reportitem->technology->name}}</a></td>--}}
                {{--<td><a href="javascript:void(0)" data-name = 'works' class="editdata" data-pk="{{ $Reportitem->id }}" data-type="text" data-placement="right" data-title="Edit Report">{{$Reportitem->works}}</a></td>--}}
                {{--<td data-name="start">{{ $start }}</td>--}}
                {{--<td data-name="end">{{ $end }}</td>--}}
                {{--<td><a href="javascript:void(0)" data-name ='start' class="editdata" data-type="combodate" data-value="{{ $Reportitem->start }}" data-format="HH:mm:ss" data-viewformat="HH : mm a" data-template="HH : mm a" data-pk="{{ $Reportitem->id }}"></a></td>--}}
                {{--<td><a href="javascript:void(0)" data-name ='end' class="editdata" data-type="combodate" data-value="{{ $Reportitem->end }}" data-format="HH:mm:ss" data-viewformat="HH : mm a" data-template="HH : mm a" data-pk="{{ $Reportitem->id }}"></a></td>--}}
                {{--<td>{{ $Reportitem->getElapsedTime() }}</td>--}}
                {{--<td><a href="javascript:void(0)" data-name = 'status' class="editstatus" data-pk="{{ $Reportitem->id }}" data-value="{{ $Reportitem->status }}" data-type="select" data-placement="right" data-title="Edit Report">{{ $Reportitem->status_name }}</a></td>--}}
                @if(!$action)
                    {{--<td><a href="javascript:void(0)" data-name = 'notes' class="editdata" data-pk="{{ $Reportitem->id }}" data-type="textarea" data-placement="right" data-title="Edit Project">{{$Reportitem->notes}}</a></td>--}}
                @endif
                @if($action && $condition == '')
                <td>
                    @if($Reportitem->lock != 1)
                        <a href="javascript:void(0)" data-id="{{ $Reportitem->id }}"
                           class="btn btn-sm btn-primary btn-edit" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;
                        <a href="javascript:void(0)" data-id="{{ $Reportitem->id }}"
                           class="btn btn-sm btn-success btn-copy" title="Copy"><i class="fa fa-copy"></i></a>&nbsp;
                           <button type="button" title ="Extend" data-toggle="modal" data-reportitem_id="{{ @$Reportitem->id }}" data-extendvalue="1"  data-target="#extendModal" class="btn btn-sm waves-effect waves-light btn-warning extend"><i class="fa fa-plus"></i></button>
                        <button class="btn btn-sm btn-danger btn-delete" type="button" title="Delete"
                                data-id="{{ $Reportitem->id }}"><i class="fa fa-remove"></i></button>
                      
                    @endif
                </td>

            @elseif($condition == 'admin-report-edit')
            <td>
                    <button class="btn btn-sm btn-primary btn-edit " title="View" data-url="' . url('trainee/report', $model->id) . '" data-itemid="{{ $Reportitem->id }}" data-toggle="modal" data-target="#ReportEditModal">
                        <i class="fa fa-edit"></i></button>
                      
                        <button class="btn btn-sm btn-danger btn-delete" type="button" title="Delete"
                        data-itemid="{{ $Reportitem->id }}"><i class="fa fa-remove"></i></button>

                </td>
                @endif
            </tr>
        @empty
            <tr class="norecord">
                <td colspan="20" style="text-align: center">No records found</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    @if(@$Reportitem && $action)
        <div class="pull-right">
              <button type="button" data-toggle="modal" data-report_id="{{ @$Reportitem->report->id }}" data-releaselockvalue="1"  data-target="#releaseLockModal" class="btn btn-sm waves-effect waves-light btn-warning">Release Lock</button>
         </div>
    @endif
</div>



