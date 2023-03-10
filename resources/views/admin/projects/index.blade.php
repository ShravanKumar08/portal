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

    <div id="AuditModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

@push('scripts')
<script>
    $(document).ready(function () {
        $('body').on('click', '.btn-status', function () {
            $this = $(this);

            $.ajax({
                method: 'POST',
                url: '/admin/' + 'project/' + $this.data('id'),
                data: {active: $this.data('active')},
                beforeSend: function () {
                    // $this.button('loading')
                },
                complete: function () {
                    // $this.button('reset')
                },
                success: function (data) {
                    if (data.active == '1') {
                        swal("Active!", "Project has been Activated.", "success");
                    } else {
                        swal("Inactive!", "Project has been Inactivated.", "success");
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

        $('#AuditModal .modal-body').html($('#loader-content').html());
            $('#AuditModal').on("shown.bs.modal", function (e) {
                var $relElem = $(e.relatedTarget);
                $this = $(this);
                var id = $relElem.data('project_id');
                $.ajax({
                    method: "GET",
                    url: "{{ route('project.audits') }}",
                    data: { project_id : id },
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

    });
</script>
@endpush    
