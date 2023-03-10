@extends('layouts.master')

@section('content')
    @if(@$model->status == "P")
        <h3 class="text-center">Wait until admin approves..</h3>        
    @else
    <div class="row justify-content-md-center">
            <div class="col-5">
                <div class="card">
                    <div class="card-body">
                        <div class="form-body">
                            <h3 class="card-title">Timer not started yet.</h3>
                            <h6 class="card-subtitle">Just send a request with your start time and reason</h6>

                            {{ Form::open(['route' => 'trainee.entry.timeronrequest', 'method' => 'POST' ,'class' => 'form-material m-t-40']) }}
                                @include('layouts.partials.timeronrequest')
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('stylesheets')
    <link href="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.clockpicker').clockpicker({
                donetext: 'Done',
            });
        });
    </script>    
@endpush