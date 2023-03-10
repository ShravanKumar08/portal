@if(@$Reportitem->id)
    {{ Form::model(@$Reportitem,['route' => [ $route, @$Reportitem->id], 'method' => 'PUT' ,'class' => 'form-horizontal', 'id' => 'reportform']) }}
@else
    {{ Form::open(['route' => $route, 'method' => 'POST' ,'class' => 'form-horizontal', 'id' => 'reportform']) }}
   
@endif

    <hr>
    {{--<div class="form-group m-t-40 row">--}}
        {{--{{ Form::label('','Employee Name:',['class'=>'col-md-3 col-form-label']) }}--}}
        {{--<h4>{{ $auth_employee->name }}</h4>--}}
        {{--</div>--}}
    {{--<div class="form-group row">--}}
        {{--{{ Form::label('', 'Designation:', ['class' => 'col-md-3 col-form-label']) }}--}}
        {{--<h4>{{ $auth_employee->designation->name }}</h4>--}}
        {{--</div>--}}
        
    <div class="row p-t-20">
        <div class="col-md-3">
            <div class="form-group">
                {{ Form::label('projectname', 'Project Name:', ['class' => '']) }} 
               
                @php
                    // $project_items = @$Reportitem->project->name ? [@$Reportitem->project->name => @$Reportitem->project->name] : []
                    $project_items = @$Reportitem->project->name ? [@$Reportitem->project->name => @$Reportitem->project->name] : []
                @endphp
                {{ Form::select('projectname', $project_items, @$Reportitem->project->name, ['class' => 'form-control','placeholder'=>'Select Project','id'=>'project-remote-select2']) }}
                <button type="button" data-toggle="modal"  data-target="#ProjectNameModal" class="btn btn-xs waves-effect waves-light btn-primary mt-2">Create Project</button>
                @if(count($latestProjects) > 0)
                    @foreach($latestProjects as $project)
                        <label class="project-label btn btn-xs btn-info waves-effect waves-light mt-2" data-project-id={{ @$project->project_id }}> {{ $project->project_name }} </label>
                    @endforeach
                @endif
                {{-- {{ Form::text('projectname', @$Reportitem->project->name , ['class' => 'form-control','id' => 'search_project', 'tabindex' => 1]) }} --}}
            </div>
        </div> 
        <div class="col-md-3">
            <div class="form-group">
                {{ Form::label('technology_id','Category:',['class'=>'']) }}
                {{ Form::select('technology_id', @$technology_dropdown, @$Reportitem->technology->id, ['class' => 'form-control select2','placeholder'=>'Select Category']) }}
            </div>
        </div>        
        <div class="col-md-3">
            <div class="form-group">
                {{ Form::label('start', 'Start Time:', ['class' => '']) }}
                {{ Form::text('start', @$Reportitem->start ? \AppHelper::formatTimestring(@$Reportitem->start, 'H:i') : '', ['class' => 'form-control clockpicker changeTimeFormat', 'autocomplete' => 'off','data-autoclose'=>"true",'id' => 'start_time', 'tabindex' => 3, 'data-mask' => "99:99"]) }}
                <span class="font-13 text-muted">{{ @$Reportitem->start ? \AppHelper::formatTimestring(@$Reportitem->start, 'h:i A') : 'HH:mm' }}</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {{ Form::label('end', 'End Time:', ['class' => '']) }}
                <div id="elapsed" class="pull-right text-danger"></div>
                {{ Form::text('end', @$Reportitem->end ? \AppHelper::formatTimestring(@$Reportitem->end, 'H:i') : '', ['class' => 'form-control clockpicker changeTimeFormat', 'autocomplete' => 'off','data-autoclose'=>"true",'id' => 'end_time', 'tabindex' => 4, 'data-mask' => "99:99"]) }}
                <span class="font-13 text-muted">{{ @$Reportitem->end ? \AppHelper::formatTimestring(@$Reportitem->end, 'h:i A') : 'HH:mm' }}</span>             </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('works', 'Summary:*', ['class' => '']) }}
                {{ Form::textarea('works', @$Reportitem->works, ['class' => 'form-control','rows' => 3, 'tabindex' => 6]) }}
                @php
                    $github = json_decode(\App\Models\UserSettings::fetch('GITHUB_CREDENTIALS'));
                @endphp

                @if(@$github->showinreport)
                    <button type="button" data-toggle="modal" data-report_id="{{ @$Reportitem->report->id }}" data-target="#githubModal" class="btn btn-xs waves-effect waves-light btn-warning GithubModal mt-1">Github Latest Commits</button>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('status', 'Status:', ['class' => '']) }}
                <br/>
                @foreach ($status as $id => $s)
                <label class="custom-control custom-radio">
                    {!! Form::radio('status', $id, (@$Reportitem->status == $id), ['class' => 'custom-control-input', 'tabindex' => 5] ); !!} {{$s}}
                    <span class="custom-control-indicator"></span>
                </label>
                @endforeach
            </div>
        </div>        
    </div>
    {{ Form::hidden('report_id', @$Report->id, ['class' => 'form-control', 'id' => 'hidden-report-id']) }}
    <div class="form-actions">
        @if(@$Reportitem->id)
            <button type="submit" class="btn btn-warning"><i class="fa fa-check"></i> Update</button>
            <button type="button" class="btn btn-danger btn-cancel-edit">Cancel</button>
        @else
            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
            <button type="reset" class="btn btn-inverse">Reset</button>
        @endif
    </div>

{{ Form::close() }}

    <div id="ProjectNameModal" data-url="" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Create Project</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    {{ Form::open(['route' => ["employee.report.createprojectname"],'method' => 'GET', 'class' => 'form-horizontal', 'id' => 'project_create_form']) }}

                    <div class="row p-t-20">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('name', 'Project Name:', ['class' => '']) }}
                                {{ Form::text('name', '', ['class' => 'form-control project_name','placeholder' => 'name']) }}
                            </div>
                        </div>
                        <div id="similarity-div">
                        </div>
                        <div class="col-md-12">
                            <div class="form-group pull-right">
                                <button type="submit" class="btn btn-success" ><i class="fa fa-plus"></i> Create Project</button>
                            </div>
                        </div>
                        </div>
                {{ Form::close() }}
                </div>
            </div>
        <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>
