@php
    if($employee = \Auth::user()->employee){
        $entryitems = collect();

        if($entry = $employee->entries()->latest()->first()){
            $entryitems = $entry->entryitems()->latest('datetime')->limit(10)->get();
        }
    }else{
        $entryitems = \App\Models\Entryitem::query()->latest('datetime')->limit(10)->get();
    }
@endphp

<div class="card dashboardDivScrolling dashScrolling">
    <div class="card-body">
        <h4 class="card-title">Time in / out ({{ \Carbon\Carbon::now()->format('d-m-Y') }})</h4>
    </div>
    <div class="comment-widgets m-b-20">
        <!-- Comment Row -->
        @foreach(@$entryitems as $item)
            @php
                $entry = $item->entry;
            @endphp
            <div class="d-flex flex-row comment-row">
                <div class="p-2"><span class="round round-customcolor"><img src="{{ @$entry->employee->avatar }}"
                                                                            alt="user" width="50" height="50"></span>
                </div>
                <div class="comment-text w-100">
                    <h5>{{  @$entry->employee->shortname }}</h5>
                    <div class="comment-footer">
                        <span class="date">{{ \Carbon\Carbon::parse($item->datetime)->format('h:i A') }}</span>
                        @if($item->inout == 'I')
                            <label class="label label-success">In</label>
                        @else
                            <label class="label label-danger">Out</label>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{--<div class="card-body">--}}
        {{--<div class="table-responsive">--}}
            {{--<table class="table table-hover earning-box">--}}
                {{--<thead>--}}
                {{--<tr>--}}
                    {{--<th colspan="2">Employee</th>--}}
                    {{--<th>Time</th>--}}
                    {{--<th>In / Out</th>--}}
                {{--</tr>--}}
                {{--</thead>--}}
                {{--<tbody>--}}
                {{--@foreach(@$entryitems as $item)--}}
                    {{--@php--}}
                        {{--$entry = $item->entry;--}}
                    {{--@endphp--}}
                    {{--<tr>--}}
                        {{--<td style="width:50px;"><span class="round round-customcolor"><img--}}
                                        {{--src="{{ @$entry->employee->avatar }}" alt="user" width="50" height="50"></span>--}}
                        {{--</td>--}}
                        {{--<td>--}}
                            {{--<h6>{{ @$entry->employee->name }}</h6>--}}
                            {{--<small class="text-muted">{{ \Carbon\Carbon::parse($item->datetime)->format('d-m-Y h:i A') }}</small>--}}
                            {{--<span>--}}
                                 {{--@if($item->inout == 'I')--}}
                                    {{--<label class="label label-success">In</label>--}}
                                {{--@else--}}
                                    {{--<label class="label label-danger">Out</label>--}}
                                {{--@endif--}}
                            {{--</span>--}}
                        {{--</td>--}}
                    {{--</tr>--}}
                {{--@endforeach--}}
                {{--</tbody>--}}
            {{--</table>--}}
        {{--</div>--}}
    {{--</div>--}}
</div>