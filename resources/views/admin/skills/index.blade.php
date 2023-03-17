@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
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