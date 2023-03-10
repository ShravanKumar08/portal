<div class="card-body">
        <div class="row">
            <div class="col-md-3 col-xs-6 b-r"> <strong>Full Name</strong>
                <br>
                <p class="text-muted">{{$candidate->name}}</p>
            </div>
            <div class="col-md-3 col-xs-6 b-r"> <strong>Mobile</strong>
                <br>
                <p class="text-muted">{{$candidate->mobile}}</p>
            </div>
            <div class="col-md-3 col-xs-6 b-r"> <strong>Email</strong>
                <br>
                <p class="text-muted">{{$candidate->email}}</p>
            </div>
            <div class="col-md-3 col-xs-6"> <strong>Location</strong>
                <br>
                <p class="text-muted">{{$candidate->permanent_location}}</p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6 col-xs-6">
                    <h5 class="font-medium m-t-30"> Designation :</h5>
                    <p>{{$designation->name}}</p>
                <h5 class="font-medium m-t-30">Present company :</h5>
                <p>{{$model->present_company}}</p>
                <h5 class="font-medium m-t-30">Present location :</h5>
                <p>{{$model->present_location}}</p>
                <h5 class="font-medium m-t-30"> Experience :</h5>
                <p>{{$model->experience}}</p>
            </div>
            <div class="col-md-6 col-xs-6">
                <h5 class="font-medium m-t-30">Specialization :</h5>
                <p>{{$model->technology}}</p>
                <h5 class="font-medium m-t-30">Reason for Change :</h5>
                <p>{{$model->present_location}}</p>
            </div>
        <hr>
    </div>
</div>