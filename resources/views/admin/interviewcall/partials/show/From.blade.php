
<div class="map-box">
<div class="row">
    <div class="col-md-12">
            <div class="form-group">
            <input type="hidden" name="interview_call_id" value= "{{ @$model->id}}" >
                <label class="control-label">Select Status</label>
                <div class="form-group col-md-12">
                    {{ Form::Select('interview_status_id', $status,old('interview_status_id'), ['class' => 'form-control ','autocomplete'=>"off",'placeholder'=>'Full name','placeholder'=>'Select Status']) }}
                </div>

                <label class="control-label"> Schedule Date</label>
                <div class="form-group col-md-12">
                    {{ Form::text('schedule_date',$model->schedule_date, ['class' => 'form-control datepicker', 'id' => 'datetime-format','autocomplete'=>"off",'placeholder'=>'Select Date']) }}
                </div>
                
                <div class="form-actions"> 
                    <center><button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button></center>                                                           
                </div>
            </div>    
    </div>                                    
</div>
</div>   
<div><hr></div>

<div class="map-box">
        <div class="row">
            <div class="col-md-12">
                    <div class="form-group">
                          <div class="form-group col-md-12">
                               <label class="control-label">Comment </label>
                               {{ Form::textarea('remarks','', ['class' => 'form-control','autocomplete'=>"off",'rows'=>'5']) }}
                           </div>
                             <div class="form-actions">                                                            
                                 <center><button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Submit</button></center>
                             </div>
                    </div>
            </div>
       </div>
 </div>


