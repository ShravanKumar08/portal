<?php

namespace App\Models;


class Evaluation extends BaseModel {

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'evaluations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['assessment_id', 'evaluator_id', 'evaluation','status'];

    public function assessment(){
        return $this->belongsTo(Assesment::class)->withoutGlobalScope('App\Scopes\EmployeeScope');
    }

    public function employee(){
        return $this->belongsTo(Employee::class ,'evaluator_id');
    }

    public function getStatusnameAttribute()
    {
        return $this->status == 0 ? 'Pending' : 'Completed';
    }

    public function scopeSelf($query)
    {
        return $query->whereHas('assessment', function($q){
            $q->where('employee_id', \Auth::user()->employee->id);
        })->where('evaluator_id', \Auth::user()->employee->id);
    }

    public function scopeOthers($query)
    {
        return $query->whereHas('assessment', function($q){
            $q->where('employee_id', '!=', \Auth::user()->employee->id);
        })->where('evaluator_id', \Auth::user()->employee->id);
    }
}
