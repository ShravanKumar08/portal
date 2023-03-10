@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="text-right p-b-20">
                <a class="btn btn-sm btn-primary" id="advance-search-btn" data-toggle="collapse"
                   href="#collapseAdvanced" role="button" aria-expanded="false" aria-controls="collapseAdvanced">
                    {{ $request->show ? 'Hide' : 'Show' }} Advanced Search
                </a>
            </div>

            <!--            <div class="text-right p-b-20 ">
                            <div class='btn-group'>
                                <button type='button' class='btn dropdown-toggle btn-info btn-sm' data-toggle='dropdown'
                                        aria-haspopup='true' aria-expanded='false'>Bulk Change Status
                                </button>
                                <div class='dropdown-menu animated lightSpeedIn'>
                                    <li><a href="javascript:change_status(1)" data-id='' data-status='A' data-toggle='modal'
                                           data-target='#BulkChangeStatusModal' class='dropdown-item btn-status'>Approve</a></li>
                                    <li><a href="javascript:change_status(2)" data-id='' data-status='D' data-toggle='modal'
                                           data-target='#BulkChangeStatusModal' class='dropdown-item btn-status'>Decline</a></li>
                                </div>
                            </div>
                        </div>-->

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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="">Employee</label>
                                        {{ Form::select('employee_id[]', $employees_list, $request->employee_id, ['class' => 'form-control selectpicker','multiple data-style' => 'form-control btn-secondary']) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="">Status</label>
                                        {{ Form::select('status', $statuses, $request->status, ['class' => 'form-control', 'placeholder' => 'Select']) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="">Date Range</label>
                                        <div class="input-daterange input-group" id="date-range">
                                            {{ Form::text('from_date', $request->from_date, ['class' => 'form-control', 'placeholder' => 'From', 'autocomplete' => 'off']) }}
                                            <span class="input-group-addon bg-info b-0 text-white">to</span>
                                            {{ Form::text('to_date', $request->to_date, ['class' => 'form-control', 'placeholder' => 'To', 'autocomplete' => 'off']) }}
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
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Search</button>
                            {{-- @if($request->scope == null) --}}
                            @if(empty($_GET['scope']))
                                <button type="button" class="btn btn-inverse" onclick="location.href ='{{  route('userpermission.index').'?employeetype='.$request->employeetype }}'">Reset</button>
                            @else
                                <button type="button" class="btn btn-inverse" onclick="location.href ='{{  route('userpermission.index').'?scope='.$_GET['scope'].'&employeetype='.$request->employeetype }}'">Reset</button>
                            @endif

                            {{-- @else
                                {{-- <button type="button" class="btn btn-inverse"
                                        onclick="location.href ='{{  route('userpermission.index').'?scope='.$request->scope }}'">
                                    Reset
                                </button> --}}
                            {{-- @endif --}}
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'display nowrap table table-hover table-striped table-bordered', 'id' => 'bulkchangestatus_table_id']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="RemarksModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Approve / Decline Leave Request</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                {{ Form::open( ['url' => ['admin/userpermission/addremarks'],'method' => 'POST', 'class' => 'form-horizontal','id'=>'submitForm']) }}
                <div class="modal-body">
                    {!! Form::hidden('id','',array('id'=>'permission_id')) !!}
                    {!! Form::hidden('status','',array('id'=>'permission_status')) !!}
                    <div class="form-group date_time_div">
                    {!! Form::label('date','Date  *') !!}
                    {!! Form::text('date','',['class'=>'form-control' , 'id'=>'date-format' , 'autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group date_time_div">
                    {!! Form::label('time','Report Start Time *') !!}
                    {!! Form::text('time',null,['class'=>'clockpicker form-control' , 'id'=>'time-format' , 'autocomplete'=>'off']) !!}
                    </div>
                    {!! Form::label('remarks','Remarks') !!}
                    {!! Form::textarea('remarks',null,['class'=>'form-control','rows' => 4]) !!}
                </div>
                <div class="modal-body" id="changereport"></div>
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

    <div id="BulkChangeStatusModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><span id="bulkstatus_modalheader"></span></h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                {{ Form::open( ['url' => ['admin/userpermission/bulkchangestatus'],'method' => 'POST', 'class' => 'form-horizontal','id'=>'submitFormBulkChange']) }}
                <div class="modal-body" id="showBulkchanges">
                    {!! Form::hidden('id','',array('id'=>'bulk_permission_id')) !!}
                    {!! Form::hidden('status','',array('id'=>'bulk_permission_status')) !!}
                    {!! Form::label('remarks','Remarks') !!}
                    {!! Form::textarea('remarks',null,['class'=>'form-control','rows' => 4, 'id'=>'bulk_remarks' ]) !!}
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

    <div id="AuditModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Audits</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body" id="showAudits"></div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@include('layouts.partials.datatable_scripts')
@push('stylesheets')
    <link href="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />   
    <link href="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
   @endpush 
@push('scripts')
    <link href="{{ asset('assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script>
        var rows_selected = [];
        $(document).ready(function () {
            $('.select2').select2();

            $('#AuditModal .modal-body').html($('#loader-content').html());

            $('#date-format').datetimepicker({
                format : 'YYYY-MM-DD' ,
            });

            $('#date-range').datepicker({
                toggleActive: true,
                format: 'yyyy-mm-dd',
                autoclose: true, todayHighlight: true,
            });

            $('.clockpicker').clockpicker({
                autoclose:true,
            });

            $('#RemarksModal').on("shown.bs.modal", function (e) {
                var $relElem = $(e.relatedTarget);
                var status = $relElem.data('status');
                var date = $relElem.data('date');
                var time = $relElem.data('time');
                $('#permission_id').val($relElem.data('id'));
                $('#permission_status').val(status);
                $('#date-format').val(date);
                $('#time-format').val(time);
                if(status == 'U'){
                    $('.date_time_div').show();
                }else{
                    $('.date_time_div').hide();
                }

                $('#myModalLabel').html($relElem.html());
            });

            $('#RemarksModal').on("hidden.bs.modal", function (e) {
                $('#submitForm')[0].reset();
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
                        $('#bulkchangestatus_table_id').DataTable().draw(false);
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

            $('#AuditModal').on("shown.bs.modal", function (e) {
                var $relElem = $(e.relatedTarget);
                $this = $(this);
                var id = $relElem.data('permission_id');
                $.ajax({
                    method: "GET",
                    url: "{{ route('userpermission.audits') }}",
                    data: {userpermission_id: id},
                    success: function (data) {
                        $("#showAudits").html(data);
                        $('#bulkchangestatus_table_id').DataTable().draw(false);
                    },
                    error: function (jqXhr) {
                        swalError(jqXhr);
                    }
                });
            }).on("hidden.bs.modal", function (e) {
                $('#AuditModal .modal-body').html($('#loader-content').html());
            });

            // Array holding selected row IDs
            var table = window.LaravelDataTables["bulkchangestatus_table_id"];

            // Handle click on checkbox
            $('#bulkchangestatus_table_id tbody').on('click', 'input[type="checkbox"]', function (e) {
                var $row = $(this).closest('tr');

                // Get row data
                var data = table.row($row).data();

                // Get row ID
                var rowId = data.id;

                // Determine whether row ID is in the list of selected row IDs
                var index = $.inArray(rowId, rows_selected);

                // If checkbox is checked and row ID is not in list of selected row IDs
                if (this.checked && index === -1) {
                    rows_selected.push(rowId);

                    // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
                } else if (!this.checked && index !== -1) {
                    rows_selected.splice(index, 1);
                }

                if (this.checked) {
                    $row.addClass('table-active');
                } else {
                    $row.removeClass('table-active');
                }
                $('#bulk_permission_id').val(rows_selected);

                // Update state of "Select all" control
                updateDataTableSelectAllCtrl(table);

                // Prevent click event from propagating to parent
                e.stopPropagation();
            });

            $('#bulkchangestatus_table_id').on('draw.dt', function () {
                for (var i = 0; i < rows_selected.length; i++) {
                    $('input[type="checkbox"][value="' + rows_selected[i] + '"]').prop('checked', true).closest('tr').addClass('table-active');
                }
            });
                
            // Handle click on table cells with checkboxes
            $('#bulkchangestatus_table_id').on('click', 'tbody td:not(:has(input[type="checkbox"]))', function (e) {
                $(this).parent().find('input[type="checkbox"]').trigger('click');
            });

            // Handle click on "Select all" control
            $('#select-all').on('click', function (e) {
                if (this.checked) {
                    $('#bulkchangestatus_table_id tbody input[type="checkbox"]:not(:checked)').trigger('click');
                } else {
                    $('#bulkchangestatus_table_id tbody input[type="checkbox"]:checked').trigger('click');
                }
                $('#bulk_permission_id').val(rows_selected);
                // Prevent click event from propagating to parent
                e.stopPropagation();
            });

            // Handle table draw event
            table.on('draw', function () {
                // Update state of "Select all" control
                updateDataTableSelectAllCtrl(table);
            });

            $('#BulkChangeStatusModal').on("shown.bs.modal", function (e) {
                $('#bulk_permission_status').val() === 'A' ? $('#bulkstatus_modalheader').text('Approved') :  $('#bulkstatus_modalheader').text('Declined');
            });
               
            $('#submitFormBulkChange').submit(function (e) {
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
                        $("#submitFormBulkChange")[0].reset();
                        $('#BulkChangeStatusModal').modal('hide');
                        if ($('#bulk_permission_status').val() == "A") {
                            swal("Accepted!", "Permission has been accepted.", "success");
                        } else {
                            swal("Declined!", "Permission has been declined.", "success");
                        }
                        rows_selected = [];
                        $('#bulkchangestatus_table_id').DataTable().draw(false);
                    },
                    error: function (jqXhr) {
                        swalError(jqXhr);
                    }
                });
            });
            
        });

        function openBulkUpdateModal(status) {
            if (!(rows_selected.length > 0)) {
                swal({
                    title: 'Invalid Data',
                    text: "Please check atleast one checkbox!!",
                    html: true,
                    type: 'error'
                });
            }else{
                    $('#bulk_permission_status').val(status);
                    $('#BulkChangeStatusModal').modal('show');
            }
        }

        function updateDataTableSelectAllCtrl(table) {
            var $table = table.table().node();
            var $chkbox_all = $('tbody input[type="checkbox"]', $table);
            var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
            var chkbox_select_all = $('thead input[name="select_all"]', $table).get(0);

            // If none of the checkboxes are checked
            if ($chkbox_checked.length === 0) {
                chkbox_select_all.checked = false;
                if ('indeterminate' in chkbox_select_all) {
                    chkbox_select_all.indeterminate = false;
                }

                // If all of the checkboxes are checked
            } else if ($chkbox_checked.length === $chkbox_all.length) {
                chkbox_select_all.checked = true;
                if ('indeterminate' in chkbox_select_all) {
                    chkbox_select_all.indeterminate = false;
                }

                // If some of the checkboxes are checked
            } else {
                chkbox_select_all.checked = true;
                if ('indeterminate' in chkbox_select_all) {
                    chkbox_select_all.indeterminate = true;
                }
            }
        }
    </script>
@endpush