<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecture extends BaseModel
{
    protected $table = 'lectures';

    public $incrementing = false;

    protected $fillable = ['employee_id', 'title', 'description', 'date', 'start','end','status'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class,'employee_lecture','lecture_id','employee_id')->withPivot('status','mark_attendance');
    }

    public function scopeSelf($query)
    {
        return $query->where('employee_id',\Auth::user()->employee->id);
    }

    public function scopeOthers($query)
    {
        $employee_id = \Auth::user()->employee->id;
        return  $query->where('employee_id','!=',$employee_id);
    }

}
