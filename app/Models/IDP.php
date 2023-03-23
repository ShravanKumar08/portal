<?php

namespace App\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;

class IDP extends BaseModel
{

    public $timestamps      = true;
    public $incrementing    = false;
    protected $table        = 'idps';

    use SoftCascadeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['employee_id', 'manager_id', 'mentor_id', 'cv', 'personal_motivation',
        'current_job_requirements', 'goals', 'assignments', 'strengths', 'development_needs', 'development_action_plan'];

    protected $casts = [
        'personal_motivation'       => 'array',
        'current_job_requirements'  => 'array',
        'goals'                     => 'array',
        'assignments'               => 'array',
        'strengths'                 => 'array',
        'development_needs'         => 'array',
        'development_action_plan'   => 'array'
    ];

    protected $softCascade = ['user'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function mentor()
    {
        return $this->belongsTo(Employee::class, 'mentor_id');
    }
    
}
