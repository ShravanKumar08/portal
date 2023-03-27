@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('/css/datatable.css') }}">
@endpush

@push('container')
    <div id="DatatableViewModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">View</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body" id="datatableViewContent"></div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="dropdown-menu dropdown-menu-sm" id="context-menu">
    </div>
@endpush

@push('scripts')

    <script src="{{ asset('js/datatable.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    <script>
        (function ($, DataTable) {
            DataTable.ext.buttons.bulk_approve = {
                className: 'buttons-bulk',
                text: function (dt) {
                    return dt.i18n('buttons.bulk', '<i class="fa fa-check"></i> Approve');
                },
                action: function (e, dt, button, config) {
                    openBulkUpdateModal('A');
                }
            };
            DataTable.ext.buttons.bulk_decline = {
                className: 'buttons-bulk',
                text: function (dt) {
                    return dt.i18n('buttons.bulk', '<i class="fa fa-ban"></i> Decline</a>');
                },
                action: function (e, dt, button, config) {
                    openBulkUpdateModal('D');
                }
            };
            DataTable.ext.buttons.bulk_sent = {
                className: 'buttons-bulk',
                text: function (dt) {
                    return dt.i18n('buttons.bulk', '<i class="fa fa-plane"></i> Mark as Sent</a>');
                },
                action: function (e, dt, button, config) {
                    openBulkUpdateModal('S');
                }
            };
            DataTable.ext.buttons.bulk_inprogress = {
                className: 'buttons-bulk',
                text: function (dt) {
                    return dt.i18n('buttons.bulk', '<i class="fa  fa-clock-o"></i> In Progress</a>');
                },
                action: function (e, dt, button, config) {
                    openBulkUpdateModal('R');
                }
            };

            
            DataTable.ext.buttons.official_permission_leave = {
                className: 'buttons-schedule',
                text: function (dt) {
                    return dt.i18n('buttons.schedule', 'Official Permission Leave');
                },
                action: function (e, dt, button, config) {
                    openCreateModal('OFFICIAL_PERMISSION_LEAVE_DAYS');
                }
            };

            DataTable.ext.buttons.trainee_to_permanent = {
                className: 'buttons-schedule',
                text: function (dt) {
                    return dt.i18n('buttons.schedule', 'Trainee To Permanent');
                },
                action: function (e, dt, button, config) {
                    openCreateModal('TRAINEE_TO_PERMANENT');
                }
            };

            DataTable.ext.buttons.office_timing_slot = {
                className: 'buttons-schedule',
                text: function (dt) {
                    return dt.i18n('buttons.schedule', 'Office Timing Slot');
                },
                action: function (e, dt, button, config) {
                    openCreateModal('OFFICE_TIMING_SLOT');
                }
            };

            DataTable.ext.buttons.export_to_excel = {
                className: 'buttons-export',
                text: function (dt) {
                    return dt.i18n('buttons.export_to_excel', '<i class="fa fa-file-excel-o"></i> Excel');
                },
                action: function (e, dt, button, config) {
                    $('.export_all_csv').trigger('click');
                }
            };
            DataTable.ext.buttons.export_to_pdf = {
                className: 'buttons-export',
                text: function (dt) {
                    return dt.i18n('buttons.export_to_pdf', '<i class="fa fa-file-pdf-o"></i> PDF');
                },
                action: function (e, dt, button, config) {
                    $('.export_all_pdf').trigger('click');
                }
            };

        })(jQuery, jQuery.fn.dataTable);
    </script>
    {!! $dataTable->scripts() !!}
    <script type="text/javascript">
        $(document).ready(function () {
            $('#DatatableViewModal .modal-body').html($('#loader-content').html());

            $('#DatatableViewModal').on("shown.bs.modal", function (e) {
                var $relElem = $(e.relatedTarget);

                $.ajax({
                    method: "GET",
                    url: $relElem.data('url'),
                    success: function (html) {
                        $('#datatableViewContent').html(html);
                    },
                    error: function (jqXhr) {
                        $('#DatatableViewModal').modal('hide');
                        swalError(jqXhr);
                    }
                });
            }).on("hidden.bs.modal", function (e) {
                $('#DatatableViewModal .modal-body').html($('#loader-content').html());
            });

            $('body').on('click', '.btn-delete', function () {
                $this = $(this);

                swal({
                    title: "Are you sure?",
                    // text: "You will not be able to recover !",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true,
                }, function () {
                    $.ajax({
                        method: 'DELETE',
                        url: '/admin/' + $this.data('model') + '/' + $this.data('id'),
                        success: function () {
                            swal("Deleted!", "Your record has been deleted.", "success");
                            $('#datatable-buttons').DataTable().draw(false);
                        },
                        error: function (jqXhr) {
                            swalError(jqXhr);
                        }
                    });
                });
            });

            $('body').on('contextmenu','.rightclick', function(e) {
                var top = e.pageY - 5;
                var left = e.pageX - 5;

                $( "#context-menu" ).html("<a class='dropdown-item' href='"+$(this).attr("data-impersonate-url") +"'><i class='fa fa-sign-in'></i>&nbsp;Login as employee</a>");

                $("#context-menu").css({
                    display: "block",
                    top: top,
                    left: left
                }).addClass("show");
               
                return false; //blocks default Webbrowser right click menu
            }).on("click", function() {
                $("#context-menu").removeClass("show").hide();
            });

            $("body").on("click","#context-menu a", function() {
                $(this).parent().removeClass("show").hide();
            });

        });
    </script>
@endpush

@include('layouts.partials.datatable_export_scripts')
