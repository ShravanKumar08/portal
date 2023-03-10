@php
    $Lectures = App\Models\Lecture::withoutGlobalScope(App\Scopes\EmployeeScope::class)->whereHas('employees',function($query){
        $query->where('employees.id',\Auth::user()->employee->id)->where('status','P');
    })->get(); 
@endphp
<div class="message-center">
        <!-- Message -->
    <span class="noRecords1"></span>
    @if(@$Lectures)
        @foreach($Lectures as $Lecture)
        <a href="{{ url('employee/lectures?scope=Others')}}">
                <div class="btn btn-danger btn-circle"><i class="mdi mdi-file-document"></i></div>
                <div class="mail-contnet permissions-content">
                <h5>{{ $Lecture->employee->name }}</h5> <span class="mail-desc">{{ $Lecture->title }}</span> <span class="time">{{ $Lecture->description }}</span> </div>
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
