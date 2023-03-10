<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row p-t-20">
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> Name: </strong>
                            {{ $Model->name }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> Email: </strong>
                            {{$Model->email }}
                        </div>
                    </div>
                </div>
                <div class="row p-t-20">
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> Status: </strong>
                            <b>{{ @$custom_val->value }}</b>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> Phone: </strong>
                            {{ $Model->phone }}
                        </div>
                    </div> 
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> Remarks: </strong>
                            {{ $Model->remarks }}
                        </div>
                    </div>                        
                </div>
            </div>
        </div>
    </div>
</div>