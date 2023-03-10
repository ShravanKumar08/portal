@php
    $office_timings = (array) @$auth_employee->officetiming->value ?: [];
    $timings = \App\Models\Officetimingslot::$timings;
@endphp

<div class="card dashboardDivScrolling dashScrolling">
    <div class="card-body bg-inverse">
        <h4 class="text-white card-title">Office timings</h4>
    </div>
    <div class="card-body">
        <div class="message-box contact-box">
            <div class="message-widget contact-widget">
                @foreach(@$office_timings as $key => $office_timing)
                    @php
                        $timing = $timings[$key];
                    @endphp
                <a href="#">
                    <div class="">
                        <h6>{{ @$timing['text'] }}: <strong>{{ $timing['time'] == 'true' ? \Carbon\Carbon::parse($office_timing)->format('h:i A') : $office_timing }}</strong></h6>
                    </div>
                </a>
               @endforeach
            </div>
        </div>
    </div>
</div>
