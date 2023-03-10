@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Person Info</h3>
                    <hr>
                    <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Name: </strong>
                                {{ $Model->name }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Employee Type: </strong>
                                @if($Employee->employeetype == 'P')
                                    {{ "Permanent" }}
                                @else
                                    {{ "Trainee" }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Gender: </strong>
                                {{ $Employee->gendername}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Date of Birth: </strong>
                                {{ $Employee->dob }}
                            </div>
                        </div>
                    </div>
                    <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Phone: </strong>
                                {{$Employee->phone }}
                            </div>
                        </div>
                    </div>
                    <h3 class="box-title m-t-40">Address</h3>
                    <hr>
                    <div class="row">
                        <div class="col-md-12 ">
                            <div class="form-group">
                                <strong> Address: </strong>
                                {{$Employee->address }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> City: </strong>
                                {{$Employee->city }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> State: </strong>
                                {{$Employee->state }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Country: </strong>
                                {{$Employee->country }}
                            </div>
                        </div>
                    </div>
                    <h3 class="box-title m-t-40">Other Details</h3>
                    <hr>
                    <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Father Name: </strong>
                                {{$Employee->fathername }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Mother Name: </strong>
                                {{$Employee->mothername }}
                            </div>
                        </div>
                    </div>
                    <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Spouse Name: </strong>
                                {{$Employee->spousename }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Skype: </strong>
                                {{$Employee->skype }}
                            </div>
                        </div>
                    </div>
                    <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Passport: </strong>
                                {{$Employee->passport }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Pan Number: </strong>
                                {{$Employee->panno }}
                            </div>
                        </div>
                    </div>
                    <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Aadhaar Card Number: </strong>
                                {{$Employee->aadhaarno }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Emergency Contact Number: </strong>
                                {{$Employee->emergencynumber }}
                            </div>
                        </div>
                    </div>
                    <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Joined on: </strong>
                                {{ $Employee->joindate }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Photo: </strong>
                                @if($Employee['photo'])
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($Employee['photo']) }}" alt="" title="" height="150px" width="150px"><br/>
                                @else
                                    {{ "None" }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Designation: </strong>
                                {{$Designation->name }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Active: </strong>
                                @if($Employee->active == "1")
                                    {{ "Active" }}
                                @else
                                    {{ "Inactive" }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <h3 class="box-title m-t-40">Login Details</h3>
                    <hr>
                    <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Email: </strong>
                                {{$Model->email }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection