<?php

namespace App\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;

class Team extends BaseModel
{

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'teams';

    use SoftCascadeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['lead_id', 'name'];

    protected $softCascade = ['user'];

    public function lead()
    {
        return $this->belongsTo(Employee::class, 'lead_id');
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class);
    }
    
}
