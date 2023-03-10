@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="text-right p-b-20">
            <a class="btn btn-sm btn-primary" id="advance-search-btn" data-toggle="collapse" href="#collapseAdvanced" role="button" aria-expanded="false" aria-controls="collapseAdvanced">
                {{ $request->show ? 'Hide' : 'Show' }} Advanced Search
            </a>
        </div>
            
            <div class="collapse {{ $request->show ? 'show' : '' }}" id="collapseAdvanced">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h4 class="m-b-0 text-white"><i class="fa fa-search"></i> Advanced search</h4>
                </div>
                <div class="card-body">
                    {{ Form::open(['url' => \URL::current(), 'class' => 'form-horizontal','method' => 'GET']) }}
                    {{ Form::hidden('show', $request->show) }}
                    {{ Form::hidden('scope', $request->scope) }}
                    <div class="form-body">
                        <div class="row">
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="">Date Range</label>
                                    <div class="input-daterange input-group" id="date-range">
                                        {{ Form::text('from_date', $request->from_date, ['class' => 'form-control', 'placeholder' => 'From', 'autocomplete' => 'off']) }}
                                        <span class="input-group-addon bg-info b-0 text-white">to</span>
                                        {{ Form::text('to_date', $request->to_date, ['class' => 'form-control', 'placeholder' => 'To', 'autocomplete' => 'off']) }}
                                    </div>
                                </div>
                            </div>   
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Search</button>
                        @if($request->scope == null)
                            <button type="button" class="btn btn-inverse" onclick="location.href ='{{  route('employee.report.index') }}'">Reset</button>
                            @else
                            <button type="button" class="btn btn-inverse" onclick="location.href ='{{  route('employee.report.index').'?scope='.$request->scope }}'">Reset</button>
                       @endif
                    </div>
                    {{ Form::close() }}
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
    <a class="btn btn-info hide" id="copy-button" href="javascript:void(0)">Copy to clipboard</a>

@endsection

@include('layouts.partials.datatable_scripts')
@include('employee.reports.partials.copytoclipboardscripts')

@push('stylesheets')    
<link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')

<script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function () {
         $('#date-range').datepicker({
            // toggleActive: true,
            format: 'yyyy-mm-dd',
            autoclose: true, todayHighlight: true,
        });
        
        $('#collapseAdvanced').on('hidden.bs.collapse', function () {
            $('#advance-search-btn').html('Show Advanced Search');
            $('input[name="show"]').val('');
        }).on('shown.bs.collapse', function () {
            $('#advance-search-btn').html('Hide Advanced Search');
            $('input[name="show"]').val(1);
        });

        $('body').on('click','.btn-status',function () {
                $this = $(this);
                swal({
                    title: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes!",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true,
                },function () {
                $.ajax({
                    method: 'POST',
                    url: "{{ route('employee.lectures.status') }}",
                    data: {
                        active: $this.data('status'),id:$this.data('id'),
                        employee: $this.data('employee'),id:$this.data('id')
                    },
                    success: function (data) {
                    if($this.data('status') == 'A'){
                        swal("Joined!", "You joined in this lecture.", "success");
                    } else {
                        swal("Declined","You declined this lecture.", "success");
                    }
                    $('#datatable-buttons').DataTable().draw(false);
                    }
                });
            });
        });
        
        $('body').on('click', '#delete', function (e) {
            var id = $(this).data('id');
            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            },function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('employee.lectures.deleteLecture') }}",
                    data: {id: id},
                    dataType: 'json',
                    success: function (data) {
                        swal("Deleted", "Row Deleted", "success");
                        $('#datatable-buttons').DataTable().draw(false);
                    },
                    error: function () {
                        swal("Failed", "Something went wrong", "error");
                    }
                });
            });
        });
    });
</script>
@endpush