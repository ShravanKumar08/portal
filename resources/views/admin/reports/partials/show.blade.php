<div class="row">
    <div class="col-12">
        <div class="card hidecard">
            <div class="card-body">
                <div class="row p-t-20">
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> Name: </strong>
                            {{ $Model->employee->name }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> Start Time: </strong>
                            {{ Carbon\Carbon::parse($Model->start)->format('H:i A') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> Date: </strong>
                            {{ Carbon\Carbon::parse($Model->date)->format('d-m-Y') }}
                        </div>
                    </div>
                     <div class="col-md-6">
                        <div class="form-group">
                            <strong> End Time: </strong>
                            {{ Carbon\Carbon::parse($Model->end)->format('H:i A') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> Worked Hours: </strong>
                            {{ Carbon\Carbon::parse($Model->workedhours)->format('H:i') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> Break Hours: </strong>
                            {{ Carbon\Carbon::parse($Model->breakhours)->format('H:i') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> Total Hours: </strong>
                            {{ Carbon\Carbon::parse($Model->totalhours)->format('H:i') }}
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
                        <textarea id="smart_report" style="display: none"></textarea>
                        <a class="btn btn-info" id="copy-button" href="javascript:void(0)">Copy to clipboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
