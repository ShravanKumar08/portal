@php
    $leaves = \App\Models\Leave::query()->monthYear('start', \Carbon\Carbon::now()->year ,\Carbon\Carbon::now()->month)->latest()->limit(10)->get();
@endphp
 <div class="card dashboardDivScrolling dashScrolling">
        <div class="card-body">
            <h4 class="card-title">Leave Requests</h4>
            </div>

        <div class="comment-widgets m-b-20">
           @forelse($leaves as $leave)
               @php
                   $employee = $leave->employee;
               @endphp
            <!-- Comment Row -->
            <div class="d-flex flex-row comment-row ">
                <div class="p-2"><span class="round round-customcolor"><img src="{{ $employee->avatar }}" alt="user" width="50" height="50"></span></div>
                <div class="comment-text active w-100">
                    <h5>{{ $employee->shortname }}</h5>
                    <div class="comment-footer">
                        <span class="date">{{ \Carbon\carbon::parse($leave->start)->format('M d, Y') }}</span>
                        @php
                            $color_class = AppHelper::getButtonColorByStatus($leave->status);
                        @endphp
                        <span class='label label-{{ $color_class }}'> {{ $leave->statusname }}</span> 
                    </div>
                    <p class="m-b-5 m-t-10">{{ $leave->reason }}</p>
                </div>
            </div>
            @empty
                   <div class="text-center">
                       No Leaves
                   </div>
            @endforelse

        </div>
    </div>