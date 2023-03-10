@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="contact-page-aside card-body">
                
                <div class="text-right p-b-20">
            <a class="btn btn-sm btn-primary" id="advance-search-btn" data-toggle="collapse" href="#collapseAdvanced" role="button" aria-expanded="false" aria-controls="collapseAdvanced">
                {{ @$request->show ? 'Hide' : 'Show' }} Advanced Search
            </a>
        </div>
        <div class="collapse {{ @$request->show ? 'show' : '' }}" id="collapseAdvanced">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h4 class="m-b-0 text-white"><i class="fa fa-search"></i> Advanced search</h4>
                </div>
                <div class="card-body">
                    {{ Form::open(['url' => \URL::current(), 'class' => 'form-horizontal','method' => 'GET']) }}
                    {{ Form::hidden('show', @$request->show) }}
                    {{ Form::hidden('scope', @$request->scope) }}
                    {{ Form::hidden('employeetype', @$request->employeetype) }}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="">Designation</label>
                                    {{ Form::select('designation_id', @$designations, @$request->designation_id, ['class' => 'form-control select2', 'placeholder' => 'Select']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Search</button>
                            @if(empty($_GET['status']))
                                <button type="button" class="btn btn-inverse" onclick="location.href ='{{  route('employee.index').'?employeetype='.$request->employeetype }}'">Reset</button>
                            @else
                                <button type="button" class="btn btn-inverse" onclick="location.href ='{{  route('employee.index').'?status='.$_GET['status'].'&employeetype='.$request->employeetype }}'">Reset</button>
                            @endif
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
                
                <!-- .left-aside-column-->

                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'display nowrap table table-hover table-striped table-bordered', 'id' => 'datatable-buttons']) !!}
                    </div>
                    <!-- .left-aside-column-->
                </div>
                <!-- /.left-right-aside-column-->
            </div>
        </div>
    </div>
</div>
@endsection

@include('layouts.partials.datatable_scripts')

@push('stylesheets')    
<link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>

<script>
    $(document).ready(function () {
        $('.select2').select2();
         
        $('body').on('submit', '#designationModal', function (e) {
            e.preventDefault();
            var DesignationForm = $(this);

            $.ajax({
                url: DesignationForm.attr('action'),
                type: DesignationForm.attr('method'),
                data: DesignationForm.serialize(),
                beforeSend: function () {
                    DesignationForm.find(':submit').buttonLoading();
                },
                complete: function () {
                    DesignationForm.find(':submit').buttonReset();
                },
                success: function (data) {
                    $('.left-aside.bg-light-part ul br').before('<li><a href="javascript:void(0)">' + $('#designationModal input[name="name"]').val() +' <span>0</span></a></li>');
                    $("#designationModal")[0].reset();
                    swal("Created!", "Designation Created Successfully", "success");
                    $('#myModal').modal('hide');
                },
                error: function (jqXhr) {
                    swalError(jqXhr);
                }
            });
        });

        $('body').on('click', '.btn-status', function () {
            $this = $(this);

            $.ajax({
                method: 'POST',
                url: '/admin/' + 'user/' + $this.data('id'),
                data: {active: $this.data('active')},
                beforeSend: function () {
                    // $this.button('loading')
                },
                complete: function () {
                    // $this.button('reset')
                },
                success: function (data) {
                    if (data.active == '1') {
                        swal("Active!", "Employee has been Activated.", "success");
                    } else {
                        swal("Inactive!", "Employee has been Inactivated.", "success");
                    }
                    $('#datatable-buttons').DataTable().draw(false);
                },
                error: function (xhr) {
                    var msg = 'Failed to delete';
                    if (typeof xhr.responseJSON.message != 'undefined') {
                        msg = msg + ' ' + xhr.responseJSON.message;
                    }
                    swal("Failed to Delete", msg, "error");
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
