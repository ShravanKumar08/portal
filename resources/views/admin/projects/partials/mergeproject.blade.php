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
                                                <div class="col-md-12">
                                                        <h4 class="card-title">String Soundex Range</h4>
                                                        <input type="text" name="filter" id="range_03" />
                                                        <input type="hidden" id="from"  name="from"/>
                                                        <input type="hidden" id="to" name="to"/>
                                                                                                                                                              
                                                </div>
                                                <div class="col-md-6">
                                                        <label class="">&nbsp;</label>
                                                        <div class="form-actions">
                                                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Search </button>
                                                                @if($request->scope == null)
                                                                <button type="button" class="btn btn-inverse" onclick="location.href ='{{  route('project.mergeproject') }}'">Reset</button>
                                                                @else
                                                                <button type="button" class="btn btn-inverse" onclick="location.href ='{{  route('project.mergeproject').'?scope='.$request->scope }}'">Reset</button>
                                                           @endif
                                                        </div>
                                                </div>
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="over_all_productivity_top">
                        </div>
                        <table border='0' cellspacing='1' cellpadding='10' width='100%'
                               class="display nowrap table table-hover table-striped table-bordered" id="datatable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Duplicate</th>
                                <th >Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="DatatableViewModal" data-url="" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Merge Projects</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    {{ Form::open(['route' => ["project.mergeproject"],'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'merge-form']) }}
                    {{ Form::hidden('merge_row') }}

                    <div class="row p-t-20">
                        <div class="col-md-12">
                            <div class="form-group">
                                <strong> Name: </strong>
                                <h4 id ="primary_project_name"></h4>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <strong>Primary name: </strong>
                                {!! Form::select('primary_project_id', [],  '' , ['class' => 'form-control', 'placeholder' => 'Select']) !!}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pull-right  text-center">
                                <textarea id="smart_report" style="display: none"></textarea>
                                <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
                            </div>
                        </div>
                        </div>
                {{ Form::close() }}
                </div>
            </div>
        <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>
@endsection


@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('/css/datatable.css') }}">
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('/js/datatable.js') }}"></script>
    {{--<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>--}}
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>

    <link href="{{  asset('assets/plugins/ion-rangeslider/css/ion.rangeSlider.css') }}" rel="stylesheet">
    <link href="{{  asset('assets/plugins/ion-rangeslider/css/ion.rangeSlider.skinModern.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/ion-rangeslider/js/ion-rangeSlider/ion.rangeSlider.min.js') }}"></script>
     

    <script type="text/javascript">
        $(document).ready(function () {
            $('.select2').select2();
            $("#range_03").ionRangeSlider({
                type: "double",
                grid: true,
                min: 1,
                max: {{ $max }},
                from: '{{$request->from ? $request->from : 1}}',
                to: '{{$request->to ? $request->to : 6}}',
                onChange: function(data) {
              
                  $('#from').val(data.from);
                  $('#to').val(data.to);
              } 
            });
          
            
            $('#datatable').dataTable({
                //processing: true,
                //serverSide: true,
                'ajax': '{{  \Request::fullUrl() }}',
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'cnt', name: 'cnt'},
                    {data: 'action', name: 'action'},
                ],
                'dom': 'lBfrtip',
                'buttons': [
                    {
                        extend: 'collection',
                        text: '<i class="fa fa-download"></i> Export',
                        buttons: [
                            {
                                extend: 'csvHtml5',
                                text: '<i class="fa fa-file-excel-o"></i> CSV',
                            },
                            {
                                extend: 'pdfHtml5',
                                text: '<i class="fa fa-file-pdf-o"></i> PDF',
                            },
                        ]
                    },
                ]
            });

            $('#merge-form').submit(function (e) {
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
                        $("#merge-form")[0].reset();
                        $('#DatatableViewModal').modal('hide');
                        swal("Merged!", "Projects has been merged", "success");
                        $('#datatable').DataTable().ajax.reload(null, false);
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

            $('#DatatableViewModal').on("shown.bs.modal", function (e) {
                var rel = $(e.relatedTarget);

                $('select[name="primary_project_id"]').find('option').remove();
                $('.hidden_merge_ids').remove();

                $('input[name="merge_row"]').val(rel.data('id'));
                $('#primary_project_name').html(rel.data('name'));
                
                $.each(_.zipObject(rel.data('merge_ids').split(';'), rel.data('merge_name').split(';')), function(key, value) {   
                    $('select[name="primary_project_id"]')
                        .append($("<option></option>")
                                    .attr("value",key)
                                    .text(value)); 
                    $('#merge-form').append('<input type="hidden" class="hidden_merge_ids" name="merge_project_ids[]" value="' + key + '"/>');
                });
                
            }).on("hidden.bs.modal", function (e) {
            });
        });
    </script>
@endpush
