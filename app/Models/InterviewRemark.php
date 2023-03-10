<?php

namespace App\Models;

class InterviewRemark extends BaseModel {

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'interview_remarks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'remarks','interview_status_id','created_by','interview_call_id'
    ];
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public function user() 
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function status() 
    {
        return $this->belongsTo(Interviewstatus::class,'interview_status_id');
    }

    


    
}
