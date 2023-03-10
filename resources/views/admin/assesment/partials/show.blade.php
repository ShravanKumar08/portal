@extends('layouts.master')
@section('content')
  <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row p-t-20">
                        <div class="col-md-12">
                            @foreach($evaluations as $evaluation)
                                @php
                                    $employee_name = $evaluation->assessment->employee->name;
                                    $period = $evaluation->assessment->period;
                                @endphp
                            @endforeach
                        <h4 class="card-title text-center">{{ $employee_name }}'s Assessment  for {{ date('jS F Y', strtotime($evaluation->assessment->from))}} to {{ date('jS F Y', strtotime($evaluation->assessment->to)) }}</h4>
                            <div class="card-body">
                               
                                <!-- Nav tabs -->
                                <div class="vtabs customvtab">
                                    <ul class="nav nav-tabs tabs-vertical" role="tablist">
                                        @foreach ($evaluations as $key =>$evaluation)
                                            <li class="nav-item "> <a class="nav-link {{ $evaluation->evaluator_id == $evaluation->assessment->employee_id ? 'active' : ''}}"  data-toggle="tab" href="#home{{ $loop->iteration }}" role="tab"><span class="hidden-xs-down">{{$evaluation->employee->shortname}}</span> 
                                                <span style=" color: red; "title="Pending"><i class="fa fa-{{ $evaluation->status == 0 ? 'info-circle'  : ''}}"></i></a> </li></span>
                                        @endforeach
                                    </ul>
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        @foreach ($evaluations as $key =>$evaluation)
                                            <div class="tab-pane {{ $evaluation->evaluator_id == $evaluation->assessment->employee_id ? 'active' : ''}} " id="home{{ $loop->iteration }}" role="tabpanel">
                                                {{ Form::open(['route' => 'assesment.reupdate', 'class' => 'form-horizontal']) }}
                                                <a href='{{ url("admin/evaluation/{$evaluation['id']}/pdf") }}' class="btn btn-sm btn-success"><i class="fa fa-download"></i> Download</a>
                                                <button type="submit" class="btn btn-sm btn-primary pull-right"> <i class="fa fa-check"></i> Save</button>
                                                <br><br>
                                                {!! Form::hidden('id', @$evaluation->evaluator_id) !!}
                                                {{ Form::textarea('teamleader', $evaluation['evaluation'], ['class' => 'form-control textarea','rows'=> 5]) }}
                                                <div class="row">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.partials.multiselect_scripts')

    @push('stylesheets')
        <link rel="stylesheet" href="{{ asset('assets/plugins/html5-editor/bootstrap-wysihtml5.css') }}"/>
        <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    
        <style>
            .bootstrap-tagsinput {
                width: 100% !important;
            }
    
            .bootstrap-tagsinput input {
                min-width: 500px;
            }
        </style>
    @endpush
    
    @push('scripts')
        <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
         <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
    
        <script type="text/javascript">
            $(document).ready(function () {
                $('.select2').select2();
    
                $('#date-range').datepicker({
                    // toggleActive: true,
                    format: 'yyyy-mm-dd',
                    autoclose: true, todayHighlight: true,
                });
    
                tinymce.init({
                    selector: ".textarea",
                    theme: "modern",
                    height: 300,
                    plugins: [
                        "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                        "save table contextmenu directionality emoticons template paste textcolor"
                    ],
                    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
                });
            });
        </script>
    @endpush
@endsection