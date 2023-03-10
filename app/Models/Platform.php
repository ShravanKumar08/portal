<?php

namespace App\Models;

class Platform extends BaseModel
{
    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'platforms';
    protected $fillable = ['name'];
    public function questions(){
        return $this->belongsToMany('App\Models\Question');
    }
}
