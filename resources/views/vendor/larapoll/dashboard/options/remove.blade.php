@extends('larapoll::layouts.app')
@push('stylesheets')
    <style>
        .old_options, #options, .button-add {
            list-style-type: none;
        }

        .add-input {
            width: 80%;
            display: inline-block;
            margin-right: 10px;
            margin-bottom: 10px;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <form method="POST" action=" {{ route('poll.options.remove', $poll->id) }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <!-- Question Input -->
                            <div class="form-group">
                                <label>{{ $poll->question }}</label>
                                <div class="radio">
                                    @foreach($poll->options as $option)
                                        {{ Form::label('options[]', $option->name,['class'=>'']) }} <br/>
                                        <div class="switch">
                                            <label>
                                                {{ Form::checkbox('options[]', $option->id, true) }}<span class="lever switch-col-blue"></span>
                                            </label>
                                        </div>
                                        <br/>
                                    @endforeach
                                </div>
                            </div>
                            <!-- Create Form Submit -->
                            <div class="form-group">
                                <input name="Delete" type="submit" value="Delete" class="btn btn-danger" >
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection