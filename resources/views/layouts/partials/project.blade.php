<div class="row justify-content-md-center">
    <div class="col-12">
        <div class="text-right p-b-20">
            <a class="btn btn-sm btn-primary" id="advance-search-btn" data-toggle="collapse"
               href="#collapseAdvanced" role="button" aria-expanded="false" aria-controls="collapseAdvanced">
                {{ $request->show ? 'Hide' : 'Show' }} Advanced Search
            </a>
        </div>

        <div class="collapse {{ $request->show ? 'show' : '' }}" id="collapseAdvanced">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h4 class="m-b-0 text-white"><i class="fa fa-search"></i> Advanced search</h4>
                </div>
                <div class="card-body">
                    {{ Form::open(['url' => \URL::current(), 'class' => 'form-horizontal','method' => 'GET', 'id' => 'project_search_form']) }}
                    {{ Form::hidden('show', $request->show) }}
                    {{ Form::hidden('sortby', $request->sortby) }}
                    <div class="form-body">
                        <div class="row">
                            @if(@$is_admin_route)
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="">Employee</label>
                                        {{ Form::select('employee_id[]', $employees_list, $request->employee_id, ['class' => 'form-control selectpicker','multiple data-style' => 'form-control btn-secondary']) }}
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('filtertype','Filter type',['class'=>'']) }}
                                    <div class="form-check">
                                    <label class="custom-control custom-radio">
                                        {!! Form::radio('filtertype', 'M',($request->filtertype == 'M' || !$request->filtertype), ['class' => 'custom-control-input' , 'id' => 'month_type'] ); !!} Monthly
                                        <span class="custom-control-indicator"></span>
                                    </label>
                                    <label class="custom-control custom-radio">
                                        {!! Form::radio('filtertype', 'D', $request->filtertype == 'D', ['class' => 'custom-control-input', 'id' => 'range_type'] ); !!} Date Range
                                        <span class="custom-control-indicator"></span>
                                    </label>
                                </div>
                                </div>
                            </div>
                      
                            
                            <div class="col-md-4" id="month" >
                                <div class="form-group">
                                    <label class="">Month</label>
                                    <div class="input-daterange input-group" id="date-range">
                                        {{ Form::text('month_year', $request->month_year ,  ['class' => 'form-control', 'placeholder' => 'Select Month', 'autocomplete' => 'off']) }}
                                    </div>
                                </div>
                            </div>
                        
                        <div class="col-md-4" id="date" >
                            <div class="form-group" >
                                    <label class="">Date Range</label>
                                    <div class="input-daterange input-group" id="date-range">
                                        {{ Form::text('from_date', $request->from_date ? $request->from_date : date('Y-m-d'),  ['class' => 'form-control datepicker', 'placeholder' => 'From', 'autocomplete' => 'off']) }}
                                        <span class="input-group-addon bg-info b-0 text-white">to</span>
                                        {{ Form::text('to_date',  $request->to_date ? $request->to_date : date('Y-m-d'), ['class' => 'form-control datepicker', 'placeholder' => 'To', 'autocomplete' => 'off']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="">&nbsp;</label>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Search
                                    </button>
                                    <button type="button" class="btn btn-inverse"
                                            onclick="location.href ='{{ route($is_admin_route ? 'report.monthlyreports.project' : 'employee.report.monthlyreports.project') }}'">
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-7">
        <div class="card">
            <div class="card-body">
                <ul class="country-state">
                    @php
                        $collection = collect($project);
                        $sum=$collection->sum('elapsed_minutes');
                    @endphp
                    <div class="d-flex no-block">
                        <div class="ml-auto">
                            {!! Form::select('sort_order', ['projects.name ASC' => 'Name Asc', 'projects.name DESC' => 'Name Desc', 'elapsed_minutes ASC' => 'Hours Asc', 'elapsed_minutes DESC' => 'Hours Desc'], $request->sortby, ['class' => 'form-control', 'placeholder' => 'Sort By']) !!}
                        </div>
                    </div>
                    @forelse($project as $item)
                        <li>
                            <h4><a href=" " data-toggle="modal" data-id="{{ $item->id }}" data-emp_id="{{ $item->employee_id }}"
                                   data-target="#DatatableViewModal">{{$item->name}}</a></h4>
                            <small>{{$item->elapse}}</small>
                            @php
                                $per = $item->elapsed_minutes;
                                $percentage = $per * 100 / $sum;
                                $percentage = number_format((float)$percentage, 2, '.', '');
                            @endphp
                            <div class="pull-right">{{$percentage}}%</div>
                            <div class="progress">
                                @php
                                    if ($percentage < "5"){
                                        $bg_color = "bg-danger";
                                    }elseif ($percentage < "10"){
                                        $bg_color = "bg-inverse";
                                    }elseif ($percentage < "20"){
                                        $bg_color = "bg-info";
                                    }else{
                                        $bg_color = "bg-success";
                                    }
                                @endphp

                                <div class="progress-bar {{$bg_color }}" role="progressbar"
                                     style="width: {{$percentage}}%; height: 6px;" aria-valuenow="25" aria-valuemin="0"
                                     aria-valuemax="100"></div>
                            </div>
                        </li>
                    @empty
                        <li>
                            <h2>0</h2>
                            <small>No Projects</small>
                            <div class="pull-right">0%</div>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 0%; height: 6px;"
                                     aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<div id="DatatableViewModal" data-url="" class="modal fade in" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Report items</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body"></div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@push('stylesheets')
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css" rel="stylesheet">
    <link href="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet"/>
@endpush

@push('scripts')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.select2').select2();

            $('#date-range').datepicker({
                format: "yyyy-mm",
                viewMode: "months",
                minViewMode: "months",
                autoclose: true, todayHighlight: true,
            });

            $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true, todayHighlight: true,
                    daysOfWeekDisabled: [0],
                });

            $('#DatatableViewModal .modal-body').html($('#loader-content').html());

            $('#DatatableViewModal').on("shown.bs.modal", function (e) {
                var id = $(e.relatedTarget).data('id');
                var emp_id = $(e.relatedTarget).data('emp_id');

                $.ajax({
                    method: "GET",
                    @if(@$is_admin_route)
                        url: "{{ url('/admin/report/monthlyreports/showproject') }}",
                    @else
                        url: "{{ url('/employee/report/monthlyreports/showproject') }}",
                    @endif
                    data: {
                        id: id,
                        emp_id: emp_id,
                        filtertype: '{{ $request->filtertype }}',
                        from_date : '{{ $request->from_date ? $request->from_date : date('Y-m-d') }}',
                        to_date : '{{ $request->to_date ? $request->to_date : date('Y-m-d') }}',
                        month_year: '{{ $request->month_year ? $request->month_year : date('Y-m') }}'
                    },
                    success: function (html) {
                        $('#DatatableViewModal .modal-body').html(html);
                    },
                    error: function (jqXhr) {
                        $('#DatatableViewModal').modal('hide');
                        swalError(jqXhr);
                    }
                });
            }).on("hidden.bs.modal", function (e) {
                $('#DatatableViewModal .modal-body').html($('#loader-content').html());
            });


            $('#collapseAdvanced').on('hidden.bs.collapse', function () {
                $('#advance-search-btn').html('Show Advanced Search');
                $('input[name="show"]').val('');
            }).on('shown.bs.collapse', function () {
                $('#advance-search-btn').html('Hide Advanced Search');
                $('input[name="show"]').val(1);
            });

             $('select[name="sort_order"]').on('change', function () {
                $('input[name="sortby"]').val($(this).val());
                $('#project_search_form').submit();
            });
           
            if($('input[name="filtertype"]:checked').val() == 'M'){
                $("#date").hide();
            }else{
                $("#month" ).hide();
            }

            $("#range_type").click(function() {
                $("#date").show();
                $("#month" ).hide();
            });

            $("#month_type").click(function() {
                $("#month").show();
                $("#date" ).hide();
            });
                
});


       
    
    </script>
@endpush

