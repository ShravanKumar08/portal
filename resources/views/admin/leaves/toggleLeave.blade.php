{{ Form::open( ['route' => ['leave.toggleLeave'],'method' => 'POST', 'class' => 'form-horizontal','id'=>'submitForm']) }}
<div class="modal-body">
    <label class="" style="font-weight: bold">Name:</label> <br/>
    {{ $employee_name }} <br/>
    <label class="" style="font-weight: bold">Leave Date:</label>
    <div class="text-left">
        @foreach($leaveitems as $leaveitem)
        {{ $leaveitem->date }} ({{ $leaveitem->days }} day) 
         {{ Form::select('leavetype_id', $leavetypes, $leaveitem->leavetype_id, ['class' => 'form-control col-md-3 leavetype', 'data-leaveitemid' => $leaveitem->id]) }}
        <br/> <br/>
        @endforeach
        {{ Form::hidden('leaveitem_id',$leaveitem->id) }}
    </div>
</div>
{{ Form::close() }}
