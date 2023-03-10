<?php

namespace App\Models;

class Holiday extends BaseModel {

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'holidays';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','date'];

    public static function getYears()
    {
        return Holiday::selectRaw('distinct year(date) as years')->latest('years')->pluck('years', 'years')->toArray();
    }
    
}
