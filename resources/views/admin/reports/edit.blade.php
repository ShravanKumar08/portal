@extends('layouts.master')

@section('content')
    <div class="row justify-content-md-center">
        <div class="col-7">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($Model,['route' => [ "report.update", $Model->id],'method' => 'PUT', 'class' => 'form-horizontal']) }}
                    @include('admin.reports.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>


     <div class="col-7 pull-right">
     <button class="btn btn-sm btn-primary btn-edit " title="View" data-url="' . url('employee/report', $model->id) . '" data-itemid="" data-toggle="modal" data-target="#ReportEditModal">
            <i class="fa fa-edit"></i>ADD</button>
     </div>


     <div id="getreportitems" style="clear: both;">
        @include('layouts.partials.reportitemstable',['action'=> true, 'condition' => 'admin-report-edit'])
     </div>

<div id="ReportEditModal" class="modal fade in" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Edit Reports</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" id="edit_Reports"></div>            
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>

@endsection


@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">
<link rel="stylesheet" href="{{ mix('/css/report.css') }}">
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">

<style type="text/css">
    .clockpicker-popover {
    z-index:9999 !important;
}</style>
<script src="{{ mix('/js/report.js')  }}"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                // getReportItemForm({mode: 'new', report_id: '{{ $Report->id }}'});

                // getReportitems();

                initelement();

                //Report Item Save
                $('body').on('submit', '#reportform', function (e) {
                    e.preventDefault();

                    $this = $(this);

                    $.ajax({
                        method: $this.attr('method'),
                        url: $this.attr('action'),
                        data: $this.serialize(),
                        beforeSend: function () {
                            $this.find(':submit').buttonLoading();
                        },
                        success: function (data) {
                            $('#ReportEditModal').modal('hide');
                            getReportItemForm({mode: 'new', report_id: '{{ $Report->id }}'});

                            $.toast({
                                heading: 'Success',
                                text: data.id ? 'Report updated in the list' : 'Report added in the list',
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'info',
                            });

                            getReportitems();
                        },
                        complete: function () {
                            $this.find(':submit').buttonReset();
                            // $('body, html').animate({scrollTop: $this.offset().top}, 'slow');
                        },
                        error: function (jqXhr) {
                            swalError(jqXhr);
                        }
                    });
                });

                // Add permission start & end times to reportitems
                $('body').on('change', '#technology_id', function (e) {
                    e.preventDefault();

                    $this = $(this);
                    var permission = $this.children("option:selected").val();
                    var report_id = $("#hidden-report-id").val();
                    $.ajax({
                        method: 'POST',
                        url: "{{ route('report.setPermissionTime') }}",
                        data: {permission: permission, report_id: report_id},
                        success: function (data) {
                            if (data['success'] == 'Success') {
                                $("#start_time").val(data['start']);
                                $("#end_time").val(data['end']);
                            }
                        },
                    });
                })

                // Change 24 hrs format to 12hr in reportitems   
                $('body').on('change', '.changeTimeFormat', function (e) {
                    var formatted = moment($(this).val(), "HH:mm:ss").format("hh:mm A");
                    $(this).next("span").text(formatted);

                    var start_time = $("#start_time").val();
                    var end_time = $("#end_time").val();
                    var start = moment.utc(start_time, "HH:mm");
                    var end = moment.utc(end_time, "HH:mm");
                    if (end.isBefore(start)) end.add(1, 'day');
                    var inseconds = moment.duration(end.diff(start));
                    var Elapsed = moment.utc(+inseconds).format('HH:mm');
                    $("#elapsed").text('('+Elapsed+')');  
                });


                // Edit Report item
                $('body').on('click', '#items tbody tr .btn-edit', function (e) {
                    getReportItemForm({id: $(this).data('id'), mode: 'edit'});
                    $('body, html').animate({scrollTop: 0}, 'slow');
                });

                // Copy Report item
                $('body').on('click', '#items tbody tr .btn-copy', function () {
                    getReportItemForm({id: $(this).data('id'), mode: 'copy'});
                    $('body, html').animate({scrollTop: 0}, 'slow');
                });

                // Cancel Report item
                $('body').on('click', '.btn-cancel-edit', function () {
                    getReportItemForm({mode: 'new', report_id: '{{ $Report->id }}'});
                });

                // Delete Report items
                $('body').on('click', '#items tbody tr .btn-delete', function (e) {
                    var id = $(this).data('itemid');
                    swal({
                        title: "Are you sure?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true,
                    }, function () {
                        $.ajax({
                            method: "DELETE",
                            url: "{{ route('report.deleteReportitems') }}",
                            data: {id: id},
                            dataType: 'json',
                            success: function (data) {
                                swal("Deleted", "Row Deleted", "success");
                                getReportitems();
                            },
                            error: function () {
                                swal("Failed", "Something went wrong", "error");
                            }
                        });
                    });
                });

                //Show or Hide Fields
                $('body').on('change', '#technology_id', function (e) {
                    disableFormElements();
                });

                // Send Report items
                $('#report-send').click(function (e) {
                    swal({
                        title: "Are you sure?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, Send it!",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true,
                    }, function () {
                        $.ajax({
                            method: "{{ $formMethod }}",
                            url: "{{ $formUrl }}",
                            data: {id: '{{ $Report->id }}'},
                            success: function (data) {
                                swal("Sent", "Your Report send successfully!", "success");

                                setTimeout(function () {
                                    window.location.replace("{{ route('employee.report.index') }}");
                                }, 2000);
                            },
                            error: function (jqXhr) {
                                swal({
                                    title: errorTitle(jqXhr),
                                    text: errorMessage(jqXhr),
                                    html: true,
                                    type: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#DD6B55",
                                    confirmButtonText: "Yes, Send it Anyway!",
                                    closeOnConfirm: false,
                                    showLoaderOnConfirm: true,
                                }, function () {
                                    $.ajax({
                                        method: "{{ $formMethod }}",
                                        url: "{{ $formUrl }}",
                                        data: {id: '{{ $Report->id }}', novalidate: 'endtime'},
                                        success: function (data) {
                                            swal("Sent", "Your Report send successfully!", "success");

                                            setTimeout(function () {
                                                window.location.replace("{{ route('employee.report.index') }}");
                                            }, 2000);
                                        },
                                        error: function (jqXhr) {
                                            swalError(jqXhr);
                                        }
                                    });
                                });
                            }
                        });
                    });
                });
            });

            //Release break lock
            $('body').on('shown.bs.modal', '#releaseLockModal', function (e) {
                var $relElem = $(e.relatedTarget);
                var report_id = $relElem.data('report_id');
                $.ajax({
                    method: 'GET',
                    url: "{{ route('employee.report.releaselockbreak') }}",
                    data: {'report_id': report_id},
                    beforeSend: function () {
                        $(this).button('loading')
                    },
                    complete: function () {
                        $(this).button('reset')
                    },
                    success: function (html) {
                        $("#releaseLockModal .modal-body").html(html);
                        $('.toggle_value').bootstrapToggle({
                            on: 'Lock',
                            off: 'Release'
                        });
                    },
                    error: function (jqXhr) {
                        swalError(jqXhr);
                    }
                });
            }).on('hidden.bs.modal', function () {
                $('#releaseLockModal .modal-body').html($('#loader-content').html());
            });

            $('body').on('submit', '#submitreleaseForm', function (e) {
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
                        $("#submitreleaseForm")[0].reset();
                        $('#releaseLockModal').modal('hide');
                        swal("Done!", "Release lock request sent successfully.!!", "success");
                        $('#datatable-buttons').DataTable().draw(false);
                    },
                    error: function (jqXhr) {
                        swalError(jqXhr);
                    }
                });
            });

            // Github popup
            $('body').on('click', '.GithubModal', function (e) {
                $('#githubModal').modal('show');
                var $relElem = $(e.relatedTarget);
                var report_id = $relElem.data('report_id');
                $('#githubModal #loader-content').show();
                if ($('#github-table').length == 0) {
                    getGithubCommits(report_id);
                }
            });
            
            // Github apply work
            $('body').on('click', '.apply-commit', function (e) {
                $('#works').val($(this).closest('tr').find('.apply-message').html());
                $('#githubModal').modal('hide');

                setTimeout(function () {
                    $('#works').focus();
                }, 1000);
            });

            // Github refresh button
            $('body').on('click', '.github-refresh', function (e) {
                e.preventDefault();
                $('#githubModal .modal-body').html($('#loader-content').html());
                var $relElem = $(e.relatedTarget);
                var report_id = $relElem.data('report_id');
                getGithubCommits(report_id);
            });

            function getGithubCommits(report_id) {
                $.ajax({
                    method: 'GET',
                    url: "{{ route('employee.report.getgithubCommits') }}",
                    data: {'report_id': report_id},
                    beforeSend: function () {
                        $(this).button('loading')
                    },
                    complete: function () {
                        $(this).button('reset')
                    },
                    success: function (html) {
                        $("#githubModal .modal-body").html(html);
                        $('.toggle_value').bootstrapToggle({
                            on: 'Lock',
                            off: 'Release'
                        });
                    },
                    error: function (jqXhr) {
                        $("#githubModal .modal-body").html(errorMessage(jqXhr));
                        swalError(jqXhr);
                    }
                });
            }

            function getReportitems() {
                //Show Table
                var timer_on = '{{ \Carbon\Carbon::parse($Report->start)->format('H:i') }}';

                $.ajax({
                    method: 'get',
                    url: "{{ route('report.getReportitems') }}",
                    dataType: 'html',
                    data: {'id': "{{ @$Report->id }}"},
                    success: function (data) {
                        $("#getreportitems").html(data);
                        if ($('#items tbody tr.norecord').length == 1) {
                            $("#reports_save").hide();
                            $('#start_time').val(timer_on);
                            $('#end_time').val(timer_on);
                        } else {
                            // $('#start_time').val($('#report-mismatch-time-start').html());
                            // $('#end_time').val($('#report-mismatch-time-end').html());

                            $.fn.editable.defaults.mode = 'inline';
                            var reportitem_id = $(this).data('id');
                            initXeditableText(reportitem_id);
                            initXeditableTechnology(reportitem_id);
                            initXeditableStatus(reportitem_id);

                            $("#reports_save").show();
                        }
                    },
                    error: function () {
                        swal("Failed", "Something went wrong", "error");
                    }
                });
            }

            function initXeditableText(reportitem_id) {
                //Make fields editable
                $('.editdata').editable({
                    id: reportitem_id,
                    type: "POST",
                    url: '{{ route('employee.report.updateReportitems') }}',
                });
            }

            function initXeditableTechnology(reportitem_id) {
                //Make technology editable
                $('.edittech').each(function () {
                    $(this).editable({
                        id: reportitem_id,
                        type: "POST",
                        url: '{{ route('employee.report.updateReportitems') }}',
                        prepend: 'Select Category',
                        source: [
                                @foreach($technology_dropdown as $id => $categories)
                            {
                                value: '{{ $id }}', text: '{{ $categories }}'
                            }
                            @unless ($loop->last)
                            ,
                            @endunless
                            @endforeach
                        ]
                    });
                });
            }

            function initXeditableStatus(reportitem_id) {
                //Make Status editable
                $('.editstatus').editable({
                    id: reportitem_id,
                    type: "POST",
                    url: '{{ route('employee.report.updateReportitems') }}',
                    prepend: 'Select Status',
                    source: [
                            @foreach($status as $id => $s)
                        {
                            value: '{{ $id }}', text: '{{ $s }}'
                        }
                        @unless ($loop->last)
                        ,
                        @endunless
                        @endforeach
                    ]
                });
            }

            function initelement() {
                disableFormElements();

                //Date Picker
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true, todayHighlight: true,
                });

                //clock Picker
                $('.clockpicker').clockpicker({
                    donetext: 'Done',
                });

                //Auto complete
                $('#search_project').autocomplete({
                    source: '{{ route('report.searchproject') }}',
                    minlength: 1,
                    autoFocus: true,
                    select: function (event, ui) {
                        var value = ui.item.value;

                        $.ajax({
                            url: '{{ route('report.searchtechnolgy') }}',
                            type: 'POST',
                            data: {
                                'value': value
                            },
                            success: function (data) {
                                if (data.id) {
                                    $('#technology_id').val(data.id).trigger('change');
                                    $('#works').focus();
                                }
                            }
                        });
                    }
                });

                //Search Category
                $('.select2').select2();

                $("#project-remote-select2").select2({
                    minimumInputLength: 3,
                    // tags: [],
                        
                    ajax: {
                        url: '{{ route('report.searchproject') }}',
                        dataType: 'json',
                        type: "GET",
                        quietMillis: 50,
                        data: function (term) {
                            return {
                                term: term
                            };
                        },
                    }
                });
            }

            function disableFormElements() {
                var category = $('option:selected', '#technology_id').text();
                var categories = {!! json_encode($exclude_technology) !!};

                var form_elems = $('#technology_id').closest('form').find('#search_project, #works, #notes, [name="status"]');
                form_elems.attr('disabled', $.inArray(category, categories) !== -1);
            }

            function getReportItemForm(data) {
                $.ajax({
                    method: "POST",
                     url: "{{ $is_admin_route ? route('report.getreportitemsedit') : route('employee.report.getReportitemForm')}}",
                    //url: "{{ route('employee.report.getReportitemForm') }}",
                    data: data,
                    success: function (data) {
                        $("#report-div").html(data);
                        initelement();
                        // $('#ReportEditModal').modal('hide');
                    },
                    error: function () {
                        swal("Failed", "Something went wrong", "error");
                    }
                });
            }
        </script>

<script type="text/javascript">
$(document).ready(function () {

    $('#ReportEditModal').on("shown.bs.modal", function (e) {
        var $relElem = $(e.relatedTarget);
        var reportitem_id = $relElem.data('itemid');
        console.log(reportitem_id);
        
            $.ajax({
                method: "GET",
                url: "{{ route('report.getreportitemsedit') }}",
                data: { reportitem_id : $relElem.data('itemid') , report_id : "{{ $Model->id }}" },
                success: function (html) {
                    $("#edit_Reports").html(html);
                    initelement();
                },
                
            });
    });


        //Report Item edit
    // $('body').on('submit', '#reportform', function (e) {
    //     e.preventDefault();
    //     $this = $(this);

    //         $.ajax({
    //             method: 'post',
    //             url: $this.attr('action'),
    //             data: $this.serialize(),
    //             success: function (data) {
    //                 $('#ReportEditModal').modal('hide');
    //             },
    //             complete: function () {
    //                 $this.find(':submit').buttonReset();
    //             },
                
    //         });
    //     });

    });

    </script>
@endpush
