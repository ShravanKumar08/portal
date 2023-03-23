@extends('layouts.master')
@push('stylesheets')
<link rel="stylesheet" href="{{ asset('/css/jquery.steps.css') }}">
<link rel="stylesheet" href="{{ asset('/css/interview.css') }}">
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            {{ Form::open(['route' => 'employee.idps.store', 'class' => 'form-horizontal','id'=>'idp_post', 'enctype'=>"multipart/form-data"]) }}
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="background: white;">
                    <div class="get-in-touch">
                        <div id="idp-form">
                            <h3>Step 1</h3>
                                <fieldset>  <legend>Personal Information</legend>
                                    <div class="form">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <strong>Name : </strong> {{\Auth::user()->name}}
                                            </div>
                                        </div>
                                        <div class="row m-t-15">
                                            <div class="col-md-6">
                                                <strong>Position : </strong>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Time in Position/Date of Hire : </strong>
                                            </div>
                                        </div>
                                        <div class="row m-t-15">
                                            <div class="col-md-6">
                                                <strong>Manager's Name : </strong>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Mentor’s Name (if applicable) : </strong>
                                            </div>
                                        </div>
                                        <div class="row m-t-15">
                                            <div class="col-md-12">
                                                <strong>CV </strong> (outline your CV in a brief version)
                                            </div>
                                            <div class="col-md-12  m-t-15">
                                                <div class="form-group">
                                                    {{ Form::textarea('cv', @$model->cv, ['class' => 'form-control', 'required']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>            
                                </fieldset>
                                <h3>Step 2</h3>
                                <fieldset>  <legend>Personal Motivation & Drivers</legend>
                                    <div class="form">
                                        <table class="table">
                                            <thead>
                                              <tr>
                                                <th class="w-50 position-absolute top-0 start-0"><strong> Personal Motivation </strong><br>(What motivates me in a job)</th>
                                                <th><strong> Work Life Balance considerations </strong><br>
                                                    <span>(Please reflect on how well you manage to balance your life on those dimensions that matter to you. Examples could be: 
                                                    Ability to develop professionally, to live healthy; Family time, Time to yourself/ friends) <span>
                                                </th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                              <tr>
                                                <td>
                                                    @if(!@$model)
                                                        @php
                                                            $key_2_1=0;   
                                                        @endphp
                                                        <div class="row m-t-10">
                                                            <div class="col-md-9">
                                                                {{ Form::text("personal_motivation[personal_motivation][$key_2_1]", '', ['class' => 'form-control', 'required']) }}
                                                            </div>
                                                        </div>
                                                        <div class="personal-motivation">
                                                        </div>
                                                    @else
                                                        <div class="personal-motivation">
                                                            @foreach ($model->personal_motivation['personal_motivation'] as $key_2_1 => $skill)
                                                                <div class="row m-t-10">
                                                                    <div class="col-md-9">
                                                                        {{ Form::text("personal_motivation[personal_motivation][$key_2_1]", "$skill", ["class" => "form-control", 'required']) }}
                                                                    </div>
                                                                    @if ($key_2_1 > 0)
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
                                                            <span data-key="{{$key_2_1}}" data-name="personal_motivation[personal_motivation][key]" data-field="personal-motivation" class="btn btn-warning add-extra-field"> <i class="fa fa-plus"></i> Add details</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if(!@$model)
                                                        @php
                                                            $key_2_2 = 0;   
                                                        @endphp
                                                        <div class="row m-t-10">
                                                            <div class="col-md-9">
                                                                {{ Form::text("personal_motivation[work_life_balance ][$key_2_2]", '', ['class' => 'form-control', 'required']) }}
                                                            </div>
                                                        </div>
                                                        <div class="work-life-balance">
                                                        </div>
                                                    @else
                                                        <div class="work-life-balance">
                                                            @foreach ($model->personal_motivation['work_life_balance'] as $key_2_2 => $skill)
                                                                <div class="row m-t-10">
                                                                    <div class="col-md-9">
                                                                        {{ Form::text("personal_motivation[work_life_balance][$key_2_2]", "$skill", ["class" => "form-control", 'required']) }}
                                                                    </div>
                                                                    @if ($key_2_2 > 0)
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
                                                            <span data-key="{{$key_2_2}}"data-name="personal_motivation[work_life_balance][key]" data-field="work-life-balance" class="btn btn-warning add-extra-field"> <i class="fa fa-plus"></i> Add details</span>
                                                        </div>
                                                    </div>
                                                </td>
                                              </tr>
                                            </tbody>
                                        </table>
                                    </div>   
                                </fieldset>
                                <h3>Step 3</h3>
                                <fieldset>  <legend>Current and future job aspirations</legend>
                                    <div class="form">
                                        <table class="table">
                                            <thead>
                                              <tr>
                                                <th colspan="2" class="text-center bg-gray"> 
                                                    Requirements in your CURRENT job <br/>
                                                    What are the requirements you need in order to perform your job as it looks today ?
                                                </th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th class="w-50 position-absolute top-0 start-0"><strong> Personal Motivation </strong><br>(What motivates me in a job)</th>
                                                    <th><strong> Work Life Balance considerations </strong><br>
                                                        <span>(Please reflect on how well you manage to balance your life on those dimensions that matter to you. Examples could be: 
                                                        Ability to develop professionally, to live healthy; Family time, Time to yourself/ friends) <span>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        @if(!@$model)
                                                            @php
                                                                $key_3_1=0;
                                                            @endphp
                                                            <div class="row m-t-10">
                                                                <div class="col-md-9">
                                                                    {{ Form::text("current_job_requirements[technical][$key_3_1]", '', ['class' => 'form-control', 'required']) }}
                                                                </div>
                                                            </div>
                                                            <div class="technical-requirements">
                                                            </div>
                                                        @else
                                                            <div class="technical-requirements">
                                                                @foreach ($model->current_job_requirements['technical'] as $key_3_1 => $skill)
                                                                    <div class="row m-t-10">
                                                                        <div class="col-md-9">
                                                                            {{ Form::text("current_job_requirements[technical][$key_3_1]", "$skill", ["class" => "form-control", 'required']) }}
                                                                        </div>
                                                                        @if ($key_3_1 > 0)
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
                                                                <span data-key="{{$key_3_1}}" data-name="current_job_requirements[technical][key]" data-field="technical-requirements" class="btn btn-warning add-extra-field"> <i class="fa fa-plus"></i> Add details</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if(!@$model)
                                                            @php
                                                                $key_3_2=0;   
                                                            @endphp
                                                            <div class="row m-t-10">
                                                                <div class="col-md-9">
                                                                    {{ Form::text("current_job_requirements[behavioral][$key_3_2]", '', ['class' => 'form-control', 'required']) }}
                                                                </div>
                                                            </div>
                                                            <div class="behavioral-requirements">
                                                            </div>
                                                        @else
                                                            <div class="behavioral-requirements">
                                                                @foreach ($model->current_job_requirements['behavioral'] as $key_3_2 => $skill)
                                                                    <div class="row m-t-10">
                                                                        <div class="col-md-9">
                                                                            {{ Form::text("current_job_requirements[behavioral][$key_3_2]", "$skill", ["class" => "form-control", 'required']) }}
                                                                        </div>
                                                                        @if ($key_3_2 > 0)
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
                                                                <span data-key="{{$key_3_2}}" data-name="current_job_requirements[behavioral][key]" data-field="behavioral-requirements" class="btn btn-warning add-extra-field"> <i class="fa fa-plus"></i> Add details</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                              </tr>
                                            </tbody>
                                        </table>
                                        <table class="table">
                                            <thead>
                                              <tr>
                                                <th colspan="2" class="text-center bg-gray"> 
                                                    Aspirations for a FUTURE work life <br/>
                                                    What are the aspirations for your future work life?  (Note that future work life could be same 
                                                    job as today; new responsibilities in your current job or a new/different job)
                                                </th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th class="w-50 position-absolute top-0 start-0"><strong> Short-term goals (1-2 years): </strong></th>
                                                    <th><strong> Long-term goals (2-3 years): </strong></th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        @if(!@$model)
                                                            @php
                                                                $key_3_3=0;   
                                                            @endphp
                                                            <div class="row m-t-10">
                                                                <div class="col-md-9">
                                                                    {{ Form::text("goals[short_term][$key_3_3]", '', ['class' => 'form-control', 'required']) }}
                                                                </div>
                                                            </div>
                                                            <div class="short-term-goals">
                                                            </div>
                                                        @else
                                                            <div class="short-term-goals">
                                                                @foreach ($model->goals['short_term'] as $key_3_3 => $skill)
                                                                    <div class="row m-t-10">
                                                                        <div class="col-md-9">
                                                                            {{ Form::text("goals[short_term][$key_3_3]", "$skill", ["class" => "form-control", 'required']) }}
                                                                        </div>
                                                                        @if ($key_3_3 > 0)
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
                                                                <span data-key="{{$key_3_3}}" data-name="goals[short_term][key]" data-field="short-term-goals" class="btn btn-warning add-extra-field"> <i class="fa fa-plus"></i> Add details</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if(!@$model)
                                                            @php
                                                                $key_3_4=0;   
                                                            @endphp
                                                            <div class="row m-t-10">
                                                                <div class="col-md-9">
                                                                    {{ Form::text("goals[long_term][$key_3_4]", '', ['class' => 'form-control', 'required']) }}
                                                                </div>
                                                            </div>
                                                            <div class="long-term-goals">
                                                            </div>
                                                        @else
                                                            <div class="long-term-goals">
                                                                @foreach ($model->goals['long_term'] as $key_3_4 => $skill)
                                                                    <div class="row m-t-10">
                                                                        <div class="col-md-9">
                                                                            {{ Form::text("goals[long_term][$key_3_4]", "$skill", ["class" => "form-control", 'required']) }}
                                                                        </div>
                                                                        @if ($key_3_4 > 0)
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
                                                                <span data-key="{{$key_3_4}}" data-name="goals[long_term][]" data-field="long-term-goals" class="btn btn-warning add-extra-field"> <i class="fa fa-plus"></i> Add details</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                              </tr>
                                            </tbody>
                                        </table>
                                        <table class="table">
                                            <thead>
                                              <tr>
                                                <th colspan="2" class="text-center bg-gray"> 
                                                    If you have aspirations for new tasks or job assignments, what would be the requirements in these assignments?
                                                </th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th class="w-50 position-absolute top-0 start-0"><strong> Requirements in new tasks/job assignments (functional, technical) </strong></th>
                                                    <th><strong> Requirements in new tasks/job assignments (behavioral) </strong></th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        @if(!@$model)
                                                            @php
                                                                $key_3_5=0;   
                                                            @endphp
                                                            <div class="row m-t-10">
                                                                <div class="col-md-9">
                                                                    {{ Form::text("assignments[technical][$key_3_5]", '', ['class' => 'form-control', 'required']) }}
                                                                </div>
                                                            </div>
                                                            <div class="assignments-technical">
                                                            </div>
                                                        @else
                                                            <div class="assignments-technical">
                                                                @foreach ($model->assignments['technical'] as $key_3_5 => $skill)
                                                                    <div class="row m-t-10">
                                                                        <div class="col-md-9">
                                                                            {{ Form::text("assignments[technical][$key_3_5]", "$skill", ["class" => "form-control", 'required']) }}
                                                                        </div>
                                                                        @if ($key_3_5 > 0)
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
                                                                <span data-key="{{$key_3_5}}" data-name="assignments[technical][key]" data-field="assignments-technical" class="btn btn-warning add-extra-field"> <i class="fa fa-plus"></i> Add details</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if(!@$model)
                                                            @php
                                                                $key_3_6=0;
                                                            @endphp
                                                            <div class="row m-t-10">
                                                                <div class="col-md-9">
                                                                    {{ Form::text("assignments[behavioral][$key_3_6]", '', ['class' => 'form-control', 'required']) }}
                                                                </div>
                                                            </div>
                                                            <div class="assignments-behavioral">
                                                            </div>
                                                        @else
                                                            <div class="assignments-behavioral">
                                                                @foreach ($model->assignments['behavioral'] as $key_3_6 => $skill)
                                                                    <div class="row m-t-10">
                                                                        <div class="col-md-9">
                                                                            {{ Form::text("assignments[behavioral][$key_3_6]", "$skill", ["class" => "form-control", 'required']) }}
                                                                        </div>
                                                                        @if ($key_3_6 > 0)
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
                                                                <span data-key="{{$key_3_6}}" data-name="assignments[behavioral][key]" data-field="assignments-behavioral" class="btn btn-warning add-extra-field"> <i class="fa fa-plus"></i> Add details</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                              </tr>
                                            </tbody>
                                        </table>
                                    </div>   
                                </fieldset>
                                <h3>Step 4</h3>
                                <fieldset>  <legend>Analysis of development needs</legend>
                                    <div class="form">
                                        <table class="table">
                                            <thead>
                                              <tr>
                                                <th colspan="2" class="text-center bg-gray"> 
                                                    Strengths<br/>
                                                    1. Looking at current and future job requirements, what are your most important strengths?
                                                    2. Consider including strengths identified under section 2
                                                </th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th class="w-50 position-absolute top-0 start-0"><strong> Functional/technical strengths</strong></th>
                                                    <th><strong> Behavioral strengths </strong></th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        @if(!@$model)
                                                            @php
                                                                $key_4_1=0;
                                                            @endphp
                                                            <div class="row m-t-10">
                                                                <div class="col-md-9">
                                                                    {{ Form::text("strengths[technical][$key_4_1]", '', ['class' => 'form-control', 'required']) }}
                                                                </div>
                                                            </div>
                                                            <div class="technical-strengths">
                                                            </div>
                                                        @else
                                                            <div class="technical-strengths">
                                                                @foreach ($model->strengths['technical'] as $key_4_1 => $skill)
                                                                    <div class="row m-t-10">
                                                                        <div class="col-md-9">
                                                                            {{ Form::text("strengths[technical][$key_4_1]", "$skill", ["class" => "form-control", 'required']) }}
                                                                        </div>
                                                                        @if ($key_4_1 > 0)
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
                                                                <span data-key="{{$key_4_1}}" data-name="strengths[technical][key]" data-field="technical-strengths" class="btn btn-warning add-extra-field"> <i class="fa fa-plus"></i> Add details</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if(!@$model)
                                                            @php
                                                                $key_4_2=0;
                                                            @endphp
                                                            <div class="row m-t-10">
                                                                <div class="col-md-9">
                                                                    {{ Form::text("strengths[behavioral][$key_4_2]", '', ['class' => 'form-control', 'required']) }}
                                                                </div>
                                                            </div>
                                                            <div class="behavioral-strengths">
                                                            </div>
                                                        @else
                                                            <div class="behavioral-strengths">
                                                                @foreach ($model->strengths['behavioral'] as $key_4_2 => $skill)
                                                                    <div class="row m-t-10">
                                                                        <div class="col-md-9">
                                                                            {{ Form::text("strengths[behavioral][$key_4_2]", "$skill", ["class" => "form-control", 'required']) }}
                                                                        </div>
                                                                        @if ($key_4_2 > 0)
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
                                                                <span data-key="{{$key_4_2}}" data-name="strengths[behavioral][key]" data-field="behavioral-strengths" class="btn btn-warning add-extra-field"> <i class="fa fa-plus"></i> Add details</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                              </tr>
                                            </tbody>
                                        </table>
                                        <table class="table">
                                            <thead>
                                              <tr>
                                                <th colspan="2" class="text-center bg-gray">
                                                    Development Needs</br>
                                                    1. Given current and future job requirements, what skills and behaviors do you need to further 
                                                    develop ?
                                                    2. Please consider including development areas identified under section 2
                                                </th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th class="w-50 position-absolute top-0 start-0"><strong> Functional/technical competencies:</strong></th>
                                                    <th><strong> Behavioral competencies: </strong></th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        @if(!@$model)
                                                            @php
                                                                $key_4_3=0;
                                                            @endphp
                                                            <div class="row m-t-10">
                                                                <div class="col-md-9">
                                                                    {{ Form::text("development_needs[technical][$key_4_3]", '', ['class' => 'form-control', 'required']) }}
                                                                </div>
                                                            </div>
                                                            <div class="development-needs-technical">
                                                            </div>
                                                        @else
                                                            <div class="development-needs-technical">
                                                                @foreach ($model->development_needs['technical'] as $key_4_3 => $skill)
                                                                    <div class="row m-t-10">
                                                                        <div class="col-md-9">
                                                                            {{ Form::text("development_needs[technical][$key_4_3]", "$skill", ["class" => "form-control", 'required']) }}
                                                                        </div>
                                                                        @if ($key_4_3 > 0)
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
                                                                <span data-key="{{$key_4_3}}" data-name="development_needs[technical][key]" data-field="development-needs-technical" class="btn btn-warning add-extra-field"> <i class="fa fa-plus"></i> Add details</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if(!@$model)
                                                            @php
                                                                $key_4_4=0;
                                                            @endphp
                                                            <div class="row m-t-10">
                                                                <div class="col-md-9">
                                                                    {{ Form::text("development_needs[behavioral][$key_4_4]", '', ['class' => 'form-control', 'required']) }}
                                                                </div>
                                                            </div>
                                                            <div class="development-needs-behavioral">
                                                            </div>
                                                        @else
                                                            <div class="development-needs-behavioral">
                                                                @foreach ($model->development_needs['behavioral'] as $key_4_4 => $skill)
                                                                    <div class="row m-t-10">
                                                                        <div class="col-md-9">
                                                                            {{ Form::text("development_needs[behavioral][$key_4_4]", "$skill", ["class" => "form-control", 'required']) }}
                                                                        </div>
                                                                        @if ($key_4_4 > 0)
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
                                                                <span data-key="{{$key_4_4}}" data-name="development_needs[behavioral][key]" data-field="development-needs-behavioral" class="btn btn-warning add-extra-field"> <i class="fa fa-plus"></i> Add details</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>   
                                </fieldset>
                                <h3>Step 5</h3>
                                <fieldset>  <legend>Development Action Plan</legend>
                                    <div class="form">
                                        <div class="row m-t-15">
                                            <div class="col-md-12">
                                                <strong>Competency to develop: </strong>
                                            </div>
                                            <div class="col-md-12  m-t-15">
                                                <div class="form-group">
                                                    {{ Form::textarea('development_action_plan[develop]', @$model->development_action_plan['develop'], ['class' => 'form-control', 'required', 'rows' => 3]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-t-15">
                                            <div class="col-md-12">
                                                <strong>SMART Development Goal: </strong>
                                            </div>
                                            <div class="col-md-12  m-t-15">
                                                <div class="form-group">
                                                    {{ Form::textarea('development_action_plan[goal]', @$model->development_action_plan['goal'], ['class' => 'form-control', 'required', 'rows' => 3]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-t-15">
                                            <div class="col-md-12">
                                                <strong>Actions/Activities: </strong>
                                            </div>
                                            <div class="col-md-12  m-t-15">
                                                <div class="form-group">
                                                    {{ Form::textarea('development_action_plan[action]', @$model->development_action_plan['action'], ['class' => 'form-control', 'required', 'rows' => 3]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-t-15">
                                            <div class="col-md-12">
                                                <strong>Completion date: </strong>
                                            </div>
                                            <div class="col-md-12  m-t-15">
                                                <div class="form-group">
                                                    {{ Form::text('development_action_plan[completion_date]', @$model->development_action_plan['completion_date'], ['class' => 'form-control', 'required']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-t-15">
                                            <div class="col-md-12">
                                                <strong>Measures: </strong>
                                            </div>
                                            <div class="col-md-12  m-t-15">
                                                <div class="form-group">
                                                    {{ Form::textarea('development_action_plan[measures]', @$model->development_action_plan['measures'], ['class' => 'form-control', 'required', 'rows' => 3]) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-t-15">
                                            <div class="col-md-12">
                                                <strong>Progress: </strong>
                                            </div>
                                            <div class="col-md-12  m-t-15">
                                                <div class="form-group">
                                                    {{ Form::textarea('development_action_plan[progress]', @$model->development_action_plan['progress'], ['class' => 'form-control', 'required', 'rows' => 3]) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>        
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>
@push('stylesheets')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" rel="stylesheet">
    <style>
        .wizard>.steps>ul>li {
            width: 15% !important ;
        }
        .wizard>.steps>ul>li, .wizard>.actions>ul>li {
            margin-right: 55px !important ;
        }
        .get-in-touch .form-control {
            height: 50% !important;
            border: 1px solid #ccc  !important;
        }
        .table thead th {
            vertical-align: inherit  !important;
        }
        .bg-gray {
            background: gray;
            color: white;
        }
        
    </style>
@endpush
@push('scripts')
    <script src="{{asset('js/jquery.validate.js')}}"></script>
    <script src="{{asset('js/jquery.steps.js')}}"></script>
    <script>
        $(document).ready(function (){
            $("select2").select();

            var div = $("#idp-form").show();
            var form = $("#idp_post");
            
            div.steps({
                    headerTag: "h3",
                    bodyTag: "fieldset",
                    transitionEffect: "slideLeft",
                    enableAllSteps: true,
                    onStepChanging: function (event, currentIndex, newIndex)
                    {
                        if (currentIndex == 0 && (newIndex == 2 || newIndex == 3 || newIndex == 4)) {
                            return false;
                        }
                        if (currentIndex == 1 && (newIndex == 3 || newIndex == 4)) {
                            return false;
                        }
                        if (currentIndex == 2 && (newIndex == 4)) {
                            return false;
                        }
                        // Allways allow previous action even if the current form is not valid!
                        if (currentIndex > newIndex)
                        {
                            return true;
                        }
                        // Forbid next action on "Warning" step if the user is to young
                        if (newIndex === 5)
                        {
                            return false;
                        }
                        // Needed in some cases if the user went back (clean up)
                        if (currentIndex < newIndex)
                        {
                            // To remove error styles
                            div.find(".body:eq(" + newIndex + ") label.error").remove();
                            div.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                        }
                        form.validate().settings.ignore = ":disabled,:hidden";
                        return form.valid();
                        //return true;
                    },
                    onStepChanged: function (event, currentIndex, priorIndex)
                    {
                        // Used to skip the "Warning" step if the user is old enough.
                        // if (currentIndex === 2)
                        // {
                        //     div.steps("next");
                        // }
                        // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
                        // if (currentIndex === 1 && priorIndex === 2)
                        // {
                        //     div.steps("previous");
                        // }
                    },
                    onFinishing: function (event, currentIndex)
                    {
                        form.validate().settings.ignore = ":disabled";
                        return form.valid()
                    },
                    onFinished: function (event, currentIndex)
                    {
                            var newurl = $('#idp_post').data('url');
                            $('#idp_post').attr('action',newurl);
                            document.getElementById('idp_post').submit();
                    }
                });
            });
    </script>
    
    <script>
    $(document).ready(function(){
        removeSkillSet();

        $(".add-extra-field").on('click', function (e) {

            var key     = $(this).data('key') + 1;
            var name    = $(this).data('name').replace("key", key)
            
            $html   = "<div class='row m-t-10'> \
                        <div class='col-md-9'> \
                            <input class='form-control' required name="+name+" type='text' value=''>\
                        </div> \
                        <div class='col-md-3'> \
                            <button class='btn btn-danger btn-remove'> <i class='fa fa-trash'></i></button> \
                        </div> \
                    </div>";
            $('.'+$(this).data('field')).append($html);
            var key = $(this).data('key', key);

            removeSkillSet()
        });

        function removeSkillSet()
        {
            $(".btn-remove").on('click', function (e) {
                $(this).parent().parent().remove();
            });
        }
    });
    </script>
@endpush;
    
@endsection

