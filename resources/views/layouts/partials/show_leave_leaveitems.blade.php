<?php
    use Illuminate\Support\Str;
?>
<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Trainee: </strong>
                                {{ $Employee->name }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Start Date: </strong>
                                {{ Carbon\Carbon::parse($Model->start)->format('d-m-Y') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> End Date: </strong>
                                {{ Carbon\Carbon::parse($Model->end)->format('d-m-Y') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Leave Days: </strong>
                                {{ $Model->days }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Reason: </strong>
                                {{ $Model->reason }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Remarks: </strong>
                                {{ $Model->remarks }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Status: </strong>
                               {{ $Model->statusname }}
                            </div>
                        </div>
                    </div>
                    <table cellspacing='15' cellpadding='15' width='100%' class='table color-table success-table color-bordered-table success-bordered-table'>
                        <thead>
                        <th>S.no</th>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Leave Type</th>
                    </thead>
                    @foreach($Leaveitems as $Leaveitem)
                        <tr>
                        <td>{{ $loop->iteration }}</td>   
                        <td>{{ Carbon\Carbon::parse($Leaveitem->date)->format('d-m-Y') }}</td>
                        <td>{{ $Leaveitem->days }}</td>
                         @php
                            $color_class = AppHelper::getLabelColorByType(@$Leaveitem->leavetype->name);
                        @endphp
                        <td><span class='label label-{{ $color_class }}'>{{ Str::title(@$Leaveitem->leavetype->name) }}</span></td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>