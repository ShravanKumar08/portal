    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Name: </strong>
                                {{ $Model->employee->name }}
                            </div>
                            <div class="form-group">
                                <strong> Start Date: </strong>
                                {{ $Model->from }}
                            </div>
                            <div class="form-group">
                                <strong> To Date: </strong>
                                {{ $Model->to }}
                            </div>
                            <div class="form-group">
                                <strong> Temporary Access Card No: </strong>
                                {{ $Model->tempcard }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>