<?php

namespace App\Models;

class Leavetype extends BaseModel{

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'leavetypes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'days', 'is_paid'];

    public function leaveitems()
    {
        return $this->hasMany(LeaveItem::class);
    }

    public static function getCasual()
    {
        return self::where('name', 'casual')->first();
    }

    public static function getPaid()
    {
        return self::where('name', 'LOP')->first();
    }

    public static function getCompensation()
    {
        return self::where('name', 'compensation')->first();
    }
}
