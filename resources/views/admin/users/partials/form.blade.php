<div class="form-body">
    <h3 class="card-title">Person Info</h3>
    <hr>
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('name','Name*',['class'=>'']) }}
                {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('employeetype','Employee type*',['class'=>'']) }}
                <div class="form-check">
                    @foreach ($types as $id=>$employeetype)
                    <label class="custom-control custom-radio">                        
                        {!! Form::radio('employeetype', $id, (@$Employee->employeetype==$id), ['class' => 'custom-control-input'] ); !!} {{$employeetype}}                                               
                        <span class="custom-control-indicator"></span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('gender','Gender*',['class'=>'']) }}
                <div class="form-check">
                    @foreach ($gender as $id=>$gen)
                    <label class="custom-control custom-radio">                        
                        {!! Form::radio('gender', $id, (@$Employee->gender==$id), ['class' => 'custom-control-input'] ); !!} {{$gen}}                                               
                        <span class="custom-control-indicator"></span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('dob','Date of Birth*',['class'=>'']) }}
                {{ Form::text('dob', @$Employee->dob, ['class' => 'form-control datepicker']) }}
            </div>
        </div>        
    </div>    

    <div class="row p-t-20">  
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('phone','Phone*',['class'=>'']) }}
                {{ Form::text('phone', @$Employee->phone, ['class' => 'form-control']) }}
            </div>
        </div>        
    </div>    
    <h3 class="box-title m-t-40">Address</h3>
    <hr>
    <div class="row">
        <div class="col-md-12 ">
            <div class="form-group">
                {{ Form::label('address','Address*') }}
                {{ Form::text('address', @$Employee->address, ['class' => 'form-control']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('city','City*') }}
                {{ Form::text('city', @$Employee->city, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('state','State*') }}
                {{ Form::text('state', @$Employee->state, ['class' => 'form-control']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('country','Country*',['class'=>'']) }}
                {{ Form::text('country', @$Employee->country, ['class' => 'form-control']) }}
            </div>
        </div>
    </div>

    <h3 class="box-title m-t-40">Other Details</h3>
    <hr>
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('fathername','Father Name*',['class'=>'']) }}
                {{ Form::text('fathername', @$Employee->fathername, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('mothername','Mother Name',['class'=>'']) }}
                {{ Form::text('mothername', @$Employee->mothername, ['class' => 'form-control']) }}
            </div>
        </div>        
    </div>
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('spousename','Spouse Name',['class'=>'']) }}
                {{ Form::text('spousename', @$Employee->spousename, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('skype','Skype*',['class'=>'']) }}
                {{ Form::text('skype', @$Employee->skype, ['class' => 'form-control']) }}
            </div>
        </div>
    </div>        
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('passport','Passport',['class'=>'']) }}
                {{ Form::text('passport', @$Employee->passport, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('panno','Pan Number',['class'=>'']) }}
                {{ Form::text('panno', @$Employee->panno, ['class' => 'form-control']) }}
            </div>
        </div>        
    </div>
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('aadhaarno','Aadhaar Card Number*',['class'=>'']) }}
                {{ Form::text('aadhaarno', @$Employee->aadhaarno, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('emergencynumber','Emergency Contact Number',['class'=>'']) }}
                {{ Form::text('emergencynumber', @$Employee->emergencynumber, ['class' => 'form-control']) }}
            </div>
        </div>
    </div>
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('joindate','Joined on*',['class'=>'']) }}
                {{ Form::text('joindate', @$Employee->joindate, ['class' => 'form-control datepicker']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('photo','Photo',['class'=>'']) }}<br/>
                {{ Form::file('photo') }}
            </div>
        </div>
    </div>
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('designationname','Designation*',['class'=>'']) }}
                {{ Form::text('designationname', @$Designation->designationname, ['class' => 'form-control','id'=>'search_text']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('active','Active',['class'=>'']) }} <br/>
                <div class="switch">
                    <label>
                        {{ Form::hidden('active', 0) }}
                        {{ Form::checkbox('active', 1, @$Employee->active) }}<span class="lever switch-col-blue"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <h3 class="box-title m-t-40">Login Details</h3>
    <hr>
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('email','Email*') }}
                {{ Form::text('email', @$Employee->email, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('password','Password*') }}
                {{ Form::password('password',['class'=>'form-control']) }}
            </div>
        </div>
    </div>
</div>
    <div class="form-actions">
        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
    </div>

@push('scripts')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/multiselect/js/jquery.multi-select.js') }}"></script>
    
<!--Auto complete Search-->
    <script type="text/javascript">
        $(document).ready(function () {
            src = "{{ route('searchajax') }}";
            $("#search_text").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: src,
                        dataType: "json",
                        data: {
                            term: request.term
                        },
                        success: function (data) {
                            response(data);

                        }
                    });
                },
                minLength: 3,

            });
        });
    </script>
    @endpush