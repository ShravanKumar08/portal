@php
    $employee = \App\Models\Employee::active()->permanent()->whereMonth('dob', \Carbon\Carbon::now()->month)->orderByRaw('day(dob) ASC')->get();
@endphp

<div class="card dashboardDivScrolling dashScrolling">
    <div class="card-body bg-inverse">
        <h4 class="text-white card-title">Birthdays on this Month</h4>
    </div>
    <div class="card-body">
        <div class="message-box contact-box">
            <div class="message-widget contact-widget">
                @forelse(@$employee as $emp)
                <a href="#">
                    <div class="user-img"> <img src="{{ $emp->avatar }}" alt="user" class="img-circle"></div>
                    <div class="mail-contnet">
                    <h5>{{ $emp->shortname }}</h5><span class="mail-desc">{{ date('M jS', strtotime($emp->dob)) }}</span></div>
                </a>
               @empty
                    <div class="text-center">No birthdays</div>
               @endforelse
            </div>
        </div>
    </div>
</div>