<?php

namespace App\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;

class TeamMember extends BaseModel
{

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'team_members';

    use SoftCascadeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['team_id', 'teammate_id'];

    protected $softCascade = ['user'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'teammate_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    
}
