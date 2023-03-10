 <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Employee: </strong>
                                {{ $Employee->name }}
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
                                <strong> Start: </strong>
                                {{ Carbon\Carbon::parse($Model->start)->format('g:i a') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> End: </strong>
                               {{ Carbon\Carbon::parse($Model->end)->format('g:i a') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Reason: </strong>
                                {{ $Model->reason }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Remarks: </strong>
                                {{ $Model->remarks }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Status: </strong>
                               {{ $Model->statusname }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
