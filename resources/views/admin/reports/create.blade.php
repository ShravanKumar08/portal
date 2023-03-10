@extends('layouts.master')

@section('content')
 <div class="row justify-content-md-center">
        <div class="col-7">
            <div class="card">
                <div class="card-body">
                    <div class="form-body">
                        {{ Form::open(['route' => "report.create", 'class' => 'form-horizontal','method' => 'GET']) }}
                        <div class="row p-t-20">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('employee_id','Choose Employee*',['class'=>'']) }}
                                    {{ Form::select('employee_id', $employees, $Model->employee_id, ['class' => 'form-control','placeholder'=>'Select Employee']) }}
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
 @if($Model->employee_id)
    <div class="row justify-content-md-center"> 
        <div class="col-7">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => 'report.store', 'class' => 'form-horizontal']) }}
                    @include('admin.reports.partials.form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
 @endif
 
    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                $('select[name="employee_id"]').on('change', function () {
                    $(this).closest('form').submit();
                });
            });
        </script>
    @endpush
@endsection