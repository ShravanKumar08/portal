@extends('layouts.master')

@section('content')
    @if(@$Report)
        @if($Report->status == 'P')
            <div class="alert alert-warning alert-rounded">
                You can send report after admin approves
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-body">
                        <h3 class="card-title">Today Report ( {{date('jS F Y', strtotime($Report->date))}} )</h3>
                            <div id='report-div'>
                                <div class="text-center">
                                    <i class="fa fa-spin fa-spinner fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="getreportitems"></div>

        <div class="form-actions text-center" id="reports_save">
            @if($Report->status != 'P')
                <button type="submit" id="report-send" class="btn btn-success"><i class="fa fa-paper-plane"></i> Send</button>
            @endif
            <textarea id="smart_report" style="display: none"></textarea>
            <a class="btn btn-info" id="copy-button" href="javascript:void(0)">Copy to clipboard</a>
        </div>
    @else
        <div class="row justify-content-md-center">
            <div class="col-5">
                <div class="card">
                    <div class="card-body">
                        <div class="form-body">
                            <h3 class="card-title">Timer not started yet.</h3>
                            <h6 class="card-subtitle">Just send a request with your start time and reason</h6>

                            {{ Form::open(['route' => 'trainee.report.timeronrequest', 'method' => 'POST' ,'class' => 'form-material m-t-40']) }}
                                @include('layouts.partials.timeronrequest')
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@include('trainee.reports.partials.reportscripts')
