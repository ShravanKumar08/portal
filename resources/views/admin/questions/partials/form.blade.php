<div class="form-body">
    <h3 class="card-title">Question</h3>
    <hr>
    <div class="row">
        <div class="col-6">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        {{ Form::label('name', 'Question *', ['class' => '']) }}
                        {{ Form::textarea('name', old('name'), ['class' => 'form-control','rows'=>2]) }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('type', 'Question Type *', ['class' => '']) }}
                        {{ Form::select('type', \App\Models\Question::$question_types, old('type'), ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="col-6 obj">
                    <div class="form-group">
                        @php
                            $selected_opt_count = (@$Model->opt_count) ? @$Model->opt_count : '4';
                        @endphp
                        {{ Form::label('opt_count', 'Option Count', ['class' => '']) }}
                        {{ Form::select('opt_count', \App\Models\Question::$option_counts,$selected_opt_count, ['class' => 'form-control']) }}
                    </div>
                </div>
            </div>

            <div class="row obj obj_opt">
                <!-- <div class="card-group"> -->
                <?php
                $n = (@$Model->opt_count) ? @$Model->opt_count : '4';
                $opt_val = json_decode(@$Model->options);
                for($i = 1;$i <= $n;$i++) {
                $ans = (@$Model->answer == $opt_val[$i - 1]) ? @$opt_val[$i - 1] : old('answer');
                ?>
                <div class="col-12">
                    <?php echo Form::label('options_' . $i, 'Option ' . $i, ['class' => '']); ?>
                    <div class="form-group">
                        <label class="custom-control custom-radio col-12">
                            {{ Form::radio('answer', @$ans,(old('answer') == $opt_val[$i-1]), ['class' => 'custom-control-input ans-radio','data-id'=>'options_'.$i]) }}
                            {{ Form::textarea('options[]', $opt_val[$i - 1], ['class' => 'form-control options', 'id' => 'options_' . $i,'rows' => 2]) }}
                            <span class="custom-control-indicator" style="margin-top:6px;"></span>
                        </label>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="row desc" style="display:none;">
                <div class="col-12">
                    <div class="form-group">
                        @php
                            $answer = (@$Model->type=="D") ? @$Model->answer : old('description_ans');
                        @endphp
                        {{ Form::label('description_ans', 'Answer *', ['class' => '']) }}
                        {{ Form::textarea('description_ans', $answer, ['class' => 'form-control']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        @php
                            $selected_platforms = (@$Model->platforms) ? @$Model->platforms->pluck('id') : [];
                        @endphp
                        {{ Form::label('platforms[]', 'Platforms *', ['class' => '']) }}
                        {{ Form::select('platforms[]', $platforms, $selected_platforms, ['class' => 'form-control searchablemultiselect', 'multiple' => true]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-9">
                    <div class="form-group">
                        {{ Form::label('grade_id', 'Grade *', ['class' => '']) }}
                        {{ Form::select('grade_id', $grades,old('grade_id'), ['class' => 'form-control','placeholder'=>'Select Grade']) }}
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        {{ Form::label('duration', 'Duration', ['class' => '']) }}
                        {{ Form::select('duration',\App\Models\Question::$question_minutes, old('duration'), ['class' => 'form-control']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-actions">
                <button type="submit" class="btn btn-success btn-submit"><i class="fa fa-check"></i> Save</button>
            </div>
        </div>
    </div>
</div>

@include('layouts.partials.multiselect_scripts')

@push('stylesheets')
    <style type="text/css">
        .ms-container {
            width: 100%;
        }

        .obj .card {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin-bottom: 0 !important;
        }

        .obj .card .card-body {
            padding: 0;
        }
    </style>
@endpush

@push('scripts')
    <script type="text/javascript">
        function checktype() {
            if ($('#type').val() == "O") {
                $(".obj").show();
                $(".desc").hide();
            } else {
                $(".obj").hide();
                $(".desc").show();
            }
        }

        $(document).ready(function () {
            checktype();

            $('body').on('change', '#type', function () {
                checktype();
            });
            $('body').on('click', '.ans-radio', function () {
                var ans = $(this).data("id");
                $(this).val($("#" + ans).val());
            });

            $("#opt_count").on("change", function () {
                var opt_count = $(this).val();
                var opt_html = '';
                for (oi = 1; oi <= opt_count; oi++) {
                    opt_html += '<div class="col-12"><label for="options_' + oi + '" class="">Option ' + oi + '</label><div class="form-group"><label class="custom-control custom-radio col-12"><input type="radio" name="answer" class="custom-control-input ans-radio" data-id="options_' + oi + '" /> <span class="custom-control-indicator" style="margin-top:6px;"></span><input class="form-control options" id="options_' + oi + '" name="options[]" type="text"></label></div></div>';
                }
                $(".obj_opt").html(opt_html);
            });

            $("#question_form").on('submit', function (e) {
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
                        checktype();
                    },
                    success: function (data) {
                        @if(@$Model->id == null)
                            $("#question_form")[0].reset();
                            $("#opt_count").trigger("change");

                            $('html, body').animate({
                                scrollTop: $('textarea[name="name"]').offset().top - 100
                            });

                            $('textarea[name="name"]').focus();
                        @endif

                        swal("Saved!", data.msg, "success");
                    },
                    error: function (jqXhr) {
                        swalError(jqXhr);
                    }
                });
            });
        });
    </script>
@endpush