@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <img class="card-img" src="{{ asset('assets/images/background/socialbg.jpg') }}" alt="Card image">
                <div class="card-img-overlay card-inverse social-profile d-flex justify-content-md-center">
                    <div class="align-self-center"><img src="{{ $Employee->avatar }}" class="img-circle" width="100">
                        <h4 class="card-title">{{ $Employee->name }}</h4>
                        <h6 class="card-subtitle">{{ $Employee->email }}</h6>
                        <p class="text-white">{{ $Employee->designation->name }}</p>
                        @if(@$Employee->user->active)
                            <p class="card-subtitle">Experience: ({{ $Employee->current_experience }})</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Email address</small>
                    <h6>{{ $Employee->email }}</h6>
                    <small class="text-muted p-t-30 db">Phone</small>
                    <h6>{{ $Employee->employee_phonenumber }}</h6>
                    <small class="text-muted p-t-30 db">Address</small>
                    <h6>@php
                            $address = trim($Employee->employee_address.' '.$Employee->employee_city.' '.$Employee->employee_state.' '.$Employee->employee_country);
                        @endphp
                        <h6>{{ $address }}</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
                <ul class="nav nav-tabs profile-tab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#personalinfo" role="tab">Personal
                      Info</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="personalinfo" role="tabpanel">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-xs-6"><strong>Full Name</strong>
                                    <br>
                                    <p class="text-muted">{{ $Employee->name }}</p>
                                </div>
                                <div class="col-md-4 col-xs-6"><strong>Gender</strong>
                                    <br>
                                    <p class="text-muted"> {{ $Employee->gendername}}</p>
                                </div>
                                <div class="col-md-4 col-xs-6"><strong>Date of Birth</strong>
                                    <br>
                                    <p class="text-muted">{{ Carbon\Carbon::parse($Employee->dob)->format('d-m-Y') }}</p>
                                </div>
                            </div>
                                @php
                                    $formgroups = $custom_fields->groupBy('formgroup');
                                @endphp

                                @foreach($formgroups as $formgroup => $formfields)
                                    <h4 class="box-title m-t-40">{{ $formgroup }}</h4>
                                    <hr>
                                    @php
                                        $field_chunks = $formfields->chunk(2);
                                    @endphp
                                   <div class="row">
                                    @foreach($field_chunks as $fields)
                                        @foreach($fields as $field)
                                            <div class="col-md-4 col-xs-6"><strong>{{ Form::label($field->name, $field->label) }} </strong>
                                            <br>
                                            <p class="text-muted">{{ $Employee->{$field->name} }}</p><br>
                                            </div>
                                        @endforeach
                                    @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
