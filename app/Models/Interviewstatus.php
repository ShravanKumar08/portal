<?php

namespace App\Models;

class Interviewstatus extends BaseModel {

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'interview_status';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name','active'   
    ];
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


    
}
