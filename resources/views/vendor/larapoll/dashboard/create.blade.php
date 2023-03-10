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
                        <form method="POST" action=" {{ route('poll.store') }}">
                        {{ csrf_field() }}
                        <!-- Question Input -->
                            <div class="form-group">
                                <label for="question">Question:</label>
                                <input type="text" id="question" name="question" class="form-control" required/>
                            </div>
                            <div class="form-group">
                                <label>Options:</label>
                                <ul id="options">
                                    <li>
                                        <input id="option_1" type="text" name="options[0]" class="form-control add-input" placeholder='Insert your option' required/>
                                    </li>
                                    <li>
                                        <input id="option_2" type="text" name="options[1]" class="form-control add-input" placeholder='Insert your option' required/>
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
                            </div>
                            <!-- Create Form Submit -->
                            <div class="form-group">
                                <input name="create" type="submit" value="Create" class="btn btn-primary"/>
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
            e.innerHTML = "<input type='text' name='options[]' class='form-control add-input' placeholder='Insert your option' required/> <a class='btn btn-danger' href='#' onclick='remove(this)'><i class='fa fa-minus-circle' aria-hidden='true'></i></a>";
            document.getElementById("options").appendChild(e);
        }
    </script>
@endpush