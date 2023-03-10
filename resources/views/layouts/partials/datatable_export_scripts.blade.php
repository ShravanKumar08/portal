@push('scripts')
    <a href="javascript:void(0)" class="export_all_csv hide">Export to Excel</a>
    <a href="javascript:void(0)" class="export_all_pdf hide">Export to PDF</a>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">

    <div class="modal fade" id="export_progress_modal" tabindex="-1" role="dialog" aria-labelledby="export_progress_modal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    {{--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>--}}
                    <h4 class="modal-title" id="myModalLabel">Export</h4>
                </div>
                <div class="modal-body">
                    <div class="export_progress-label">Starting...</div>
                    <div id="export_progressbar"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger btn-sm" type="button" id="export_cancel_send">Cancel Export</button>
                    <form method="GET" action="" id="download_form">
                        <input name="filename" type="hidden"/>
                    </form>
                    {{--<a href="javascript:void(0)" download class="hide" id="filename">download</a>--}}
                </div>
            </div>
        </div>
    </div>

    <!--Export functionality ---->
    <script type="text/javascript">
        var xhr;
        var export_rows_count = export_progress_step = export_progress_count = 0;
        var row_offset = row_limit = 10;
        var filename = '', mode = '';

        $(document).ready(function () {
            var fKey = Object.keys(window.LaravelDataTables)[0];
            var oTable = window.LaravelDataTables[fKey];

            $('.export_all_csv, .export_all_pdf').on('click', function () {
                if($(this).hasClass('export_all_csv')){
                    var ext = '.xls';
                    var label = 'Export to Excel';
                    var action = "{{ url('force_download/excel') }}";
                    mode = 'excel';
                }else if ($(this).hasClass('export_all_pdf')){
                    var ext = '.txt';
                    var label = 'Export to PDF';
                    var action = "{{ url('force_download/pdf') }}";
                    mode = 'pdf';
                }

                $('#myModalLabel').html(label);
                $('#download_form').attr('action', action);

                filename = 'export_' + Math.random().toString(36).substring(5) + "_{{ time() }}" + ext;

                $( "#export_progress_modal" ).modal( "show" );
            });

            var export_progressbar = $( "#export_progressbar" ),
                export_progressLabel = $( ".export_progress-label" );

            $('#export_progress_modal').on('shown.bs.modal', function () {
                export_progressbar.progressbar( "value", 0);
                export_progress(0);
            });

            $('#export_progress_modal').on('hidden.bs.modal', function () {
                export_progressbar.progressbar( "value", 0);
                $('#export_cancel_send').removeClass('btn-success').addClass('btn-danger').text('Cancel Send');
            });

            $('#export_cancel_send').on('click', function () {
                exportcloseSend();
            });

            function export_progress(offset) {
                var val = export_progressbar.progressbar( "value" ) || 0;

                var data_params = $.extend({}, oTable.ajax.params(), {
                    row_offset: offset,
                    row_limit: row_limit,
                    export_to_all: mode,
                    filename: filename,
                });

                xhr = $.ajax({
                    method: 'get',
                    url: '{{ \Request::fullUrl() }}',
                    data: data_params,
                    success: function(response){
                        var new_row_offset = parseInt(offset) + parseInt(row_offset);
                        if (export_rows_count > new_row_offset) {
                            export_progress_count = parseInt(export_progress_count) + parseInt(row_offset);
                            export_progressbar.progressbar("value", val + export_progress_step);
                            export_progress(new_row_offset);
                        }else{
                            exportstopSend();
                            export_progressbar.progressbar( "value", 100 );
                            $('input[name="filename"]').val('/tmp/' + filename);
                            $('#download_form').submit();
                        }
                    },
                    error: function (request, textStatus, errorThrown) {
                        if (request.statusText != 'abort') {
                            exportstopSend();
                            alert('Failed to export');
                            export_progressbar.progressbar( "value", false );
                            export_progressLabel.text( "Starting..." );
                        }
                    }
                });
            }

            export_progressbar.progressbar({
                value: false,
                create: function(event, ui) {
                    $(this).find('.ui-widget-header').css({'background-color':'#00A8B3'})
                },
                change: function() {
                    export_progressLabel.text( "Current export_progress: " + Math.round(export_progressbar.progressbar( "value" )) + "% (" + export_progress_count + " of " + export_rows_count + " rows)" );
                },
                complete: function() {
                    export_progressLabel.text( "Complete! All Rows exported Successfully" );
                    $('#export_cancel_send').toggleClass('btn-success btn-danger').text('Close');
                }
            });

            function exportcloseSend() {
                exportstopSend();
                $("#export_progress_modal").modal("hide");
                export_progressbar.progressbar( "value", false );
                export_progressLabel.text( "Starting..." );
            }

            function exportstopSend(){
                if(xhr){
                    xhr.abort();
                }
            }
        });
    </script>
@endpush
