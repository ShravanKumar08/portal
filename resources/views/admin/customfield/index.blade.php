@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="contact-page-aside card-body">
                    <div class="text-right p-b-20">
                        <a class="btn btn-sm btn-primary" id="advance-search-btn" data-toggle="collapse" href="#collapseAdvanced" role="button" aria-expanded="false" aria-controls="collapseAdvanced">
                            {{ @request()->show ? 'Hide' : 'Show' }} Advanced Search
                        </a>
                    </div>
                    <div class="collapse {{ @request()->show ? 'show' : '' }}" id="collapseAdvanced">
                        <div class="card card-outline-info">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white"><i class="fa fa-search"></i> Advanced search</h4>
                            </div>
                        <div class="card-body">
                            {{ Form::open(['url' => \URL::current(), 'class' => 'form-horizontal','method' => 'GET']) }}
                                {{ Form::hidden('show', 1) }}
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="">Form Groups</label>
                                                {{ Form::select('formgroup', @$formgroups, @request()->formgroup, ['class' => 'form-control select2', 'placeholder' => 'Select']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Search</button>
                                    <button type="button" class="btn btn-inverse" onclick="location.href ='{{  route('customfield.index')}}'">Reset</button>
                                </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'display nowrap table table-hover table-striped table-bordered', 'id' => 'datatable-buttons']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('layouts.partials.datatable_scripts')