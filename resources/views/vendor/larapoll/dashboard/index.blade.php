@extends('larapoll::layouts.app')
@section('style')
    <style>
        .table td, .table th {
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="dt-buttons">
                            <a class="dt-button buttons-create" tabindex="0" aria-controls="datatable-buttons" href="{{ route('poll.create') }}"><span><i class="fa fa-plus"></i> Create</span></a>
                        </div>
                        @if($polls->count() >= 1)
                            <table class="table table-hover table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
                                    <th>Options</th>
                                    <th>Votes</th>
                                    <th>State</th>
                                    <th>Edit</th>
                                    <th>Add Options</th>
                                    <th>Remove Options</th>
                                    <th>Remove</th>
                                    <th>Lock/Unlock</th>
                                    <th>View Vote Chart</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($polls as $poll)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $poll->question }}</td>
                                        <td>{{ $poll->options_count }}</td>
                                        <td>{{ $poll->votes_count }}</td>
                                        <td>
                                            @if($poll->isLocked())
                                                <span class="label label-danger">Closed</span>
                                            @else
                                                <span class="label label-success">Open</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-info btn-sm" href="{{ route('poll.edit', $poll->id) }}">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-success btn-sm" href="{{ route('poll.options.push', $poll->id) }}">
                                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-warning btn-sm" href="{{ route('poll.options.remove', $poll->id) }}">
                                                <i class="fa fa-minus-circle" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <form action="{{ route('poll.remove', $poll->id) }}" method="POST">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            @php $route = $poll->isLocked()? 'poll.unlock': 'poll.lock' @endphp
                                            @php $fa = $poll->isLocked()? 'fa fa-unlock': 'fa fa-lock' @endphp
                                            <form action="{{ route($route, $poll->id) }}" method="POST">
                                                {{ csrf_field() }}
                                                {{ method_field('PATCH') }}
                                                <button type="submit" class="btn btn-sm">
                                                    <i class="{{ $fa }}" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        </td>                                        
                                        <td>
                                            <button class="btn btn-success btn-view" title="Vote Chart" data-url="{{ route('viewVotes', $poll->id) }}" data-toggle="modal" data-target="#VoteChartModal"><i class="fa fa-eye"></i> View</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No poll has been found</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        @endif
                        {{ $polls->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="VoteChartModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
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

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#VoteChartModal').on("shown.bs.modal", function (e) {
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