<?php

namespace App\Models;

class Grade extends BaseModel
{
    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'grades';
    protected $fillable = ['name','level'];
}
