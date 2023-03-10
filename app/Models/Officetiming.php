<?php

namespace App\Models;

use App\Helpers\AppHelper;
use Carbon\Carbon;

class Officetiming extends BaseModel {

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'officetimings';

    protected $fillable = [
        'name', 'slots','employeetype'
    ];

    protected $casts = [
        'slots' => 'array',
    ];

    public function employees() {
        return $this->hasMany(Employee::class);
    }

    public function schedules()
    {
        return $this->morphMany('App\Models\Schedule', 'scheduletypes' , 'model_type' , 'model_id');
    }

    public function getValueAttribute()
    {
        return $this->getValueByDate(Carbon::now()->day);
    }

    public function getValueByDate($day)
    {
        $value = [];

        if($slot_id = @$this->slots[$day]){
            $slot = Officetimingslot::find($slot_id);

            if($slot){
                $value = $slot->value;
            }
        }

        return (object) $value;
    }

    public function getIsAlldayAttribute()
    {
        return count(array_unique($this->slots)) == 1;
    }

    public function getTotalOfficeHoursAttribute()
    {
        if (Setting::isOfficialPermissionToday()) {
            //7:30 hours
            $secs = strtotime($this->value->break_hours)-strtotime("00:00:00");
            return date("H:i",strtotime($this->value->off_perm_work_time)+$secs);
        } else if(Setting::isOfficialHalfdayLeaveToday()) {
            //04:30 hours
            return $this->value->half_day_hours;
        } else {
            //10:30 hours
            return AppHelper::getTimeDiffFormat($this->value->start, $this->value->end);
        }
    }
    
    public function getTotalWorkingHoursAttribute()
    {
        if(Setting::isOfficialPermissionToday()){
            //6:00 hours
            return $this->value->off_perm_work_time;
        }else{
            //9:00 hours
            return AppHelper::getTimeDiffFormat($this->getTotalOfficeHoursAttribute(), $this->value->break_hours,'H:i','H:i', false);
        }
    }
    
    public function getTotalWorkingHoursWithGraceAttribute()
    {
        //8:00 hours
        return AppHelper::getTimeDiffFormat($this->getTotalWorkingHoursAttribute(), $this->value->rp_grace,'H:i','H:i', false);
    }
    
    public function getEmployeeNamesAttribute()
    {
        return $this->employees()->active()->orderBy('name')->get()->implode('name',', ');
    }
    
    public function getCurrentDaySlotAttribute()
    {
        return $this->getSlotByDay(intval(date("d")));
    }

    public function getSlotByDay($day)
    {
        return Officetimingslot::find(@$this->slots[$day]);
    }
}
