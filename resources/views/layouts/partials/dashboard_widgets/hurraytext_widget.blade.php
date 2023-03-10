@php
    $tmrw = \Carbon\Carbon::now()->addDay()->toDateString();
    $official_permission = App\Models\Setting::isOfficialPermissionToday();
    $official_leave = App\Models\Setting::isOfficialLeaveToday($tmrw);
    $official_holiday = \App\Models\Holiday::where("date", $tmrw)->first();
    $official_halfday_tomorrow = App\Models\Setting::isOfficialHalfdayLeaveToday($tmrw);
    $official_halfday = App\Models\Setting::isOfficialHalfdayLeaveToday();
@endphp

@if($official_permission || $official_leave || $official_holiday || $official_halfday_tomorrow || $official_halfday)
    <div class="row">
        <div class="col-12">
            <p class='blink' style="text-align:center; color:#f46242; font-size: 20px; font-weight:bolder">
                @if($official_permission)
                    Hurray!! Today is Official Permission
                @elseif($official_leave)
                    Hurray!! Tomorrow official Leave
                @elseif($official_holiday)
                    Hurray!! Tomorrow official Holiday ({{ $official_holiday->name }})
                @elseif($official_halfday_tomorrow)
                    Hurray!! Tomorrow is Official Halfday Leave
                @elseif($official_halfday)
                    Hurray!! Today is Official Halfday Leave
                @endif
            </p>
        </div>
    </div>

    @push('scripts')
        <script type="text/javascript">
            function blink_text() {
                $('.blink').fadeOut(500);
                $('.blink').fadeIn(500);
            }

            setInterval(blink_text, 2000);
        </script>
    @endpush
@endif
