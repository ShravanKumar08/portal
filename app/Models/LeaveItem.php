<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class LeaveItem extends BaseModel {

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'leaveitems';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['leave_id','date','days','leavetype_id'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('permanent', function (Builder $builder) {
            $builder->whereHas('leave', function ($q){
                $q->permanent();
            });
        });
    }


    public function leave() {
        return $this->belongsTo(Leave::class);
    }

    public function scopePending($query)
    {
        return $query->where('leavetype_id', null)->whereHas('leave', function ($q){
            $q->where('status', 'P');
        });
    }

    public function compensates() {
        return $this->morphToMany(Compensation::class, 'compensates')->withPivot('days');
    }

    public function scopeCasual($query)
    {
        return $query->where('leavetype_id', Leavetype::getCasual()->id);
    }

    public function scopePaid($query)
    {
        return $query->where('leavetype_id', Leavetype::getPaid()->id);
    }

    public function scopeCompensation($query)
    {
        return $query->where('leavetype_id', Leavetype::getCompensation()->id);
    }
    
    public function scopeDeclined($query)
    {
        return $query->whereHas('leave', function($q){
            $q->declined();
        });
    }
    
    public function leavetype() {
        return $this->belongsTo(Leavetype::class);
    }

    public function getLeavedaysAttribute()
    {
        return $this->date." ({$this->pivot->days})";
    }
}
