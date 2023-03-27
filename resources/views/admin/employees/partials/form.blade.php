<div class="form-body">
    <h3 class="card-title">Person Info</h3>
    <hr>
    <div class="row p-t-20">
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('name', 'Name*', ['class' => '']) }}
                {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('employeetype','Employee type*',['class'=>'']) }}
                <div class="form-check">
                    @foreach ($types as $id => $employeetype)
                    <label class="custom-control custom-radio">
                        {!! Form::radio('employeetype', $id, (old('employeetype') == $id), ['class' => 'custom-control-input'] ); !!} {{$employeetype}}
                        <span class="custom-control-indicator"></span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('gender','Gender*',['class'=>'']) }}
                <div class="form-check">
                    @foreach ($gender as $id=>$gen)
                    <label class="custom-control custom-radio">
                        {!! Form::radio('gender', $id, (old('gender') == $id), ['class' => 'custom-control-input'] ); !!} {{$gen}}
                        <span class="custom-control-indicator"></span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('dob','Date of Birth*',['class'=>'']) }}
                {{ Form::text('dob', old('dob'), ['class' => 'form-control datepicker', 'autocomplete' => 'off']) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('designation[name]','Designation*',['class'=>'']) }}
                {{ Form::text('designation[name]', @$Designation->name, ['class' => 'form-control','id'=>'search_text']) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('photo','Photo',['class'=>'']) }}<br/>
                {{ Form::file('photo', ['accept' => 'image/*']) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('officetiming_id','Choose Office Timings*',['class'=>'']) }}
                {{ Form::select('officetiming_id', $officetimings, @$Employee->officetiming_id, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('casual_count_this_year','Casual leave count ('.date('Y').')',['class'=>'']) }}
                {{ Form::text('casual_count_this_year', old('casual_count_this_year'), ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('isTeamLead','Team Lead*',['class'=>'']) }}
                <div class="form-check">
                    @foreach ($isLeads as $id=>$isLead)
                    <label class="custom-control custom-radio">
                        {!! Form::radio('isTeamLead', $id, (@$User->isTeamLead == $id), ['class' => 'custom-control-input'] ); !!} {{$isLead}}
                        <span class="custom-control-indicator"></span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        {{--<div class="col-md-4">--}}
            {{--<div class="form-group">--}}
                {{--{{ Form::label('phone','Phone*',['class'=>'']) }}--}}
                {{--{{ Form::text('phone', old('phone'), ['class' => 'form-control']) }}--}}
                {{--</div>--}}
            {{--</div>--}}
    </div>

    @include('layouts.partials.custom_fields_form')

    <h3 class="box-title m-t-40">Login Details</h3>
    <hr>
    <div class="row p-t-20">
        <div class="col-md-5">
            <div class="form-group">
                {{ Form::label('email','Email*') }}
                {{ Form::text('email', @$User->email, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                {{ Form::label('password','Password*') }}
                {{ Form::password('password',['class'=>'form-control']) }}
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                {{ Form::label('active','Active',['class'=>'']) }} <br/>
                <div class="switch">
                    <label>
                        {{ Form::hidden('active', 0) }}
                        {{ Form::checkbox('active', 1,  @$User->active) }}<span class="lever switch-col-blue"></span>
                    </label>
                </div>
            </div>
        </div>        
    </div>
    <div class="form-actions">
        {!! Form::hidden('uid', @$User->id) !!}
        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
    </div>
</div>

@push('stylesheets')
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" rel="stylesheet">
@endpush

@push('scripts')
<!--Auto complete Search-->
<script type="text/javascript">
    $(document).ready(function () {
        $('#search_text').autocomplete({
            source: '{{ route('employee.searchdesignation') }}',
            minlength: 1,
            autoFocus: true,
        });

        $('input[name="employeetype"]').on("change", function() {
            setCasualCount();
        });

        $('input[name="employee_joinedon"]').on("change", function() {
            setCasualCount();
        });
    });

    function setCasualCount() {
        var casual = 0;

        if($('input[name="employeetype"]:checked').val() == 'P'){
            casual = 12;

            var joindate = $("#employee_joinedon").val();

            if(joindate){
                var splitdate = joindate.split("-");

                if(moment().format('YYYY') == splitdate[0]){
                    casual = (splitdate[2] > 15) ? 12 - splitdate[1]  : 12 - (splitdate[1] - 1);
                }
            }
        }

        $("#casual_count_this_year").val(casual);
    }
</script>
@endpush
