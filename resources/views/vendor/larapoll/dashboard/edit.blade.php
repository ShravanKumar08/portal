@extends('larapoll::layouts.app')

@push('stylesheets')
    <style>
        [type=radio]:checked, [type=radio]:not(:checked){
            position: relative !important;
            left: 0px !important;
            opacity: inherit !important;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <form method="POST" action=" {{ route('poll.update', $poll->id) }}">
                        {{ csrf_field() }}
                        <!-- Question Input -->
                            <div class="form-group">
                                <label>{{ $poll->question }}</label>
                            </div>
                            <ul class="options">
                                @foreach($poll->options as $option)
                                    <li>{{ $option->name }}</li>
                                @endforeach
                            </ul>

                            @php
                                $maxCheck = $poll->maxCheck;
                                $count_options = $poll->optionsNumber()
                            @endphp
                            <div class="form-group">
                                <label for="count_check">Max Count</label>
                                <select name="count_check" class="form-control" id="count_check">
                                    @for($i =1; $i<= $count_options; $i++)
                                        <option  {{ $i==$maxCheck? 'selected':'' }} >{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="form-group">
                                {{ Form::label('close','Close',['class'=>'']) }} <br/>
                                <div class="switch">
                                    <label>
                                        {{ Form::hidden('close', 0) }}
                                        {{ Form::checkbox('close', 1,  $poll->isLocked()) }}<span class="lever switch-col-blue"></span>
                                    </label>
                                </div>
                            </div>

                            <!-- Create Form Submit -->
                            <div class="form-group">
                                <input name="update" type="submit" value="Update" class="btn btn-primary"/>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection