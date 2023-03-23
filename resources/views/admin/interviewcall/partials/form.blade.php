<div class="row row-eq-height no-gutters ">
    <div class="col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5" style="background: #2f897b;">
        <div class="contact-box">
            <div class="call-image text-center">
                <center><h4 id="title">Start Call</h4></center>
                <img  class ="call-image-size" src="/assets/images/start_call.png" alt="">
            </div>
        <div class="found-box hide">
        </div>
    </div>  
</div>
<div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-7" style="background: white; ">
    <div class="get-in-touch">
        <div id="intevriewcall-form">
            <h3>Personal</h3>
                <fieldset>  <legend>Personal Information</legend>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            {{ Form::label('designation_id','Select Designation',['class'=>'']) }}
                            {{ Form::select('candidate[designation_id]',  $designationlist ,  old('candidate.designation_id'), ['id'=>'c_designation','class' => 'form-control select2 required','autocomplete'=>"off",'placeholder'=>'Select Designation ']) }}
                        </div>
                        <div class="form-group col-md-12">
                            {{ Form::label('name','Full name',['class'=>'']) }}  
                            {{ Form::text('candidate[name]', old('candidate.name'), ['id'=>'c_name','class' => 'form-control required checkname','autocomplete'=>"off"]) }}
                        </div>
                        <div class="form-group col-md-12">
                            {{ Form::label('email','Email',['class'=>'']) }}  
                            {{ Form::text('candidate[email]', old('candidate.email'), ['id'=>'c_email','class' => 'form-control required checkemail']) }}
                        </div>
                        <div class="form-group col-md-12">
                            {{ Form::label('mobile','Mobile',['class'=>'']) }}  
                            {{ Form::text('candidate[mobile]',old('candidate.mobile'),['id'=>'c_mobile','class' => 'form-control required checkmobile']) }}
                        </div>
                        <div class="form-group col-md-12">
                            {{ Form::label('permanent_location','Permanent Location',['class'=>'']) }}  
                            {{ Form::textarea('candidate[permanent_location]',old('candidate.permanent_location'),['id'=>'c_per_location','class' => 'form-control required']) }}
                        </div>
                        <div class="form-group col-md-12">
                            {{ Form::label('present_location','Current Location',['class'=>'']) }}
                            {{ Form::textarea('present_location',old('present_location'), ['id'=>'c_present_location','class' => 'form-control required','autocomplete'=>"off"]) }}
                        </div>
                        <div class="form-group col-md-12 radio-custom">
                            <div class="form-check form-check-inline">
                                {{ Form::radio('candidate[gender]', 'M', (old('candidate.gender') == 'M'),['id'=>"male",'class' => 'form-control','checked'=>'checked']) }}
                                <label for="male">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                    {{ Form::radio('candidate[gender]', 'F', (old('candidate.gender') == 'F'),['id'=>"female", 'class' => 'form-control']) }}
                                <label for="female">Female</label>
                            </div>
                        </div>
                        <div class="form-group col-md-12 radio-custom">
                            <div class="form-check form-check-inline">
                            {{ Form::radio('candidate[martial_status]', 'S', (old('candidate.martial_status') == 'S'),['id'=>'single','class' => 'form-control','checked'=>'checked']) }}
                            <label for="single">Single</label>
                        </div>
                        <div class="form-check form-check-inline">
                            {{ Form::radio('candidate[martial_status]', 'M',(old('candidate.martial_status') == 'M'), ['id'=>'married','class' => 'form-control']) }}
                            <label for="married">Married</label>
                            </div>
                        </div>
                    </div>        
                </fieldset>
                <h3>Job</h3>
                <fieldset>  <legend>Job Related Information</legend>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            {{ Form::label('experience','Experience',['class'=>'']) }}
                            {{ Form::number('experience',old('experience'), ['id'=>'c_experience','class' => 'form-control required','autocomplete'=>"off",'min'=>'1','max'=>'30']) }}
                        </div>
                        <div class="form-group col-md-12">
                            {{ Form::label('current_designation','Current Designation',['class'=>'']) }}  
                            {{ Form::textarea('candidate[current_designation]',old('candidate.current_designation'),['id'=>'current_designation','class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('specializtion','Specialization',['class'=>'']) }} 
                            {{ Form::textarea('candidate[technology]',old('candidate.technology'),['id'=>'technology','class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-md-12">
                            {{ Form::label('present_company','Current Company Name',['class'=>'']) }}
                            {{ Form::textarea('present_company',old('present_company'), ['class' => 'form-control required','autocomplete'=>"off"]) }}
                        </div>
                        <div class="form-group col-md-12">
                            {{ Form::label('change_reason','Reason of job Change',['class'=>'']) }}
                            {{ Form::textarea('change_reason',old('change_reason'), ['class' => 'form-control ','autocomplete'=>"off"]) }}
                        </div>
                        <div class="form-group col-md-12">
                            {{ Form::file('resume',['class' => 'form-control','autocomplete'=>"off",'accept'=>'application/pdf,.doc,.docx,.xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document']) }}
                        </div>
                        <div class="form-group col-md-12">
                            {{ Form::label('remarks','Remarks',['class'=>'']) }}
                            {{ Form::textarea('remarks', old('remarks') , ['class' => 'form-control ','autocomplete'=>"off"]) }}
                        </div>
                        @include('layouts.partials.custom_fields_form')
                    </div>   
                </fieldset>
                <h3>Schedule</h3>
                <fieldset>  <legend>Round Related Information</legend>
                    <div class="form-row">
                        @if(@$rounds)
                            @foreach($rounds as $key => $round)
                                <h3>{{$round}}</h3>
                                <div class="form-group col-md-12">
                                    {{ Form::hidden("roundInf[$key][round]",$key, ['id' => "$key-$round-round"]) }}
                                    {{ Form::hidden("roundInf[$key][id]",@$candidateRound[$key-1]->id, ['id' => "$key-$round-round", 'class' => 'round_id']) }}
                                    @if($round == 'Telephonic Interview')
                                        {{ Form::text("roundInf[$key][datetime]",@$candidateRound[$key-1]->datetime, ['id'=>"$key-$round-datetime",'class' => 'form-control datetimepicker','autocomplete'=>"off",'placeholder' => 'Datetime']) }}
                                    @endif
                                    @if($round == 'Final Technical')
                                        {{ Form::select("roundInf[$key][employee_id]", $Employees, @$candidateRound[$key-1]->employee_id,['id'=>"employee_id",'class' => 'form-control','placeholder' => 'select Interviewer']) }}
                                    @endif
                                    {{ Form::textarea("roundInf[$key][remarks]",@$candidateRound[$key-1]->remarks, ['id'=>"$key-$round-remarks",'class' => 'form-control','autocomplete'=>"off",'placeholder' => 'Remarks']) }}
                                    @include('layouts.partials.interview_round_custom_fields', ['key' => $key, 'id' => @$candidateRound[$key-1]->id])
                                </div>
                            @endforeach
                        @endif
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>
        
@push('stylesheets')
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" rel="stylesheet">
<link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endpush
@push('scripts')

<script src="{{asset('js/jquery.validate.js')}}"></script>
<script src="{{asset('js/jquery.steps.js')}}"></script>
<script src="{{asset('assets/plugins/select2/dist/js/select2.min.js')}}"></script>

<script>
    $(document).ready(function (){
        $("select2").select();

        var div = $("#intevriewcall-form").show();
        var form = $("#interveiwcall_post");
        div.steps({
                headerTag: "h3",
                bodyTag: "fieldset",
                transitionEffect: "slideLeft",
                enableAllSteps: true,
                onStepChanging: function (event, currentIndex, newIndex)
                {
                    // Allways allow previous action even if the current form is not valid!
                    if (currentIndex > newIndex)
                    {
                        return true;
                    }
                    // Forbid next action on "Warning" step if the user is to young
                    if (newIndex === 3)
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
                    if (currentIndex === 2)
                    {
                        div.steps("next");
                    }
                    // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
                    if (currentIndex === 1 && priorIndex === 2)
                    {
                        div.steps("previous");
                    }
                },
                onFinishing: function (event, currentIndex)
                {
                    form.validate().settings.ignore = ":disabled";
                    return form.valid()
                    //return true;
                
                },
                onFinished: function (event, currentIndex)
                {
                        var newurl = $('#interveiwcall_post').data('url');
                        $('#interveiwcall_post').attr('action',newurl);
                        document.getElementById('interveiwcall_post').submit();
                }
             });

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true, todayHighlight: true,
            });
            $('.datetimepicker').datetimepicker({
                format : 'YYYY-MM-DD hh:mm:ss' ,
                icons: {
                up: "fa fa-chevron-circle-up",
                down: "fa fa-chevron-circle-down",
                next: 'fa fa-chevron-circle-right',
                previous: 'fa fa-chevron-circle-left',
                time: 'fa fa-clock-o',
                date: 'far fa-calendar',
            }
            });

            $('.clockpicker').clockpicker({
                donetext: 'Done',
            });

            $('#c_designation').select2();
              
        });
</script>

<script>
$(document).ready(function(){
//     $(".checkactive").each(function() {
//         if($(this).is(":checked") == true){
//             $(this).closest("h4").siblings(".active").find("text,radio,input,select").attr("disabled", false);
//         }else{
//             $(this).closest("h4").siblings(".active").find("text,radio,input,select").attr("disabled", true);
//         }
//     });

//     $(".checkactive").click(function(){
//         $(".checkactive").each(function() {
//         if($(this).is(":checked") == true){
//             $(this).closest("h4").siblings(".active").find("text,radio,input,select").attr("disabled", false);
//         }else{
//             $(this).closest("h4").siblings(".active").find("text,radio,input,select").attr("disabled", true);
//         }
//     });
       
// });
   
    var changeTimer = false;
    $('.checkname, .checkemail, .checkmobile').on("keyup",function(){
        if(changeTimer !== false)clearTimeout(changeTimer)
         changeTimer = setTimeout(function(){
            changeTimer = false;
            var $this =$(this);
            var name  = $(".checkname").val();
            var email = $(".checkemail").val();
            var mobile = $(".checkmobile").val();
             if(name !='' || email !='' || mobile !='')
             {
                $.ajax({
                    method: 'POST',
                    url: '/admin/' + 'getcandidates',
                    data: {name: name, email:email, mobile:mobile},
                    cache : false,
                    success: function (data) {
                    $(".found-box").empty();
                    $(".found-box").removeClass('hide');
                    $(".call-image-size").addClass("hide");
                    $(".found-box").html(data.list);
                        if(data.status ==1){
                        $("#title").text("Records Founded..!");
                        }
                        else{
                        $("#title").text("Records Not Founded..!");
                        }
                    },
                    complete: function () {
                        $this.button('reset')
                    },
                    error: function (xhr) {
                        var msg = 'Failed to delete';
                        if (typeof xhr.responseJSON.message != 'undefined') {
                            msg = msg + ' ' + xhr.responseJSON.message;
                        }
                        swal("Failed to Delete", msg, "error");
                    }
                });
            } 
            else{
                console.log('empty');
                $(".box-thumb").remove();
                $(".found-box").addClass('hide');
                $(".call-image-size").removeClass("hide");
                $("#title").text("Start Call")
            }
        },400);
    });

         $(document).on('click','#candidate', function(){
            swal("Selected", "Candidate Details Selected", "success");
            $this = $(this); 
            $("#c_name").val($this.data('name'));
            $("#c_email").val($this.data('email'));
            $("#c_mobile").val($this.data('mobile'));
            $("#c_per_location").val($this.data('permanent_location'));
            if($this.data('gender') == 'F')
            {
                $('#female').attr("checked", "checked");
            }
            if($this.data('martial_status') == 'M')
            {
                $('#married').attr("checked", "checked");
            }
            $("#c_designation").val($this.data('designation'));
         });
});
</script>
    @endpush;
