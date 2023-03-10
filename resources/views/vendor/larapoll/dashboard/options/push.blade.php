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
                        <form method="POST" action=" {{ route('poll.options.add', $poll->id) }}">
                        {{ csrf_field() }}
                        <!-- Question Input -->
                            <div class="form-group">
                                <label for="question">{{ $poll->question }}</label>
                            </div>
                            <ul class="old_options">
                                @foreach($poll->options as $option)
                                    <li> {{ $option->name }}</li>
                                @endforeach
                            </ul>
                            <ul id="options">
                                <li>
                                    <input type="text" name="options[0]" class="form-control add-input" placeholder="Insert your new option" required/>
                                </li>
                            </ul>

                            <ul>
                                <li class="button-add">
                                    <div class="form-group">
                                        <a class="btn btn-success" id="add">
                                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                            <!-- Create Form Submit -->
                            <div class="form-group">
                                <input name="Add" type="submit" value="Add" class="btn btn-primary" >
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        function remove(current){
            current.parentNode.remove()
        }
        document.getElementById("add").onclick = function() {
            var e = document.createElement('li');
            e.innerHTML = "<input type='text' name='options[]' class='form-control add-input' placeholder='Insert your new option' required/> <a class='btn btn-danger' href='#' onclick='remove(this)'><i class='fa fa-minus-circle' aria-hidden='true'></i></a>";
            document.getElementById("options").appendChild(e);
        }
    </script>
@endpush