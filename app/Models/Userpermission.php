<?php

namespace App\Models;

use App\Helpers\AppHelper;
use App\Mail\MailNotification;
use App\Mail\PermissionRequest;
use App\Rules\MaxIntervalPeriod;
use App\Rules\MinIntervalPeriod;
use App\Rules\NotBetweenDates;
use App\Rules\PermissionValidator;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use OwenIt\Auditing\Contracts\Auditable;

class Userpermission extends BaseModel implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'userpermissions';

    protected $fillable = [
        'id', 'employee_id', 'date', 'start', 'end', 'reason', 'remarks', 'status','compensate','at_training_period',
    ];
    public static $status = ['A' => 'Approved', 'D' => 'Declined & Extended Hours', 'U' => 'Declined', 'P' => 'Pending'];

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            $report = Report::where('employee_id' , $model->employee_id)->where('date', $model->date)->first();
            
            if($report){
                $report->reportitems()->where('technology_id', Technology::PERMISSION_UUID)->forceDelete();
            }
        });
    }

    public function scopeTrainee($query)
    {
        return $query->where('at_training_period', 1);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getStarttimeAttribute()
    {
        return $this->date . ' ' . $this->start;
    }

    public function getEndtimeAttribute()
    {
        return $this->date . ' ' . $this->end;
    }

    public function getStatusnameAttribute()
    {
        return @self::$status[$this->status];
    }

    public function getElapsedAttribute()
    {
        return AppHelper::getTimeDiffFormat($this->start, $this->end, 'H:i:s');
    }

    public function compensates() {
        return $this->morphToMany(Compensation::class, 'compensates')->withPivot('days');
    }

    public static function getRules($request, $id = null)
    {
        $Employee = Employee::find($request->employee_id);
        $date = Carbon::parse($request->date);
        $officeTimings = $Employee->officetiming->getValueByDate($date->day);
        $end = Carbon::parse($request->end)->format('H:i');

        if(\Auth::user()->hasRole('admin')){
            $max_perm_hours = $officeTimings->permission_hours.':00';
        }else{
            $max_perm_hours = $officeTimings->morning_permission_hours.':00';

            $Report = Report::where(['employee_id' => $request->employee_id, 'date' => $request->date])->first();

            if($Report){
                $endTime = Carbon::parse($Report->actualEndTime)->format("H:i");
            }else{
                $endTime = $officeTimings->end;
            }

            if($endTime <= $end) {
                $max_perm_hours = $officeTimings->permission_hours.':00';
            }
        }

        return [
            'employee_id' => 'required',
            'date' => [
                'required',
                new NotBetweenDates('permission', $request->employee_id, $id),
                \Auth::user()->hasRole('admin') ? '' : new PermissionValidator($request->employee_id, $request->start, $request->end, $officeTimings)
            ],
            'start' => ['required', new MaxIntervalPeriod($request->end, $max_perm_hours),new MinIntervalPeriod($request->end, $officeTimings->minimum_permission_hours.':00')],
            'end' => 'required',
            'reason' => 'required',
            'status' => 'required',
        ];
    }

    public function saveForm($request)
    {
        $data = $request->except(['_token']);
        $this->fill($data);
        $this->save();
        $data['employee_name'] = $this->employee->name;
        $this->mailTo();
    }

    public function processCompensate()
    {
        if($this->compensate){
            $compensation = Compensation::where('employee_id', $this->employee->id)->where('type', 'P')->available()->first();
            if($compensation){
                $this->compensates()->attach($this->id, ['compensation_id' => $compensation->id, 'days' => 1]);
            }
        }
    }

    public function mailTo()
    {
        $mail = Setting::getMailObject('PERMISSION_NOTIFICATION_MAIL');

        if ($mail) {
            $mail->queue(new PermissionRequest($this));
        }
    }

    public function remarksMail()
    {
        $mail = Setting::getMailObject('PERMISSION_NOTIFICATION_MAIL');

        if ($mail) {
            $mail->queue(new \App\Mail\PermissionNotification($this));
        }
    }

    public function transformAudit(array $data): array
    {
        if (Arr::has($data, 'new_values.employee_id')) {
            $data['new_values']['employee_id'] = $this->employee->name;
        }

        if (Arr::has($data, 'old_values.employee_id')) {
            $data['old_values']['employee_id'] = $this->employee->name;
        }

        if (Arr::has($data, 'new_values.status')) {
            $data['new_values']['status'] = $this->statusname;
        }

        if (Arr::has($data, 'old_values.status')) {
            $data['old_values']['status'] = $this->statusname;
        }
        return $data;
    }

    public function scopeNotDeclined($query)
    {
        return $query->whereNotIn('status', ['D', 'U']);
    }
}
