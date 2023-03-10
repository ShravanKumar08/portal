<?php

namespace App\Models;

class LateEntry extends BaseModel {

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'late_entries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['date', 'employee_id','remarks'];

    public static $status = ['A' => 'Approved', 'D' => 'Declined & Extended Hours', 'P' => 'Pending', 'E' => 'Approved & Extended Hours', 'U' => 'Declined'];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function getStatusnameAttribute()
    {
        return @self::$status[$this->status];
    }

    public function scopeApproved($query)
    {
        return $query->whereIn('status', ['A', 'E']);
    }

    public function scopeNotDeclined($query)
    {
        return $query->whereNotIn('status', ['D', 'U']);
    }
}
