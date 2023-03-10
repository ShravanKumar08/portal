<div class="col-lg-8 col-xlg-9 col-md-7">
        <div class="card">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs profile-tab" role="tablist">
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile" role="tab">Profile</a> </li>
                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab">Timeline</a> </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="home" role="tabpanel">
                    <div class="card-body">
                        <div class="profiletimeline">
                                
                            @if(!empty($remarks))
                            @foreach($remarks as $key=>$value)
                            <div class="sl-item" style="">
                                <div class="sl-left"> <img src="{{ asset('assets/images/logo-icon.png') }}" alt="user" class="img-circle"> </div>
                                <div class="sl-right">
                                <div>
                                    {{$value->remarks}}
                                    <p><span class="sl-date"> by {{$employee[$key]->name}} </span> <span class="sl-date">{{ $value->created_at->diffForHumans() }}</span></p>      
                                </div>
                                </div>
                            </div>
                            @endforeach

                            @endif
                        </div>
                    </div>
                </div>
                <!--second tab-->
                <div class="tab-pane" id="profile" role="tabpanel">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Full Name</strong>
                                <br>
                                <p class="text-muted">{{$candidate->name}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Gender</strong>
                                <br>
                                <p class="text-muted">{{$candidate->gender}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Marital Status</strong>
                                <br>
                                <p class="text-muted">{{($candidate->martial_status == 'S') ? 'Single' : 'Married'}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Mobile</strong>
                                <br>
                                <p class="text-muted">{{$candidate->mobile}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>Email</strong>
                                <br>
                                <p class="text-muted">{{$candidate->email}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6"> <strong>Permanent Location</strong>
                                <br>
                                <p class="text-muted">{{$candidate->permanent_location}}</p>
                            </div>
                            <div class="col-md-3 col-xs-6"> <strong>Current Location</strong>
                                <br>
                                <p class="text-muted">{{$model->present_location}}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 col-xs-6">
                                    <h5 class="font-medium m-t-30"> Designation :</h5>
                                    <p>{{$designation->name}}</p>
                                <h5 class="font-medium m-t-30">Present company :</h5>
                                <p>{{$model->present_company}}</p>
                              
                                <h5 class="font-medium m-t-30"> Experience :</h5>
                                <p>{{$model->experience}}</p>
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <h5 class="font-medium m-t-30">Specialization :</h5>
                                <p>{{$model->candidate->technology}}</p>
                                <h5 class="font-medium m-t-30">Reason for Change :</h5>
                                <p>{{$model->change_reason}}</p>
                            </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
</div>