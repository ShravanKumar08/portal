@extends('larapoll::layouts.app')
@section('content')
    <style>
        [type=radio]:checked, [type=radio]:not(:checked){
            position: relative !important;
            left: 0px !important;
            opacity: inherit !important;
        }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="myTable" class="table table-hover table-striped table-bordered">
                            <thead>
                                <th>#</th>
                                <th>Poll</th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @foreach($polls as $poll)
                                @php
                                    $ifVoted = \Auth::user()->hasVoted($poll->id);
                                @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $poll->question }}</td>
                                        @if(@$ifVoted)
                                            <td><span class='label label-success'>Voted</span></td>
                                        @else
                                            <td><span class='label label-warning'>Not Voted</span></td>
                                        @endif
                                        <td>
                                            @if(@$ifVoted)
                                            <button class="btn btn-success btn-view" title="Voted" data-url="{{ $is_trainee_route ? route('trainee.vote', $poll->id) :  route('employee.vote', $poll->id) }}" data-toggle="modal" data-target="#VoteModal"><i class="fa fa-eye"></i> View</button>
                                            @else
                                            <button class="btn btn-info btn-view" title="Not Voted" data-url="{{ $is_trainee_route ? route('trainee.vote', $poll->id) :  route('employee.vote', $poll->id) }}" data-toggle="modal" data-target="#VoteModal"><i class="fa fa-thumbs-up"></i> Vote</button>
                                            @endif    
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="VoteModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Vote</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body" id="VoteContent"></div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('/css/datatable.css') }}">
@endpush

@push('scripts')

<!-- {{ asset('js/datatable.js') }} -->
    <script src="{{ mix('/js/datatable.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#myTable').DataTable();
            
            $('#VoteModal').on("shown.bs.modal", function (e) {
                var $relElem = $(e.relatedTarget);

                $.ajax({
                    method: "GET",
                    url: $relElem.data('url'),
                    success: function (html) {
                        $('#VoteContent').html(html);
                        $('#poll-container form').append('<input name="_token" type="hidden" value="' + $('meta[name="csrf-token"]').attr('content') + '">');
                    },
                    error: function (jqXhr) {
                        $('#VoteModal').modal('hide');
                        swalError(jqXhr);
                    }
                });
            }).on("hidden.bs.modal", function (e) {
                $('#VoteModal .modal-body').html($('#loader-content').html());
            });
        });
    </script>
@endpush