@extends('layouts.master')
@section('content')
<div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="over_all_productivity_top">
                        </div>
                        @php
                            $i=1;
                        @endphp
                        <table border='0' cellspacing='1' cellpadding='10' width='100%'
                               class="display nowrap table table-hover table-striped table-bordered" id="datatable">
                            <thead>
                            <tr>
                                <th width="25%">S.No</th>
                                <th width="25%">Name</th>
                                <th width="25%">Age</th>
                                <th width="45%">Birthday</th>
                                <th width="45%">Action</th>
                            </tr>
                            </thead>
                            @forelse($emp_upcoming_birthday as $upcoming_birthday)
                            @php
                            $dob = new DateTime($upcoming_birthday -> dob);
                            $now = new DateTime();
                           
                            @endphp
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$upcoming_birthday -> name}}</td>
                                    <td>{{ $now->diff($dob)->y }}</td>
                                    <td>{{$upcoming_birthday -> dob}}</td>
                                    <td>
                                            @if(App\Helpers\SecurityHelper::hasAccess('employee.show'))
                                            <a href="{{ route('employee.show', $upcoming_birthday->id) }}" class="btn btn-secondary" title="View"><i class="fa fa-eye"></i></a>
                                            @endif
                                    @php
                                        $i = $i+1;
                                    @endphp
                                </tr>
                                @empty
                                <tr class="text-center">
                                    <td colspan="5">No records found</td>
                                </tr>
                            @endforelse
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('/css/datatable.css') }}">
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ mix('/js/datatable.js') }}"></script>
    {{--<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>--}}
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>

    <script type="text/javascript">
        $('.select2').select2();
        $(document).ready(function () {
            $('#date-range').datepicker({
                format: "yyyy-mm",
                viewMode: "months",
                minViewMode: "months",
                autoclose: true, todayHighlight: true,
            });

            $('#datatable').dataTable({
                fixedHeader: {
                    header: true,
                    headerOffset: $('.navbar.top-navbar').outerHeight(),
                },
                "bPaginate": false,
                'pageLength': 50,
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
