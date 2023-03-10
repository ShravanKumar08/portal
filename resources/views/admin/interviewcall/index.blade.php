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

@push('scripts')
<script>


    $(document).ready(function () {
            
        $('body').on('click', '.btn-status', function () {
            $this = $(this);

            $.ajax({
                method: 'POST',
                url: '/admin/' + 'interviewstatus/' + $this.data('id'),
                data: {active: $this.data('active')},
                success: function (data) {
                    if (data.active == '1') {
                        swal("Active!", " Status has been Activated.", "success");
                    } else {
                        swal("Inactive!", "  Status has been Inactivated.", "success");
                    }
                    $('#datatable-buttons').DataTable().draw(false);
                },
                complete: function () {
                    $this.button('reset')
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
    });
</script>
@endpush    