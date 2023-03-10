<?php

namespace App\Models;

use App\Helpers\AppHelper;
use Carbon\Carbon;

class ReportItem extends BaseModel {

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'reportitems';

    protected $fillable = [
        'report_id', 'project_id', 'technology_id', 'works', 'start', 'end', 'notes', 'status', 'lock','release_request','order'
    ];

    public static $status = ['P' => 'Progress', 'C' => 'Completed', 'L' => 'Cancelled', 'I' => 'In-completed'];

    protected static function boot()
    {
        parent::boot();

        self::saved(function ($reporitem) {
            self::updateReportHours($reporitem->report);
        });
        self::deleted(function ($reporitem) {
            self::updateReportHours($reporitem->report);
        });
    }
    
    public static function updateReportHours($model) {

        $break_hours = $worked_hours = $permission_hours = $total_hours = '0:00';

        foreach ($model->reportitems as $reportitem) {
            $elapsed_hour = $reportitem->getElapsedTime('H');
            $elapsed_min = $reportitem->getElapsedTime('i');

            //Reference variables for $break_hours and $worked_hours
            if($reportitem->technology->id == Technology::PERMISSION_UUID){
                $hour_variable = &$permission_hours;
            }else{
                $hour_variable = &${$reportitem->technology->exclude ? 'break_hours' : 'worked_hours'};
            }

            $hour_variable = (new Carbon($hour_variable))->addHours($elapsed_hour)->addMinutes($elapsed_min)->toTimeString();
            
            //Total Hours
            $total_hours = (new Carbon($total_hours))->addHours($elapsed_hour)->addMinutes($elapsed_min)->toTimeString();
        }

        $model->workedhours = $worked_hours;
        $model->breakhours = $break_hours;
        $model->permissionhours = $permission_hours;
        $model->totalhours = $total_hours;

        $model->save();
    }

    public function report() {
        return $this->belongsTo(Report::class);
    }
    
    public function project() {
        return $this->belongsTo(Project::class)->withTrashed();
    }
    
    public function technology() {
        return $this->belongsTo(Technology::class)->withTrashed();
    }

    public function getElapsedTime($format = 'H:i')
    {
        try{
            return AppHelper::getTimeDiffFormat($this->start, $this->end, 'H:i:s', $format);
        }catch (\Exception $e){
            return null;
        }
    }

    public function getStatusNameAttribute()
    {
        return $this->status ? self::$status[$this->status] : '';
    }
    
    public function scopeExclude($query, $val = 0)
    {
        return $query->whereHas('technology', function($q) use ($val){ $q->where('exclude', $val); });
    }

    public function scopeLocked($query)
    {
        return $query->where('lock', 1);
    }
}
