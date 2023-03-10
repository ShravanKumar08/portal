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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="">Status</label>
                                    {{ Form::select('status', $statuses, $request->status, ['class' => 'form-control', 'placeholder' => 'Select']) }}
                                </div>
                            </div>   
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Search</button>
                        @if($request->scope == null)
                            <button type="button" class="btn btn-inverse" onclick="location.href ='{{  route('compensation.index') }}'">Reset</button>
                            @else
                            <button type="button" class="btn btn-inverse" onclick="location.href ='{{  route('compensation.index').'?scope='.$request->scope }}'">Reset</button>
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

    <div id="RemarksModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
               <h4 class="modal-title" id="myModalLabel">Mark as Paid</h4>
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
           </div>
           {{ Form::open( ['url' => ['admin/compensation/addremarks'],'method' => 'POST', 'class' => 'form-horizontal','id'=>'submitForm']) }}
           <div class="modal-body">
               <div class="form-group">
               {!! Form::hidden('id','',array('id'=>'compensation_id')) !!}
               {!! Form::hidden('is_paid','',array('id'=>'is_paid')) !!}
               {!! Form::label('remarks','Remarks') !!}
               {!! Form::textarea('remarks',null,['class'=>'form-control','rows' => 4]) !!}
               </div>
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

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('/css/datatable.css') }}">
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css" rel="stylesheet">
    <link href="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />   
@endpush 

@push('scripts')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
    
    <script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
    <script src="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>
    
    <script type="text/javascript">
        $(document).ready(function () {
            $('.select2').select2();
            var ispaid;
             
            $('#date-range').datepicker({
                toggleActive: true,
                format: 'yyyy-mm-dd',
                autoclose: true, todayHighlight: true,
            });

            $('#RemarksModal').on("shown.bs.modal", function (e) {
                var $relElem = $(e.relatedTarget);
                ispaid = $relElem.data('is_paid');
                $("#is_paid").attr('value',ispaid);
                $('#compensation_id').val($relElem.data('id'));
              
                $('#myModalLabel').html($relElem.html());
            });

            $('#RemarksModal').on("hidden.bs.modal", function (e) {
                $('#submitForm')[0].reset();
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
                        if(ispaid == 1){
                            swal("Updated!", "Your Record has been set to paid.", "success");
                        } else if(ispaid == 2){
                            swal("Updated!", "Your Record has been converted to paid.", "success");
                        }  else if(ispaid == -1){
                            swal("Updated!", "Your Record has been set to No Compensation.", "success");
                        } else {
                            swal("Updated!", "Your Record has been Unmark to paid.", "success");
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

 
    
  