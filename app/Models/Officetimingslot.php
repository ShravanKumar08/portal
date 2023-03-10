<?php

namespace App\Models;

use App\Helpers\AppHelper;
use Carbon\Carbon;

class Officetimingslot extends BaseModel {

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'officetimingslots';

    protected $fillable = [
        'name', 'value', 'bg_color', 'text_color','report_sort'
    ];

    public function officetimings() {
        return Officetiming::where('slots','like','%'.$this->id.'%')->get();
    }
    
    protected $casts = [
        'value' => 'array',
    ];

    public static $timings = [
        'start' => ['time' => 'true', 'text' => 'Office Start Time'],
        'end' => ['time' => 'true', 'text' => 'Office End Time'],
        'lt_start' => ['time' => 'true', 'text' => 'Late Entry Record Time'],
        'lt_perm' => ['time' => 'true', 'text' => 'Late Entry Permission Start Time'],
//        'lt_perm_max' => ['time' => 'true', 'text' => 'Late Entry Permission Max Time'],
        'lt_half_day_excuse' => ['time' => 'true', 'text' => 'Late Entry Halfday Start Time'],
//        'lt_half_day_grace' => ['time' => 'false', 'text' => 'Late Entry Halfday Grace Minutes'],
        'lt_end' => ['time' => 'true', 'text' => "Late Entry User's End Time"],
        'break_hours' => ['time' => 'false', 'text' => 'Total Break Hours (Including Lunch & Tea-break)'],
        'minimum_permission_hours' => ['time' => 'false', 'text' => 'Minimum permission hours'],
        'permission_hours' => ['time' => 'false', 'text' => 'Maximum Permission Hours(Evening)'],
        'morning_permission_hours' => ['time' => 'false', 'text' => 'Maximum Permission Hours(Morning)'],
        'half_day_hours' => ['time' => 'false', 'text' => 'Half day Hours'],
        'rp_grace' => ['time' => 'false', 'text' => 'Official Perm. Grace Mins (2nd and 4th Sat)'],
        'off_perm_work_time' => ['time' => 'false', 'text' => 'Official permission working hours'],
    ];

    public static $defaultSlots = [
        "start" => "09:30",
        "end" => "19:10",
        "lt_start" => "10:05",
        "lt_perm" => "10:30",
//        "lt_perm_max" => "11:30",
        "lt_half_day_excuse" => "11:45",
//        "lt_half_day_grace" => "00:15",
        "lt_end" => "19:40",
        "break_hours" => "01:30",
        "permission_hours" => "02:00",
        "half_day_hours" => "04:00",
        "rp_grace" => "00:10",
        "off_perm_work_time" => "06:00",
        "minimum_permission_hours" => "00:30",
    ];
}
