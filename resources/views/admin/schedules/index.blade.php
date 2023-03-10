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

    <div id="createModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><span id="create_modalheader"></span></h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body"></div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@include('layouts.partials.datatable_scripts')

@push('stylesheets')
    <link href="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />   
    <style>
        div.dt-button-collection{
            width: 260px;
        }
    </style>
@endpush 
@push('scripts')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>

    <script type="text/javascript">
       
        $(document).ready(function () {
            $('.select2').select2();
            
            $('#createModal').on('hidden.bs.modal', function () {
                $('#createModal .modal-body').html('');
            });
        });

        function openCreateModal(type)
        {
            $.ajax({
                url: "{{ route('schedule.create') }}",
                method: 'GET',
                data: {
                    type: type,
                },
                success: function (html) {
                    $('#createModal').modal('show');
                    $('#createModal .modal-body').html(html);
                    $('#datepicker').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true, 
                        todayHighlight: true,
                    });
                }
            });
        }
        $('#createModal').on("shown.bs.modal", function (e) {
            if($('input[name="type"]').val() == 'OFFICIAL_PERMISSION_LEAVE_DAYS'){
                $('#create_modalheader').text('OFFICIAL PERMISSION LEAVE DAYS') ;
            }else if($('input[name="type"]').val() == 'TRAINEE_TO_PERMANENT'){
                $('#create_modalheader').text('TRAINEE TO PERMANENT') ;
            }else{
                $('#create_modalheader').text('OFFICE TIMING SLOT') ;
            }
        });
       
    </script>
@endpush
