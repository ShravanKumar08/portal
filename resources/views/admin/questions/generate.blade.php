@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-7">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['method' => 'POST', 'class' => 'form-horizontal', 'id'=>'question_form']) }}
                    <div class="form-body">
                        <h3 class="card-title">Prepare Q/A</h3>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    {{ Form::label('grade_id', 'Grade *', ['class' => '']) }}
                                    {{ Form::select('grade_id', $grades, '', ['class' => 'form-control','placeholder'=>'Select Grade', 'autofocus']) }}
                                </div>
                            </div>
                            <div id="platformContainer"></div>
                            <div class="col-12">
                                <div class="form-group pull-right">
                                    <button type="button" class="btn btn-primary btn-sm" id="btn-add-more"><i
                                                class="fa fa-plus-square"></i> Add more platform
                                    </button>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-success btn-submit"><i class="fa fa-check"></i>
                                        Generate
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="col-5" id="qa-div">
            <div class="card">
                <div class="card-body">
                    <div class="form-body">
                        <h3 class="card-title">Generated Q/A</h3>
                        <hr>
                        <div>
                            -
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.js"></script>

    <script id="platformTemplate" type="text/x-jQuery-tmpl">
        <div class="col-12">
            <div class="row">
                <div class="col-4">
                    <div class="form-group"><label for="platform[${key}][id]" class="">Platform *</label>
                        <select class="form-control" name="platform[${key}][id]">
                            @foreach($platforms as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="platform[${key}][type]" class="">Type *</label>
                        <select class="form-control" name="platform[${key}][type]">
                            @foreach($question_types as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="platform[${key}][count]" class="">Count *</label>
                        <input class="form-control" name="platform[${key}][count]" type="number" value="1" min="1">
                    </div>
                </div>
                @{{if key != 0}}
                <div class="col-2">
                    <div class="form-group mt-4">
                            <button type="button" class="btn btn-danger btn-remove"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
                @{{/if}}
            </div>
        </div>
</script>

    <script type="text/javascript">
        var key = 0;

        function addPlateform()
        {
            $("#platformTemplate").tmpl([{'key': key}]).appendTo("#platformContainer");
            key++;
        }

        $(document).ready(function () {
            addPlateform();

            $('#btn-add-more').on('click', function (e) {
                addPlateform();
            });

            $('body').on('click', '.btn-remove', function (e) {
                $(this).closest('.col-12').remove();
                key++;
            });

            $('#question_form').on('submit', function (e) {
                e.preventDefault();
                $this = $(this);

                $.ajax({
                    method: $this.attr('method'),
                    url: $this.attr('action'),
                    data: $this.serialize(),
                    beforeSend: function () {
                        $this.find(':submit').buttonLoading();
                    },
                    complete: function () {
                        $this.find(':submit').buttonReset();
                    },
                    success: function (data) {
                        $('#qa-div').html(data);
                    },
                    error: function (jqXhr) {
                        swalError(jqXhr);
                    }
                });
            })
        });
    </script>
@endpush
