<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row" style="margin-bottom: 10px; ">
                    <div class="col-3" style="text-align: right">
                        <label>Total Participants:&nbsp&nbsp</label><span class="label label-info">{{$total}}</span><br/>
                    </div>
                    <div class="col-2 " style="text-align: right">
                        <label>Pending:&nbsp&nbsp</label><span class="label label-danger">@if($pending){{ $pending}}@else 0 @endif</span><br>
                    </div>
                    <div class="col-2 " style="text-align: right">
                        <label>Joined:&nbsp&nbsp</label><span class="label label-success">@if($approved){{$approved}}@else 0 @endif</span><br/>                    
                    </div>
                    <div class="col-2" style="text-align: right">
                        <label>Declined:&nbsp&nbsp</label><span class="label label-inverse">@if($declined){{$declined}}@else 0 @endif</span>
                    </div>
                </div>    
                <div id="tabs">
                    <ul>
                      <li><a href="#tabs-1">All</a></li>
                      <li><a href="#tabs-2">Pending</a></li>
                      <li><a href="#tabs-3">Joined</a></li>
                      <li><a href="#tabs-4">Declined</a></li>
                    </ul>
                    <div id="tabs-1">
                        <table class="display nowrap table table-hover table-striped table-bordered dataTable no-footer ">
                            <thead >
                                <tr>
                                    <th>Name Of Participant</th>
                                    <th>Status</th>
                                    <th>Attendance</th>
                                </tr>
                            </thead>
                            @foreach($lectures as $lecture)
                            <tr>
                                <td>{{$lecture->name}}</td>
                                @if($lecture->pivot->status == 'P')
                                <td class='status'><span class="label label-danger">Pending</span></td>
                                @elseif($lecture->pivot->status == 'A')
                                <td class='status'><span class="label label-success">Joined</span></td>
                                @else
                                <td class='status'><span class="label label-inverse">Declined</span></td>
                                @endif
                                <td>
                                    <div class="switch">
                                        <label>
                                            {{ Form::checkbox('mark_attendance', 1,  @$lecture->pivot->mark_attendance, [ 'data-joiner_id' => $lecture->id , 'data-lecture_id' => $lecturer_id->id  ]) }}<span class="lever switch-col-blue"></span>
                                        </label>
                                    </div>      
                                </td>
                            </tr>
                            @endforeach
                        </table>                      
                    </div>
                    <div id="tabs-2">
                        <table class="display nowrap table table-hover table-striped table-bordered dataTable no-footer ">
                            <thead>
                                <tr>
                                    <th>Name Of Participant</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            @foreach($lectures as $lecture)
                            @if($lecture->pivot->status == 'P')
                            <tr>
                                <td>{{$lecture->name}}</td>
                                <td><span class="label label-danger">Pending</span></td>
                            </tr>
                            @endif
                            @endforeach
                        </table>                     
                    </div>
                    <div id="tabs-3">
                        <table class="display nowrap table table-hover table-striped table-bordered dataTable no-footer ">
                            <thead>
                                <tr>
                                    <th>Name Of Participant</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            @foreach($lectures as $lecture)
                            @if($lecture->pivot->status == 'A')
                            <tr>
                                <td>{{$lecture->name}}</td>
                                <td><span class="label label-success">Joined</span></td>
                            </tr>
                            @endif
                            @endforeach
                        </table> 
                    </div>
                    <div id="tabs-4">
                        <table class="display nowrap table table-hover table-striped table-bordered dataTable no-footer ">
                            <thead>
                                <tr>
                                    <th>Name Of Participant</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            @foreach($lectures as $lecture)
                            @if($lecture->pivot->status == 'D')
                            <tr>
                                <td>{{$lecture->name}}</td>
                                <td><span class="label label-inverse">Declined</span></td>
                            </tr>
                            @endif
                            @endforeach
                        </table> 
                    </div>
                  </div>      
            </div>
        </div>
    </div>
</div>
<script>
    $( function() {
      $( "#tabs" ).tabs();
    } );

    $(document).ready(function(){
    $('input[type="checkbox"]').change(function(){
        $status = $(this).closest('tr').find('td.status');
        var present = $(this).prop("checked") == true ?  $(this).val() : 0;
        var joiner =   $(this).data('joiner_id');
        var lecturer = $(this).data('lecture_id');
                                        
        $.ajax({
            method: 'POST',
            url: "{{ route('lectures.markattendance') }}",
            data: {
                'attendance': present,
                'joiner_id':joiner, 
                'lecture_id':lecturer,
            },
            success: function () {
                $status.html('<span class="label label-success">Joined</span>');
            },
        });
    });
});
</script>
