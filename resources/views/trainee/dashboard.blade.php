@extends('layouts.master')

@section('content')
    <div class="row page-titles" style="background:url('{{ asset('assets/images/background/user-bg.jpg') }}') no-repeat center top">
        <div class="col-lg-12 text-center">
            <h1 class="m-t-30">{{ \Auth::user()->name }}</h1>
            <h5 class="text-muted m-b-30"><i class="ti-pin"></i> </h5>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Company Rules</h4>
                        {!! $rules !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
