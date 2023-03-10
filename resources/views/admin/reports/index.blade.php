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
                    {{ Form::hidden('scope', $request->scope,['id' => 'scope']) }}
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
                                    @php
                                        $status = $request->scope == 'noreport' ? 'N' : $request->status;
                                    @endphp
                                    {{ Form::select('status', $statuses, $status, ['class' => 'form-control', 'placeholder' => 'Select','id' => 'status']) }}
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
                                <div class="col-md-3">
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
                        <button type="submit" class="btn btn-success" id ="submit"> <i class="fa fa-check"></i> Search</button>
                        @if($request->scope == null)
                            <button type="button" class="btn btn-inverse" onclick="location.href ='{{  route('report.index') }}'">Reset</button>
                            @else
                            <button type="button" class="btn btn-inverse" onclick="location.href ='{{  route('report.index').'?scope='.$request->scope }}'">Reset</button>
                       @endif
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
<div id="RemarksModal" class="modal fade in" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Make Progress / Decline / Mark as sent  Report Request</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            {{ Form::open( ['url' => ['admin/report/addremarks'],'method' => 'POST', 'class' => 'form-horizontal','id'=>'submitForm']) }}
            <div class="modal-body">
                {!! Form::hidden('id','',array('id'=>'report_id')) !!}
                {!! Form::hidden('status','',array('id'=>'report_status')) !!}
              <div class="form-group">
                <div id="end_time_div">
                {!! Form::label('end','End Time*') !!}
                {!! Form::text('end','',['class'=>'form-control clockpicker','autocomplete'=>'off', 'id'=>'endtime']) !!}
                </div>
                {!! Form::label('remarks','Remarks*') !!}
                {!! Form::textarea('remarks',null,['class'=>'form-control','rows' => 4]) !!}
                <div id="release_items"></div>
            </div>
                <div class="show-releaserequest">
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

<div id="releaseModal" class="modal fade in" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Release Request</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" id="releaserequest"></div>            
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
    </div>
</div>

<div id="BulkChangeStatusModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-lg">
   <div class="modal-content">
       <div class="modal-header">
           <h4 class="modal-title" id="myModalLabel"><span id="bulkstatus_modalheader"></span></h4>
           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
       </div>
       {{ Form::open( ['url' => ['admin/report/bulkchangestatus'],'method' => 'POST', 'class' => 'form-horizontal','id'=>'submitFormBulkChange']) }}
       <div class="modal-body" id="showBulkchanges">
           {!! Form::hidden('id','',array('id'=>'bulk_report_id')) !!}
           {!! Form::hidden('status','',array('id'=>'bulk_report_status')) !!}
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

@endsection

@include('layouts.partials.datatable_scripts')
@include('employee.reports.partials.copytoclipboardscripts')


@push('stylesheets')
    <link href="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" /> 
    <link href="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
@endpush 

@push('scripts')
    <!-- Toggle button CSS -->
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <!-- Toggle button CSS -->
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>
    

    <script type="text/javascript">
    var rows_selected = [];
        $(document).ready(function () {
            $('.select2').select2();
           
            //Add scope value in pending status and no report status 
            var scope = $('#scope').val();
            if(scope == 'pending' || scope == '' || scope == 'noreport'){
                $('#status').on('change', function () {
                    if ($('#status').val() == 'P')
                        $("#scope").val("pending");
                    else if ($('#status').val() == 'N'){
                        $("#scope").val("noreport");
                    }
                    else
                        $("#scope").val("");
                });
                $('#submit').on('click',function(){
                    if ($('#status').val() == 'N')
                        $("#status").val("P");
                });   
            }
            
            $('#releaseModal .modal-body').html($('#loader-content').html());
            $('#date-range').datepicker({
                // toggleActive: true,
                format: 'yyyy-mm-dd',
                autoclose: true, todayHighlight: true,
            });
            $('.clockpicker').clockpicker({
                autoclose:true,
            });

            $('#RemarksModal').on("shown.bs.modal", function (e) {
                var $relElem = $(e.relatedTarget);
                var report_id = $('#report_id').val($relElem.data('id'));
                var report_status = $('#report_status').val($relElem.data('status'));
                $('#end_time_div')[$relElem.data('showendtime') ? 'removeClass' : 'addClass']('hide');
                
                if($relElem.data('haslock') == 1){
                    $.ajax({
                        method: "GET",
                        url: "{{ route('report.releaserequest') }}",
                        data: { report_id : $relElem.data('id') , haslockValue : $relElem.data('haslock') },
                        success: function (html) {
                            $("#release_items").html(html);
                            $('.toggle_values').bootstrapToggle({
                                on: 'Lock',
                                off: 'Release'
                            });
                        },
                        error: function () {
                            swalError(jqXhr);
                        }
                    });
                }else{
                    $("#release_items").html('');
                }
               
                if($relElem.data('showendtime')){
                    $.ajax({
                        method: "post",
                        url: "{{ route('report.getendtime') }}",
                        data: { report_id : $relElem.data('id')  },
                        success: function (data) {
                            $("#endtime").val(data);
                        },
                        error: function () {
                            swalError(jqXhr);
                        }
                    });
                }
                
            });

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
                    $('#bulkchangestatus_table_id').on('draw.dt', function () {
                        for (var i = 0; i < rows_selected.length; i++) {
                            $('input[type="checkbox"][value="' + rows_selected[i] + '"]').prop('checked', true).closest('tr').addClass('table-active');
                        }
                    });
                    $('#bulk_report_id').val(rows_selected);

                    // Update state of "Select all" control
                    updateDataTableSelectAllCtrl(table);

                    // Prevent click event from propagating to parent
                    e.stopPropagation();
                });

                // Handle click on table cells with checkboxes
                $('#bulkchangestatus_table_id').on('click', 'tbody td:not(:has(input[type="checkbox"]))', function (e) {
                    $(this).parent().find('input[type="checkbox"]').trigger('click');
                });

                // Handle click on "Select all" control
                $('#select-all').on('click', function(e){
                    if (this.checked) {
                        $('#bulkchangestatus_table_id tbody input[type="checkbox"]:not(:checked)').trigger('click');
                    } else {
                        $('#bulkchangestatus_table_id tbody input[type="checkbox"]:checked').trigger('click');
                    }
                    $('#bulk_report_id').val(rows_selected);
                    
                    // Prevent click event from propagating to parent
                    e.stopPropagation();
                    
                });

                // Handle table draw event
                if(scope != 'releaselock'){
                    table.on('draw', function () {
                        // Update state of "Select all" control
                        updateDataTableSelectAllCtrl(table);
                    });
                }


                $('#BulkChangeStatusModal').on("shown.bs.modal", function (e) {
                    if($('#bulk_report_status').val() === 'A')  
                        $('#bulkstatus_modalheader').text('Approved')
                    else if($('#bulk_report_status').val() === 'D')
                        $('#bulkstatus_modalheader').text('Declined');
                    else if($('#bulk_report_status').val() === 'R')
                        $('#bulkstatus_modalheader').text('In Progress');
                    else 
                        $('#bulkstatus_modalheader').text('Mark as Sent');
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
                            if ($('#bulk_report_status').val() == "A") {
                                swal("Accepted!", "Report has been accepted.", "success");
                            } else if ($('#bulk_report_status').val() == "D"){
                                swal("Declined!", "Report has been declined.", "success");
                            } else if($('#bulk_report_status').val() === 'R'){
                                swal("Report-In-Progress", "Report has been in processing.", "success");
                            } else {
                                swal("Sent!", "Report has been Sent.", "success");
                            }
                            rows_selected = [];
                            $('#bulkchangestatus_table_id').DataTable().draw(false);
                        },
                        error: function (jqXhr) {
                            swalError(jqXhr);
                        }
                    });
                });

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

            
            $('#releaseModal').on("shown.bs.modal", function (e) {            
                var $relElem = $(e.relatedTarget);
                $this = $(this);
                var id = $relElem.data('report_id');
                $.ajax({
                    method: "GET",
                    url: "{{ route('report.releaserequest') }}",
                    data: { report_id : id },
                    success: function (html) {
                        $("#releaserequest").html(html);
                        $('.toggle_values').bootstrapToggle({
                            on: 'Lock',
                            off: 'Release'
                        });
                    },
                    error: function () {
                        swalError(jqXhr);
                    }
                });
            }).on("hidden.bs.modal", function (e) {
                $('#releaseModal .modal-body').html($('#loader-content').html());
            });
            $('body').on('submit', '#submitForm',function (e) {
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
                        if (data.status == "R") {
                            swal("Make Progress!", "Report has been progressed.", "success");
                        }else if (data.status == "A") {
                            swal("Approved!", "Report has been approved.", "success");
                        }else  if (data.status == "D"){
                            swal("Declined!", "Report has been declined.", "success");
                        } else{
                            swal("Sent!", "Report has been Sent.", "success");

                        }
                        $('#bulkchangestatus_table_id').DataTable().draw(false);

                    },
                    error: function (jqXhr) {
                        swalError(jqXhr);
                    }
                });
            });

            $('body').on('submit', '#ReleaserequestForm', function (e) {
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
                        $('#releaseModal').modal('hide');
                        swal("Lock updated!", "Release lock changed..!", "success");
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
        function openBulkUpdateModal(status) {
            if (!(rows_selected.length > 0)) {
                swal({
                    title: 'Invalid Data',
                    text: "Please check atleast one checkbox!!",
                    html: true,
                    type: 'error'
                });
            }else{
                    $('#bulk_report_status').val(status);
                    $('#BulkChangeStatusModal').modal('show');
            }
        }
    </script>
@endpush
