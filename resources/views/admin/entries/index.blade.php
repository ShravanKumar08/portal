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
                    {{ Form::hidden('employeetype', $request->employeetype) }}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="">Employee</label>
                                    {{ Form::select('employee_id[]', $employees_list, $request->employee_id, ['class' => 'form-control selectpicker','multiple data-style' => 'form-control btn-secondary']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="">Date Range</label>
                                    <div class="input-daterange input-group" id="date-range">
                                        {{ Form::text('from_date', $request->from_date, ['class' => 'form-control datepicker', 'placeholder' => 'From', 'autocomplete' => 'off']) }}
                                        <span class="input-group-addon bg-info b-0 text-white">to</span>
                                        {{ Form::text('to_date', $request->to_date, ['class' => 'form-control datepicker', 'placeholder' => 'To', 'autocomplete' => 'off']) }}
                                    </div>
                                </div>
                            </div> 
                            @if($is_admin_route)
                                <div class="col-md-3 text-center">
                                    <label for="checkbox3">Show Inactive</label>
                                    <div class="form-group pt-2">
                                        {{ Form::checkbox('inactive_employee', 1,$request->inactive_employee, ['class' => 'checkbox checkbox-success','id' => 'checkbox3']) }}
                                        <label for="checkbox3"></label>
                                    </div>
                                </div>
                            @endif  
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Search</button>
                        @if(empty($_GET['scope']))
                            <button type="button" class="btn btn-inverse" onclick="location.href ='{{  route('entry.index').'?employeetype='.$request->employeetype }}'">Reset</button>
                        @else
                            <button type="button" class="btn btn-inverse" onclick="location.href ='{{  route('entry.index').'?scope='.$_GET['scope'].'&employeetype='.$request->employeetype }}'">Reset</button>
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

    <div id="RemarksModal" class="modal fade in" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Make Approve / Decline Timer On Request</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                {{ Form::open( ['url' => ['admin/entry/addremarks'],'method' => 'POST', 'class' => 'form-horizontal','id'=>'submitForm']) }}
                <div class="modal-body">
                    {!! Form::hidden('id','',array('id'=>'entry_id')) !!}
                    {!! Form::hidden('status','',array('id'=>'entry_status')) !!}
                  <div class="form-group">
                    <div id="end_time_div">
                    {!! Form::label('end','End Time*') !!}
                    {!! Form::text('end',null,['class'=>'form-control clockpicker','autocomplete'=>'off']) !!}
                    </div>
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
    </div>
    @include('layouts.partials.entry_index_scripts',['entryurl' => url('admin/entry/entryitems')])
@endsection

@include('layouts.partials.datatable_scripts')

@push('stylesheets')
    <link href="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" /> 
    <link href="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#RemarksModal').on("shown.bs.modal", function (e) {
                var $relElem = $(e.relatedTarget);
                $('#entry_id').val($relElem.data('id'));
                $('#entry_status').val($relElem.data('status'));
                $('#end_time_div')[$relElem.data('showendtime') ? 'removeClass' : 'addClass']('hide');
            });

            $('.clockpicker').clockpicker({
                autoclose:true,
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
                            swal("Approved!", "Timer has been Started.", "success");
                        }else {
                            swal("Declined!", "Report has been declined.", "success");
                        }
                        $('#datatable-buttons').DataTable().draw(false);
                    },
                    error: function (jqXhr) {
                        swalError(jqXhr);
                    }
                });
            });

        });
    </script>
@endpush