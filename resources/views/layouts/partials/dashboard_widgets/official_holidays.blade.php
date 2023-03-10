@php
    $holidays = \App\Models\Holiday::monthYear('date', \Carbon\Carbon::now()->year , \Carbon\Carbon::now()->month)->latest()->get();
@endphp
 <div class="card dashboardSecondDivScrolling dashScrolling">
    <div class="card-body">
        <h4 class="card-title">Official Holidays</h4>
        <ul class="feeds">
            @forelse($holidays as $holiday)
            <li>
                <div class="bg-light-info"><i class="fa fa-bell-o"></i></div> {{ $holiday->name }} <span class="text-muted">{{ \Carbon\carbon::parse($holiday->date)->format('M d')  }}</span>
            </li>
            @empty
                <div class="text-center">
                    No Holidays
                </div>
            @endforelse
        </ul>
    </div>
</div>