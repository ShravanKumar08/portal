<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row p-t-20">
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> Date: </strong>
                            {{ Carbon\Carbon::parse($Model->date)->format('d-m-Y') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> Elapsed Time (mins) : </strong>
                            {{ \Carbon\Carbon::parse($Model->elapsed)->format('i:s') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>