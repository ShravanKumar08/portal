@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-body">
                        <h3 class="card-title">Pending Report ({{ @$Report->date }})</h3>
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
        <button type="submit" id="report-send" class="btn btn-success"><i class="fa fa-paper-plane"></i> Send</button>
        <textarea id="smart_report" style="display: none"></textarea>
        <a class="btn btn-info" id="copy-button" href="javascript:void(0)">Copy to clipboard</a>
    </div>
@endsection

@include('trainee.reports.partials.reportscripts')