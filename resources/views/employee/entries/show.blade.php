<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row p-t-20">
                    <div class="col-md-4">
                        <div class="form-group">
                            <strong> Date: </strong>
                            {{ Carbon\Carbon::parse($Model->date)->format('d-m-Y') }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <strong> Start: </strong>
                            {{ $Model->start }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <strong> End: </strong>
                            {{ $Model->end }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>