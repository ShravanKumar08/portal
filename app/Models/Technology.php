<?php

namespace App\Models;

class Technology extends BaseModel {

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'technologies';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'exclude', 'active'];

    const BREAK_UUID = 'BREAKBREAK-1234-0000-1234-BREAKBREAK';

    const PERMISSION_UUID = 'PERMISSION-1234-0000-1234-PERMISSION';
}
