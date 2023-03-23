@extends('layouts.master')

@section('content')
    @if(@$model)
    <div class="col-lg-12 col-xlg-12 col-md-12">
        <div class="card">
            <div class="tab-content">
                <div class="tab-pane active" id="personalinfo" role="tabpanel">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th colspan="2" >Name: {{$model->employee->name}}</th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="w-50"><strong>Designation:</strong> &nbsp;{{$model->employee->designationName}}</td>
                                    <td><strong>Time in Position/Date of Hire:</strong> &nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="w-50"><strong>Manager's Name: </strong> &nbsp;</td>
                                    <td><strong>Mentor's Name:</strong> &nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="2" ><strong>Curriculum vitae (CV) </strong> <br>{{$model->cv}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                    <th colspan="2" class="text-center bg-gray">
                                        Personal Motivation & Drivers <br>
                                    </th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="w-50"><h3><span class="label label-default td-head">Personal Motivation</span></h3>
                                    <td><h3><span class="label label-default td-head">Work Life Balance considerations</span></h3>
                                </tr>
                                <tr>
                                    <td>
                                        @foreach ($model->personal_motivation['personal_motivation'] as $value)
                                            <i class="mdi  mdi-chevron-double-right"></i> &nbsp;{{$value}} <br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($model->personal_motivation['work_life_balance'] as $value)
                                            <i class="mdi  mdi-chevron-double-right"></i> &nbsp;{{$value}} <br/>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                    <th colspan="2" class="text-center bg-gray">
                                        Requirements in your CURRENT job <br>
                                    </th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="w-50"><h3><span class="label label-default td-head">Functional/technical requirements</span></h3>
                                    <td><h3><span class="label label-default td-head">Behavioral requirements</span></h3>
                                </tr>
                                <tr>
                                    <td>
                                        @foreach ($model->current_job_requirements['technical'] as $value)
                                            <i class="mdi  mdi-chevron-double-right"></i> &nbsp;{{$value}} <br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($model->current_job_requirements['behavioral'] as $value)
                                            <i class="mdi  mdi-chevron-double-right"></i> &nbsp;{{$value}} <br/>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                    <th colspan="2" class="text-center bg-gray">
                                        Aspirations for a FUTURE work life <br>
                                    </th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="w-50"><h3><span class="label label-default td-head">Short-term goals (1-2 years)</span></h3>
                                    <td><h3><span class="label label-default td-head">Long-term goals (2-3 years)</span></h3>
                                </tr>
                                <tr>
                                    <td>
                                        @foreach ($model->goals['short_term'] as $value)
                                            <i class="mdi  mdi-chevron-double-right"></i> &nbsp;{{$value}} <br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($model->goals['long_term'] as $value)
                                            <i class="mdi  mdi-chevron-double-right"></i> &nbsp;{{$value}} <br/>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                    <th colspan="2" class="text-center bg-gray">
                                        If you have aspirations for new tasks or job assignments, what would be the requirements in these assignments? <br>
                                    </th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="w-50"><h3><span class="label label-default td-head">Requirements in new tasks/job assignments (functional, technical)</span></h3>
                                    <td><h3><span class="label label-default td-head">Requirements in new tasks/job assignments (behavioral)</span></h3>
                                </tr>
                                <tr>
                                    <td>
                                        @foreach ($model->assignments['technical'] as $value)
                                            <i class="mdi  mdi-chevron-double-right"></i> &nbsp;{{$value}} <br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($model->assignments['behavioral'] as $value)
                                            <i class="mdi  mdi-chevron-double-right"></i> &nbsp;{{$value}} <br/>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                    <th colspan="2" class="text-center bg-gray">
                                        Strengths <br>
                                    </th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="w-50"><h3><span class="label label-default td-head">Functional/technical strengths</span></h3>
                                    <td><h3><span class="label label-default td-head">Behavioral strengths</span></h3>
                                </tr>
                                <tr>
                                    <td>
                                        @foreach ($model->strengths['technical'] as $value)
                                            <i class="mdi  mdi-chevron-double-right"></i> &nbsp;{{$value}} <br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($model->strengths['behavioral'] as $value)
                                            <i class="mdi  mdi-chevron-double-right"></i> &nbsp;{{$value}} <br/>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                    <th colspan="2" class="text-center bg-gray">
                                        Development Needs <br>
                                    </th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="w-50"><h3><span class="label label-default td-head">Functional/technical competencies</span></h3>
                                    <td><h3><span class="label label-default td-head">Behavioral competencies</span></h3>
                                </tr>
                                <tr>
                                    <td>
                                        @foreach ($model->development_needs['technical'] as $value)
                                            <i class="mdi  mdi-chevron-double-right"></i> &nbsp;{{$value}} <br/>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($model->development_needs['behavioral'] as $value)
                                            <i class="mdi  mdi-chevron-double-right"></i> &nbsp;{{$value}} <br/>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th colspan="2" class="bg-gray text-center">Development Action Plan</th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="w-50"><strong>Competency to develop</strong></td>
                                    <td>{{$model->development_action_plan['develop']}}</td>
                                </tr>
                                <tr>
                                    <td class="w-50"><strong>SMART Development Goal</strong></td>
                                    <td>{{$model->development_action_plan['goal']}}</td>
                                </tr>
                                <tr>
                                    <td class="w-50"><strong>Actions/Activities</strong></td>
                                    <td>{{$model->development_action_plan['action']}}</td>
                                </tr>
                                <tr>
                                    <td class="w-50"><strong>Completion date</strong></td>
                                    <td>{{$model->development_action_plan['completion_date']}}</td>
                                </tr>
                                <tr>
                                    <td class="w-50"><strong>Measures</strong></td>
                                    <td>{{$model->development_action_plan['measures']}}</td>
                                </tr>
                                <tr>
                                    <td class="w-50"><strong>Progress</strong></td>
                                    <td>{{$model->development_action_plan['progress']}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-lg-4 col-xlg-3 col-md-5">
        </div>
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <img class="card-img" src="{{ asset('assets/images/background/socialbg.jpg') }}" alt="Card image">
                <div class="card-img-overlay card-inverse social-profile d-flex justify-content-md-center">
                    <div class="align-self-center">
                        <p class="text-white">No response from employee</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    @endif
    <style>
        .bg-gray {
            background: gray;
            color: white;
        }
        .td-head {
            color: black;
        }
    </style>
@endsection
