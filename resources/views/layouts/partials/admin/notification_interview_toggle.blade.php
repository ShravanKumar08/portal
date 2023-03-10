@php
    $calls = App\Models\InterviewCall::whereDoesntHave('status', function ($query) {
        $query->whereIn('name', ['canceled', 'completed']);
    })->whereDate('schedule_date', Carbon\Carbon::now()->toDateString())->get();
@endphp

<div class="message-center">
    @foreach(@$calls as $call)
        @php($candidate = $call->candidate)
        <a href="{{ url('admin/interviewcall/'.$call->id)}}">
        <div class="btn btn-success btn-circle"><i class="mdi mdi-clipboard-check"></i></div>
        <div class="mail-contnet interviews-content">
        <h5>{{ $candidate->name }}</h5> <span class="mail-desc">{{ $candidate->email }}</span> 
        <span class="time">{{ $call->schedule_date }}</span> 
        </div>
        </a>
    @endforeach 
</div>