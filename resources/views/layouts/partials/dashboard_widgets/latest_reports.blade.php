@php
    $reports = \App\Models\Report::query()->latest('date')->limit(10)->get();
@endphp
<div class="card dashboardDivScrolling dashScrolling" id="scroll-dashboard-nice">
    <div class="card-body">
        <h4 class="card-title">Latest Reports</h4>
    </div>

     <div class="comment-widgets m-b-20">
        <!-- Comment Row -->
        @foreach(@$reports as $report)
            <div class="d-flex flex-row comment-row">
                <div class="p-2"><span class="round round-customcolor"><img src="{{ $report->employee->avatar }}"
                                                                            alt="user" width="50" height="50"></span>
                </div>
                <div class="comment-text w-100">
                    <h5>{{  $report->employee->shortname }}</h5>
                    <div class="comment-footer">
                        <span class="date">{{ \Carbon\Carbon::parse($report->date)->format('d-m-Y') }}</span>
                        @php
                            $color_class = AppHelper::getButtonColorByStatusReport($report->status);
                        @endphp
                        <span class='label label-{{ $color_class }}'>{{ $report->statusname }}</span>
                    </div>
                    <p class="m-b-5 m-t-10">{{ $report->projectNames }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
