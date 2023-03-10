@php
    $LatestReports = \App\Models\Report::where('status', 'P')->latest('created_at')->get();

    $release_request_ids = \App\Models\ReportItem::query()->where('release_request', 1)->pluck('report_id')->toArray();
    $LatestReleaselock = \App\Models\Report::query()->whereIn('id', $release_request_ids)->get();
@endphp
<div class="message-center">
        <!-- Message -->
    <span class="noRecords2"></span>
    @if(!empty(@$LatestReports))
       @foreach(@$LatestReports as $reports)

       <a href="{{ url('admin/report')}}" >
            <div class="btn btn-success btn-circle"><i class="mdi mdi-library-books"></i></div>
            <div class="mail-contnet report-content">
            <h5>{{ @$reports->employee->name }}</h5> <span class="time"><i>Pending reports..</i></span> </div>
        </a>
       
        @endforeach 
    @endif
      
    @if(!empty(@$LatestReleaselock))
       @foreach(@$LatestReleaselock as $release)

       <a href="{{ url('admin/report?scope=releaselock')}}" >
            <div class="btn btn-danger btn-circle"><i class="mdi mdi-houzz-box"></i></div>
            <div class="mail-contnet report-content">
                <h5>{{ $release->employee->name }}</h5> <span class="time"><i>Requested to release lock..</i></span> </div>
        </a>
       
        @endforeach 
    @endif
   
   
</div>

@push('scripts')

<script type="text/javascript">
    $(document).ready(function () {
        
        if($('.report-content').text() == ''){
            $('.report-heartbit, .report-point').hide();
            $('.noRecords2').html(' <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No Records..');
        }
    });
            
</script>
@endpush