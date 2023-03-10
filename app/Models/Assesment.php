<?php

namespace App\Models;

use Carbon\Carbon;

class Assesment extends BaseModel {

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'assessments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'employee_id','from','to'
    ];

    public function evaluations(){
        return $this->hasMany(Evaluation::class,'assessment_id');
    }

    public function employee(){
        return $this->belongsTo(Employee::class);
    }


    public function getPeriodAttribute()
    {
        return Carbon::parse($this->from)->format('M Y').' - '.Carbon::parse($this->to)->format('M Y');
    }
}
