@extends('layouts.master')

@section('content')
<div class="row justify-content-md-center">
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                {{ Form::open(['route' => 'employee.skills.store', 'class' => 'form-horizontal']) }}
                    @if(!@$id)
                        <div class="row m-t-10">
                            <div class="col-md-9">
                                {{ Form::text('skills[]', '', ['class' => 'form-control', 'required']) }}
                            </div>
                        </div>
                        <div class="skill-set-panel">
                        </div>
                    @else
                        <div class="skill-set-panel">
                            @foreach ($skills as $key => $skill)
                                <div class="row m-t-10">
                                    <div class="col-md-9">
                                        {{ Form::text("skills[]", "$skill", ["class" => "form-control", 'required']) }}
                                    </div>
                                    @if ($key > 0)
                                        <div class="col-md-3">
                                            <button class="btn btn-danger btn-remove"> <i class="fa fa-trash"></i></button>
                                        </div>
                                    @endif
                                    
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="row m-t-20">
                        <div class="col-md-6">
                            <span class="btn btn-info add-skill"> <i class="fa fa-plus"></i> Add skill</span>
                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {

            removeSkillSet()

            $(".add-skill").on('click', function (e) {
                $html = '<div class="row m-t-10"> \
                            <div class="col-md-9"> \
                                {{ Form::text("skills[]", '', ["class" => "form-control", "required"]) }} \
                            </div> \
                            <div class="col-md-3"> \
                                <button class="btn btn-danger btn-remove"> <i class="fa fa-trash"></i></button> \
                            </div> \
                        </div>';
                $('.skill-set-panel').append($html);

                removeSkillSet()
            });

            function removeSkillSet() 
            {
                $(".btn-remove").on('click', function (e) {
                    console.log($(this).parent().parent().remove());
                });
            }
        });
    </script>
@endpush