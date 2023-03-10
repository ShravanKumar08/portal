<div id="reportsdiv">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-4 col-lg-4 col-xl-4">
                            <span>Worked Hours: @if($Report->workedhours){{ \Carbon\Carbon::parse($Report->workedhours)->format('H:i') }}@endif</span> <br/>
                            <span>Break Hours: @if($Report->breakhours){{ \Carbon\Carbon::parse($Report->breakhours)->format('H:i') }} @if($extended_break) <i class='text-danger'>(+{{ $extended_break }} {{ str_plural('min', $extended_break) }})</i> @endif @endif</span><br>
                            <span id="selected_elapsed_hours"></span>
                        </div>
                        <div class="col-4  col-lg-4 col-xl-4 text-center">
                            <h4>Report Summary</h4>
                        </div>
                        <div class="col-4  col-lg-4 col-xl-4" style="text-align: right">
                            <span>Start time: {{ \Carbon\Carbon::parse($Report->start)->format('h:i A') }}</span> <br/>
                            @if($final_endtime)
                                <span>End time: {!! $final_endtime !!}</span>
                            @endif
                        </div>
                    </div>
                    @include('layouts.partials.reportitemstable',['action'=> true, 'condition' => ''])
                </div>
            </div>
        </div>
    </div>
</div>
