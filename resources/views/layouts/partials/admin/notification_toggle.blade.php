@php
    $LatestPermissions = App\Models\Userpermission::query()->pending()->latest('created_at')->get();
    $LatestLeaves = App\Models\Leave::query()->pending()->latest('created_at')->get();
@endphp

<div class="message-center">
        <!-- Message -->
    <span class="noRecords1"></span>
    @if(!empty(@$LatestPermissions))
       @foreach(@$LatestPermissions as $permission)

       <a href="{{ url('admin/userpermission')}}">
            <div class="btn btn-success btn-circle"><i class="mdi  mdi-clipboard-check"></i></div>
            <div class="mail-contnet permissions-content">
            <h5>{{ $permission->employee->name }}</h5> <span class="mail-desc">{{ $permission->reason }}</span> <span class="time">{{ $permission->date }}</span> </div>
        </a>
       
        @endforeach 
    @endif
    
    @if(!empty(@$LatestLeaves))
       @foreach(@$LatestLeaves as $leave)

       <a href="{{ url('admin/leave')}}">
            <div class="btn btn-danger btn-circle"><i class="mdi mdi-file-document"></i></div>
            <div class="mail-contnet permissions-content">
            <h5>{{ $leave->employee->name }}</h5> <span class="mail-desc">{{ $leave->reason }}</span> <span class="time">{{ $leave->start }}</span> </div>
        </a>
       
        @endforeach 
    @endif
     

     
</div>

@push('scripts')

<script type="text/javascript">
    $(document).ready(function () {
        
        if($('.permissions-content').text() == ''){
            $('.perm-leave-heartbit, .perm-leave-point').hide();
            $('.noRecords1').html(' <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No Records..');
        }
    });
            
</script>
@endpush
