@extends('layouts.master')

@section('content')
    <div class="row m-t-40">
        <!-- Column -->
        <div class="col">
            <div class="card card-inverse card-info">
                <div class="box bg-info text-center">
                    <h1 class="font-light text-white">{{ $auth_employee->getTotalLeaveCount() }}</h1>
                    <h6 class="text-white">Total Leaves</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col">
            <div class="card card-success card-warning">
                <div class="box text-center">
                    <h1 class="font-light text-white">{{ $auth_employee->getPendingCount() }}</h1>
                    <h6 class="text-white">Pending Leaves</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col">
            <div class="card card-success card-inverse">
                <div class="box text-center">
                    <h1 class="font-light text-white">{{ $auth_employee->getCasualCount() }}</h1>
                    <h6 class="text-white">Casual Leaves</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col">
            <div class="card card-inverse card-danger">
                <div class="box text-center">
                    <h1 class="font-light text-white">{{ $auth_employee->getPaidCount() }}</h1>
                    <h6 class="text-white">Paid Leaves</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col">
            <div class="card card-inverse card-primary">
                <div class="box text-center">
                    <h1 class="font-light text-white">
                        <span><big>{{ $auth_employee->getCompensationCount() }} </big><small class="text-muted">/ {{ $auth_employee->getExisingCompensationCount() }}</small></span>
                    </h1>
                    <h6 class="text-white">Compensates</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col">
            <div class="card card-inverse card-dark">
                <div class="box text-center">
                    <h1 class="font-light text-white">
                        <span><big>{{ $auth_employee->getRemainingCasualCount() }} </big><small class="text-muted">/ {{ $auth_employee->getAllowedCasualCount() }}</small></span>
                    </h1>
                    <h6 class="text-white">Remaining</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>

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
                                    <label class="">Status</label>
                                    {{ Form::select('status', $statuses, $request->status, ['class' => 'form-control', 'placeholder' => 'Select']) }}
                                </div>
                            </div>
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
                            <button type="button" class="btn btn-inverse" onclick="location.href ='{{ route('employee.leave.index') }}'">Reset</button>
                            @else
                            <button type="button" class="btn btn-inverse" onclick="location.href ='{{ route('employee.leave.index').'?scope='.$request->scope }}'">Reset</button>
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

<div id="RemarksModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Remarks</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>  
            {{ Form::open( ['url' => ['admin/leave/addremarks'],'method' => 'POST', 'class' => 'form-horizontal','id'=>'submitForm']) }}
            <div class="modal-body">                
                {!! Form::hidden('id','',array('id'=>'permission_id')) !!}
                {!! Form::hidden('status','',array('id'=>'permission_status')) !!}
                {!! Form::label('remarks','Remarks*') !!}
                {!! Form::textarea('remarks',null,['class'=>'form-control','rows' => 4]) !!}
            </div>                                        
            <div class="modal-footer">
                {!! Form::button('Submit', array('class'=>'btn btn-success', 'type' => 'submit')) !!}
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
            </div>
            {{ Form::close() }}
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>




@endsection

@include('layouts.partials.datatable_scripts')

@push('scripts')
<link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#date-range').datepicker({
            // toggleActive: true,
            format: 'yyyy-mm-dd',
            autoclose: true, todayHighlight: true,
        });

        $('#RemarksModal').on("shown.bs.modal", function (e) {
            var $relElem = $(e.relatedTarget);
            $('#permission_id').val($relElem.data('id'));
            $('#permission_status').val($relElem.data('status'));
        });

        $('#submitForm').submit(function (e) {
            e.preventDefault();
            $this = $(this);

            $.ajax({
                method: $this.attr('method'),
                url: $this.attr('action'),
                data: $this.serialize(),
                beforeSend: function () {
                    $this.find(':submit').buttonLoading();
                },
                complete: function () {
                    $this.find(':submit').buttonReset();
                },
                success: function (data) {
                    $("#submitForm")[0].reset();
                    $('#RemarksModal').modal('hide');
                    if (data.status == "A") {
                        swal("Accepted!", "Permission has been accepted.", "success");
                    } else {
                        swal("Declined!", "Permission has been declined.", "success");
                    }
                    $('#datatable-buttons').DataTable().draw(false);
                },
                error: function (jqXhr) {
                    swalError(jqXhr);
                }
            });
        });
        $('#collapseAdvanced').on('hidden.bs.collapse', function () {
            $('#advance-search-btn').html('Show Advanced Search');
            $('input[name="show"]').val('');
        }).on('shown.bs.collapse', function () {
            $('#advance-search-btn').html('Hide Advanced Search');
            $('input[name="show"]').val(1);
        });
    });


</script>
@endpush