<?php

namespace App\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;

class Skill extends BaseModel
{

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'skills';

    use SoftCascadeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'skills'];

    protected $softCascade = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
