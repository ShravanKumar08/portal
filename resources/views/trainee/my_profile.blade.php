@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-lg-4 col-xlg-3 col-md-5">
        <div class="card">
            <img class="card-img" src="{{ asset('assets/images/background/socialbg.jpg') }}" alt="Card image">
            <div class="card-img-overlay card-inverse social-profile d-flex justify-content-md-center">
                <div class="align-self-center text-center">
                    <img src="{{ $Employee->avatar }}" class="img-circle" width="100">
                    <h4 class="card-title">{{ $Employee->name }}</h4>
                    <h6 class="card-subtitle">{{ $Employee->email}}</h6>
                    <p class="text-white">{{ $Employee->designation->name }}</p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <small class="text-muted">Email address </small>
                <h6>{{ $Employee->email}}</h6>
                <small class="text-muted p-t-30 db">Phone</small>
                <h6> {{ $Employee->employee_phonenumber }} </h6>
                <small class="text-muted p-t-30 db">Address</small>
                @php
                $address = trim($Employee->employee_address.' '.$Employee->employee_city.' '.$Employee->employee_state.' '.$Employee->employee_country);
                @endphp
                <h6>{{ $address }}</h6>
            </div>
        </div>
    </div>

    <div class="col-lg-8 col-xlg-9 col-md-7">
        <div class="card">
            <ul class="nav nav-tabs profile-tab" role="tablist" id="tabMenu">
                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab">Profile</a> </li>
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#changepassword" role="tab">Change Password</a> </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="home" role="tabpanel">
                    <div class="card-body">
                        {{ Form::model(@$Employee, ['route' => [ "trainee.profile"], @$Employee->id,'method' => 'POST', 'class' => 'form-horizontal form-material','files' => true]) }}
                        <div class="form-group">
                            <div class="col-md-12">
                                {{ Form::label('name','Name',['class'=>'']) }}
                                {{ Form::text('name', old('name'), ['class' => 'form-control form-control-line']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                            {{ Form::label('gender','Gender*',['class'=>'']) }}
                            <div class="form-check">
                                @foreach ($gender as $id=>$gen)
                                <label class="custom-control custom-radio">                        
                                    {!! Form::radio('gender', $id, (@$Employee->gender==$id), ['class' => 'custom-control-input'] ); !!} {{$gen}}                                               
                                    <span class="custom-control-indicator"></span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                            </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                {{ Form::label('dob','Date of Birth*',['class'=>'']) }}
                                {{ Form::text('dob', old('dob'), ['class' => 'form-control datepicker', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                {{ Form::label('photo','Photo',['class'=>'']) }}<br/>
                                {{ Form::file('photo', ['accept' => 'image/*']) }}
                            </div>
                        </div>

                        @include('layouts.partials.custom_fields_form')

                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success">Update Profile</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
                <div class="tab-pane" id="changepassword" role="tabpanel">
                    <div class="card-body">
                        {{ Form::open( ['route' => [ "trainee.changepassword"], 'method' => 'POST', 'class' => 'form-horizontal form-material']) }}
                        <div class="form-group">
                            <div class="col-md-12">
                                {{ Form::label('current_password','Old Password',['class'=>'']) }}
                                {{ Form::password('current_password',['class' => 'form-control form-control-line']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                {{ Form::label('new_password','New Password',['class'=>'']) }}
                                {{ Form::password('new_password',  ['class' => 'form-control form-control-line']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                {{ Form::label('confirm_password','Confirm Password',['class'=>'']) }}
                                {{ Form::password('confirm_password', ['class' => 'form-control form-control-line']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success">Change Password</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<!--Auto complete Search-->
<script type="text/javascript">
$(document).ready(function () {
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true, todayHighlight: true,
    });

    @if(old('tab'))
        $('#tabMenu a[href="#{{ old('tab') }}"]').tab('show');
    @endif

    var hash = window.location.hash;
    if(hash){
        $('#tabMenu a[href="' + hash + '"]').tab('show');
    }
});
</script>
@endpush
