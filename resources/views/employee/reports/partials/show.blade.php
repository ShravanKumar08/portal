<div class="row">
<div class="col-12">
 <div class="card">
            <div class="card-body">
                <div class="row p-t-20">
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> Start Time: </strong>
                            {{ Carbon\Carbon::parse($Report->start)->format('H:i A') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> Date: </strong>
                            {{ Carbon\Carbon::parse($Report->date)->format('d-m-Y') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> End Time: </strong>
                            {{$Report->ActualEndTime == null ? '-' : Carbon\Carbon::parse($Report->ActualEndTime)->format('H:i A')}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> Worked Hours: </strong>
                            {{ $Report->workedhours ? Carbon\Carbon::parse($Report->workedhours)->format('H:i') : '-' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> Break Hours: </strong>
                            {{ $Report->breakhours ? Carbon\Carbon::parse($Report->breakhours)->format('H:i') : '-'}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> Total Hours: </strong>
                            {{ $Report->totalhours ? Carbon\Carbon::parse($Report->totalhours)->format('H:i') : '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="card">
            <div class="card-body">
                <h3 class="card-title">Report Items</h3>
                <hr>
                <div class="row p-t-20">
                  @include('layouts.partials.reportitemstable',['action'=> false, 'condition' => ''])
                   <div class="form-actions pull-right text-center">
                        <a class="btn btn-info" id="copy-button-clone" href="javascript:void(0)">Copy to clipboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>

