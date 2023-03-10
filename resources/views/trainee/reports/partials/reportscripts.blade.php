<div class="modal fade bs-example-modal-lg" id="releaseLockModal" role="dialog" aria-labelledby="releaseLockModal">
    <div class="modal-dialog modal-md" role="document" style="padding-top: 60px">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Release Lock (Break Hours) Request</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                @include('layouts.partials.loader-content')
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-lg" id="extendModal" role="dialog" aria-labelledby="extendModal">
    <div class="modal-dialog modal-md" role="document" style="padding-top: 60px">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Extend Hours</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                @include('layouts.partials.loader-content')
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-lg" id="githubModal" role="dialog" aria-labelledby="releaseLockModal">
    <div class="modal-dialog modal-xl" role="document" style="padding-top: 60px">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Github Latest Commits  <button type='button' class="btn-warning github-refresh mdi mdi-refresh"> Refresh</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                @include('layouts.partials.loader-content')
            </div>
        </div>
    </div>
</div>
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">
    <link rel="stylesheet" href="{{ mix('/css/report.css') }}">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endpush

@include('employee.reports.partials.copytoclipboardscripts')
@push('scripts')

    <script src="{{ mix('/js/report.js')  }}"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>

    @if(@$Report)
        <script type="text/javascript">
            $(document).ready(function () {
                getReportItemForm({mode: 'new', report_id: '{{ $Report->id }}'});

                getReportitems();

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
                        url: "{{ route('employee.report.setPermissionTime') }}",
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

                    //Show elapsed time in Report create Form
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
                    var id = $(this).data('id');
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
                            url: "{{ route('trainee.report.deleteReportitems') }}",
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
                                    window.location.replace("{{ route('trainee.report.index') }}");
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
                                                window.location.replace("{{ route('trainee.report.index') }}");
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

            //Sort the Reportitems and Save the Order Of Report
            function initSortable()
            {
                updateIndex = function() {
                    $('td.order').each(function(i){
                    $(this).html(i + 1);
                        });
                    }
                $( "#items tbody" ).sortable({
                    update:function(event,ui){
                        $(this).children().each(function(index){
                            if($(this).attr('data-position') != (index+1)){
                                $(this).attr('data-index',(index+1)).addClass('updated');
                                $('.order').attr('data-index');
                                updateIndex();
                            }
                        });
                        var positions=[];
                        $('.updated').each(function(){
                            positions.push($(this).attr('data-position'));
                            $(this).removeClass('updated');
                        });
                        $.ajax({
                            url:"{{ route('trainee.report.order.edit') }}",
                            method:'post',
                            dataType:"json",
                            data:{'id':positions},
                            success: function(response) {
                            }
                        });
                    }
                });
                $( "#items tbody" ).disableSelection(); 
            }

            //Report Extend Hours 
            $('body').on('shown.bs.modal', '#extendModal', function (e) {
                var $relElem = $(e.relatedTarget);
                var report_id = $relElem.data('report_id');
                var reportitem_id = $relElem.data('reportitem_id');
                $.ajax({
                    method: 'GET',
                    url: "{{ route('trainee.report.extendhours') }}",
                    data: {'report_id': report_id,'reportitem_id':reportitem_id},
                    beforeSend: function () {
                        $(this).button('loading')
                    },
                    complete: function () {
                        $(this).button('reset')
                    },
                    success: function (html) {
                        $("#extendModal .modal-body").html(html);
                    },
                    error: function (jqXhr) {
                        swalError(jqXhr);
                    }
                });
            }).on('hidden.bs.modal', function () {
                $('#extendModal .modal-body').html($('#loader-content').html());
            });

            $('body').on('submit', '#extendForm', function (e) {
                e.preventDefault();
                $this = $(this);
                var form_data = $('#extendForm').serialize();
                $.ajax({
                    method: 'post',
                    dataType:"json",
                    url: "{{ route('trainee.report.extendhours') }}",
                    data:form_data,
                    beforeSend: function () {
                        $this.find(':submit').buttonLoading();
                    },
                    complete: function () {
                        $this.find(':submit').buttonReset();
                    },
                    success: function(response) {
                        $('#extendModal').modal('hide');
                        getReportitems();
                        swal("Extended", "Your Report Hours Extended successfully!", "success");
                    },
                    error: function (jqXhr) {
                        swalError(jqXhr);
                    }
                });
            });

            //Release break lock
            $('body').on('shown.bs.modal', '#releaseLockModal', function (e) {
                var $relElem = $(e.relatedTarget);
                var report_id = $relElem.data('report_id');
                $.ajax({
                    method: 'GET',
                    url: "{{ route('trainee.report.releaselockbreak') }}",
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

            // Elapsed hours calulation
            $('body').on('click', '#items tbody tr td:not(:last)', function (e) {
                $(this).closest('tr').toggleClass('bg-light-green');
                var durations = [];
                $('#items tr.bg-light-green').each(function () {
                    durations.push($(this).find('td:nth-child(6)').html() + ':00');
                });
                const totalDurations = durations.slice(1)
                    .reduce((prev, cur) => moment.duration(cur).add(prev),
                        moment.duration(durations[0]));
                var sel_elpased = moment.utc(totalDurations.asMilliseconds()).format("HH:mm");
                $('#selected_elapsed_hours').html(sel_elpased != '00:00' ? ('Elapsed Hours (Selected): ' + sel_elpased) : '');
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
                var report_id = '{{@$Report->id}}';

                $.ajax({
                    method: 'get',
                    url: "{{ route('trainee.report.getReportitems') }}",
                    dataType: 'html',
                    data: {'id': report_id},
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
                                @if(@$report_sort)
                                    initSortable();
                                @endif
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
                    url: '{{ route('trainee.report.updateReportitems') }}',
                });
            }

            function initXeditableTechnology(reportitem_id) {
                //Make technology editable
                $('.edittech').each(function () {
                    $(this).editable({
                        id: reportitem_id,
                        type: "POST",
                        url: '{{ route('trainee.report.updateReportitems') }}',
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
                    url: '{{ route('trainee.report.updateReportitems') }}',
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
                jQuery.browser = {
                    msie: false,
                    version: 0
                };

                disableFormElements();

                //Date Picker
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true, todayHighlight: true,
                });

                //clock Picker
                $('.clockpicker').clockpicker({
                    autoclose: true,
                });


                //Search Category
                $('.select2').select2();
              
                //Project  select
                $("#project-remote-select2").select2({
                    minimumInputLength: 3,
                    // tags: [],
                        
                    ajax: {
                        url: '{{ route('trainee.report.searchproject') }}',
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

                $('#project-remote-select2').on('select2:select', function (e) {
                    var value = $(this).val();

                    $.ajax({
                        url: '{{ route('trainee.report.searchtechnolgy') }}',
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
                });

                $('#project_create_form').submit(function (e) {
                    e.preventDefault();
                    $this = $(this);

                    $.ajax({
                        method: $this.attr('method'),
                        url: $this.attr('action'),
                        data: $this.serialize(),
                        
                        beforeSend: function () {
                            $this.find(':submit').buttonLoading();
                            $('#similarity-div').html('');
                        },
                        complete: function () {
                            $this.find(':submit').buttonReset();
                        },
                        success: function(data){
                            if(data.success){
                                $('#ProjectNameModal').modal('hide');

                                $('#project-remote-select2')
                                    .append($("<option></option>")
                                    .attr("value",data.project.name)
                                    .text(data.project.name)); 
                                $('#project-remote-select2').val(data.project.name).trigger('change');
                                
                                $.toast({
                                heading: 'Success',
                                text: data.id ? 'Project Name updated in the list' : 'Project Name added in the list',
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'info',
                            });

                            }else{
                                if(data.similarity){
                                    console.log(data.similarity);
                                    var html = '<div class="col-md-12 mb-4">';
                                    html += '<span><b>' + data.similarity.length + '</b> similarity found !!!</span><br />';

                                    $.each(data.similarity, function(key, val){
                                        html += '<span class="label label-primary">' + val + '</span>&nbsp;';
                                    });

                                    html += '</div>';
                                    html += '<div class="col-md-12 mb-4">';
                                    html += '<span class="text-danger mt-2">TYPE THE SAME PROJECT NAME BELOW</span>';
                                    html += '<input name="confirm_project_name" class="form-control" placeholder="Enter project name again"/>';
                                    html += '</div>';

                                    $('#similarity-div').html(html);
                                }
                            }

                        },
                    
                        error: function (jqXhr) {
                            swalError(jqXhr);
                        }
                    });
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
                    url: "{{ route('trainee.report.getReportitemForm') }}",
                    data: data,
                    success: function (data) {
                        $("#report-div").html(data);
                        initelement();
                    },
                    error: function () {
                        swal("Failed", "Something went wrong", "error");
                    }
                });
            }

           
        </script>
    @else
        <script type="text/javascript">
            $(document).ready(function () {
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true, todayHighlight: true,
                });
                $('.clockpicker').clockpicker({
                    autoclose: true,
                });
            });
            
            
        </script>
    @endif
    
@endpush
