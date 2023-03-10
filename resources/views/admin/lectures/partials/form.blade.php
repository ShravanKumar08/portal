<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="form-body">
                    {{ Form::open(['route' => 'lectures.store', 'class' => 'form-horizontal', 'id' => 'lecture-form']) }}
                    {{ Form::hidden('employee_id', $employee_id) }}
                        <div class="row p-t-20">
                            <div class="col-md-6">
                                {{ Form::label('title','Title*',['class'=>'']) }}
                                {{ Form::text('title', (@$lecture->title), ['class' => 'form-control']) }}
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('date','Date*',['class'=>'']) }}
                                    {{ Form::text('date', (@$lecture->date), ['class' => 'form-control datepicker', 'autocomplete' => 'off']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row p-t-20"> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('start','Start Time*',['class'=>'']) }}
                                    {{ Form::text('start',(@$lecture->start), ['class' => 'form-control clockpicker', 'autocomplete' => 'off','data-autoclose'=>"true"]) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('end', 'End Time', ['class' => '']) }}
                                    {{ Form::text('end', (@$lecture->end), ['class' => 'form-control clockpicker', 'autocomplete' => 'off','data-autoclose'=>"true"]) }}
                                </div>
                            </div>
                        </div>
                        <div class="row p-t-20">
                            <div class="col-md-6">
                                {{ Form::label('description','Description*',['class'=>'']) }}
                                {{ Form::textarea('description', (@$lecture->description), ['class' => 'form-control','rows' => 4]) }}
                            </div>
                        </div>
                        @if(empty($id))
                        <div class="row p-t-20">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h5 class="box-title">Select Designation</h5>
                                    {{ Form::select('designation[]', @$designation, old('designation'), ['class' => 'form-control searchablemultiselect designation', 'multiple' => true]) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h5 class="box-title">Select Employees</h5>
                                    {{ Form::select('employees[]', @$employeeList, old('employees'), ['class' => 'form-control searchablemultiselect employees', 'multiple' => true]) }}
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                            <button type="reset" class="btn btn-inverse">Reset</button>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true, todayHighlight: true,
        });
        $('.clockpicker').clockpicker({
            donetext: 'Done',
        });
        $('.searchablemultiselect').each(function () {
            $(this).searchableMultiselect();
        });

        $('body').on('submit', '#lecture-form', function (e) {
            e.preventDefault();
            let $this = $(this);
            let data = $this.serializeArray();

            $.ajax({
                method: $this.attr('method'),
                url: $this.attr('action'),
                data: data ,
                beforeSend: function () {
                    $this.find(':submit').buttonLoading();
                },
                success: function (data) {
                    window.location.replace("/admin/lectures");
                },
                error: function (jqXhr) {
                    swalError(jqXhr);
                }
            });
        });
    });

    function afterMSSelectCallback($this, values)
    {
        if($this[0].$element.hasClass('designation')){
            $.ajax({
                method: 'get',
                url: "{{route('lectures.getemployees')}}",
                data: {
                    'designation_id' : $('.designation').val(),
                } ,
                success: function (response) {
                    $.each(response,function(key,value){
                        $('.employees').find('option[value='+key+']').attr("selected",true);
                        $('.employees').multiSelect("refresh");
                    });
                    
                }
            });
        }
    }

    function afterMSDeselectCallback($this, values)
    {
        if($this[0].$element.hasClass('designation')){
            $.ajax({
                method: 'get',
                url: "{{route('lectures.getemployees')}}",
                data: {
                    'designation_id' : values,
                } ,
                success: function (response) {
                    $.each(response,function(key,value){
                        $('.employees').find('option[value='+key+']').attr("selected",false);
                        $('.employees').multiSelect("deselect", key);
                    });
                }
            });
        }
    }
</script>