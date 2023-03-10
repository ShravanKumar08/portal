@push('stylesheets')
    <style>
        [type=radio]:checked, [type=radio]:not(:checked){
            position: relative !important;
            left: 0px !important;
            opacity: inherit !important;
        }
    </style>
@endpush

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body" id="poll-container">
                    {{ (new App\Helpers\PollWriterHelper())->drawResult($poll) }} <br/>
                    @php
                        $voted_users_id = \App\Models\User::query()->role(['admin', 'super-user'])->pluck('id')->toArray();
                    @endphp
                    @foreach ($results as $result)                            
                        @php
                            $user_ids = array_keys(array_where($votes, function ($value, $key) use ($result) {
                                return $value == $result['option']->id;
                            }));
                            $voted_users_id = array_merge($voted_users_id, $user_ids);
                        @endphp
                        {{ $result['option']->name }} ({{ $result['votes'] }}) : {{ $user_ids ? App\Models\User::whereIn('id', $user_ids)->get()->implode('employee.shortname' , ', ') : '-' }}<br/>
                    @endforeach

                    <hr />
                    @php
                        $not_voted = \App\Models\User::query()->where('active', 1)->role(['employee', 'trainee'])->whereNotIn('id', $voted_users_id)->get()->implode('employee.shortname' , ', ');
                    @endphp

                    @if($not_voted)
                        <p class="text-danger"><b>Not Voted:</b> {{ $not_voted }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#poll-container form').append('<input name="_token" type="hidden" value="' + $('meta[name="csrf-token"]').attr('content') + '">');
        });
    </script>
@endpush