<?php

namespace App\Models;

use App\Mail\ReleaselockMail;
use App\Mail\ReportsMail;
use App\Mail\ReportsNotification;
use App\Mail\ReportsStatusNotification;
use Carbon\Carbon;
use App\Helpers\AppHelper;
use App\Scopes\EmployeeScope;
use Illuminate\Support\Facades\Mail;

class Report extends BaseModel
{
    public $timestamps = true;
    public $incrementing = false;
    protected $table = 'reports';

    const MINIMUM_BREAK_MINUTES = 3;

    protected $fillable = [
        'employee_id', 'date', 'start', 'end', 'workedhours', 'breakhours', 'permissionhours' , 'totalhours', 'status', 'manual_request_time'
    ];

    public static $status = ['P' => 'Pending', 'D' => 'Declined', 'R' => 'In-progress', 'A' => 'Approved' , 'S' => 'Sent', 'N' => 'No-Report'];

    public function reportitems()
    {
        return $this->hasMany(ReportItem::class); //->orderBy('start', 'ASC');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function firstreportitem()
    {
        return $this->reportitems()->oldest('start')->first();
    }

    public function lastreportitem()
    {
        return $this->reportitems()->oldest('start')->get()->last();
    }

    public function getStatusnameAttribute()
    {
        return @self::$status[$this->status];
    }

    public function getEndTimeAttribute()
    {
        if ($last = $this->lastreportitem()) {
            return $this->date . ' ' . $last->end;
        }

        return null;
    }

    public function scopeProgress($query)
    {
        return $query->where('status', 'R');
    }
    
    public function scopeNoreport($query)
    {
        return $query->where('status', 'P')
            ->select([
                'reports.*',\DB::raw('(CASE
                WHEN status = "P" THEN 0
                ELSE 1
            END) as status_number'),
                \DB::raw('(select count(*) from entries a where a.employee_id = reports.employee_id and a.date = reports.date and a.deleted_at is null) as cnt'),
                \DB::raw('(select count(*) from leaves a where a.employee_id = reports.employee_id and a.start <= reports.date and a.end >= reports.date and a.deleted_at is null and a.status = "A") as leave_cnt'),
                \DB::raw('(select count(*) from reportitems a where a.report_id = reports.id) as reportitem_cnt'),
            ])
            ->havingRaw('cnt = 0 and leave_cnt = 0 and reportitem_cnt = 0 and reports.reason is null');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'P')
        ->select([
            'reports.*',\DB::raw('(CASE
                WHEN status = "P" THEN 0
                ELSE 1
            END) as status_number'),
            \DB::raw('(select count(*) from entries a where a.employee_id = reports.employee_id and a.date = reports.date and a.deleted_at is null) as cnt'),
            \DB::raw('(select count(*) from reportitems a where a.report_id = reports.id) as reportitem_cnt'),
            ])
        ->havingRaw('cnt > 0 OR reportitem_cnt > 0 or reports.reason is not null');
    }

    public function scopePendingNotToday($query)
    {
        return $query->pending()->where('date' , '!=' , date('Y-m-d'));
    }

    public function scopeReleaselock($query, $lock = 1)
    {
        $reportids = ReportItem::where('release_request', $lock)->pluck("report_id")->toArray();
        $query->whereIn('id', $reportids);
    }
    
    public function getHasReleaseRequestAttribute()
    {
        $reportitems = $this->reportitems->filter(function($value, $key) {
            return $value->release_request == 1;
        });
        
        return $reportitems->isEmpty() == false;
    }

    public function getActualEndTimeAttribute()
    { 
        if ($this->end) {
            return $this->date . ' ' . $this->end;
        }

        $endtime = null;
        $employee = $this->employee;
        $office_timing = $employee->officetiming;
        
        if ($timings = @$office_timing->value) { 
            //Has Leave

            $leave = $employee->leaves()->withoutGlobalScope('permanent')->whereHas('leaveitems', function ($q) {
                $q->withoutGlobalScope('permanent')->where('date', $this->date);
            })->approved()->exists();
            
            if (!$leave) {
               
                $official_permission_today = Setting::isOfficialPermissionToday();
                
                if (!$endtime) { 

                    //Has Permission
                    $permission = $employee->userpermissions()->where('date', $this->date)->notDeclined()->first();
                    
                    if ($permission) {

                        $now = Carbon::now();
                        
                        //permission used
                        if ($now->timestamp > strtotime($permission->end)) {                             
                            if($official_permission_today){
                                 $endtime = $this->_calculate_permission_late_end_time($timings, $office_timing);
                            }else{  
                                $start = Carbon::createFromTimeString($this->start);
                                $tot_hours = Carbon::createFromFormat('H:i', $office_timing->totalOfficeHours);                                        
                                $endtime = $start->addHours($tot_hours->hour)->addMinutes($tot_hours->minute)->toTimeString();                                
                           
                                //Has permission at office start timing
                                $permission_start = Carbon::parse($permission->start)->format("H:i");                                                                                     
                                if($permission_start == $timings->start){ 
                                    $endtime = Carbon::createFromFormat('H:i', $timings->end)->toTimeString();                                    
                                }                                                                                           
                            }                           
                        } else {
                            $endtime = $permission->start;
                        }
                    }
                }

                if (!$endtime) { 

                    //Has Late entry
                    $late_entry = $employee->late_entries()->whereDate('date', $this->date)->first();
                   
                    if ($late_entry) { 

                        if($official_permission_today){
                            $endtime = $this->_calculate_permission_late_end_time($timings, $office_timing, true);
                        }else if ($late_entry->status == 'A'){
                            $endtime = Carbon::createFromFormat('H:i', $timings->lt_end)->toTimeString();
                        }else if ($late_entry->status == 'U'){
                            $endtime = Carbon::createFromFormat('H:i', $timings->end)->toTimeString();
                        }                       
                    }
                }

                if (!$endtime) {
                    //Calculate End time
                    $start = Carbon::createFromTimeString($this->start);
                    $tot_hours = Carbon::createFromFormat('H:i', $office_timing->totalOfficeHours);

                    $endtime = $start->addHours($tot_hours->hour)->addMinutes($tot_hours->minute)->toTimeString();
                }
            }
        }
       
        return $endtime ? ($this->date . ' ' . $endtime) : null;
    }

    public function getFinalEndTimeAttribute()
    {
        $actual_endtime = Carbon::parse($this->actual_endtime);
        $actual_break = Carbon::createFromFormat('H:i', $this->employee->officetiming->value->break_hours);
        $used_break = Carbon::parse($this->org_breakhours);
        
        if($used_break > $actual_break){
            $actual_endtime = $actual_endtime->addMinutes($this->extended_break);
        }

        if(Setting::isOfficialHalfdayLeaveToday()){
            $breakminutes = $used_break->format('i');
            $actual_endtime = $actual_endtime->addMinutes($breakminutes);
        }
       
        return $actual_endtime;
    }

    public function getExtendedBreakAttribute()
    {
        $actual_break = Carbon::createFromFormat('H:i', $this->employee->officetiming->value->break_hours);
        $used_break = Carbon::parse($this->org_breakhours);
        
        if($used_break > $actual_break){
            $extended_break = $used_break->diffInMinutes($actual_break);
        } else {
            $extended_break = false;
        }
       
        return $extended_break;
    }
    
    private function _calculate_permission_late_end_time($timings, $office_timing, $late_entry = false) {
        $secs = strtotime($office_timing->totalOfficeHours)-strtotime("00:00:00");
        if($late_entry) {
            $diff_time = AppHelper::getTimeDiffFormat($timings->end, $timings->lt_end);
            $secs += strtotime($diff_time)-strtotime("00:00:00");
        }

        return date("H:i:s",strtotime($timings->start) + $secs);
    }

    public function mailTo()
    {
        $mail = $this->getMailObject();

        if ($mail) {
            $mail->queue(new ReportsMail($this));
        }
    }

    public function pendingNotificationMail()
    {
        $mail = $this->getMailObject();

        if ($mail) {
            $mail->queue(new ReportsNotification($this));
        }
    }
    
    public function remarksMail()
    {
        $mail = $this->getMailObject();

        if($mail){
            $mail->queue(new ReportsStatusNotification($this));
        }
    }
    
    public function releaseLockMail()
    {
        $mail = $this->getMailObject();

        if ($mail) {
            $mail->queue(new ReleaselockMail($this));
        }
    }

    protected function getMailObject()
    {
        $name = 'REPORT_NOTIFICATION_EMAIL';

        $usersetting = UserSettings::where('user_id', $this->employee->user_id)->where('name', $name)->first();

        if($usersetting){
            $emails = AppHelper::getSettingEmailsArray(json_decode($usersetting->value, true));
            return AppHelper::getMailObject($emails);
        }

        return Setting::getMailObject('REPORT_NOTIFICATION_EMAIL');
    }

    public function deleteMinimumBreaks()
    {
        $this->reportitems()->where('technology_id', Technology::BREAK_UUID)
            ->whereRaw('TIMESTAMPDIFF(MINUTE , start, end) <= ' . self::MINIMUM_BREAK_MINUTES)
            ->forceDelete();
    }
    
    public function getProjectNamesAttribute()
    {
        return $this->reportitems()->whereNotNull('project_id')->groupBy('project_id')->get()->implode('project.name', ', ');
    }

    public function getOrgBreakhoursAttribute()
    {
        $break_minutes = 0;

        $break_report = $this->reportitems()->where('technology_id', [Technology::BREAK_UUID])->selectRaw('SUM(TIMESTAMPDIFF(MINUTE, start, end)) as diff')->first();

        if($break_report){
            $break_minutes = $break_report->diff;
        }

        return Carbon::now()->startOfDay()->addMinutes($break_minutes)->format('H:i:s');
    }

    public static function getTodayReport($employee, $date = null)
    {
        $query = self::query()->where('employee_id', $employee->id);

        $officetimings = $employee->officetiming->value;

        if ($officetimings->start > $officetimings->end){
            $datas[] = Carbon::yesterday()->toDateString();
            $datas[] = Carbon::now()->toDateString();
            $datas[] = Carbon::tomorrow()->toDateString();
            $query->whereIn('date', $datas)->where('status', 'R')->orderBy('date', 'ASC');
        } else {
            $query->where('date', $date ?: Carbon::now()->toDateString());
        }

        return $query->first();
    }

    public function my_report_items()
    {
        $reportitems = @$this->reportitems()->oldest('end')->get();

        if(@$this->employee->officetiming->currentdayslot->report_sort == 1)
        {
            $reportitems = $reportitems->sortBy('order');
        }

        // $officetimings =  $this->employee->officetiming->value;

        // if ($officetimings->start > $officetimings->end){           
        //     foreach ($reportitems as $k => $reportitem) { 
        //         if($reportitem->end < $officetimings->start){ 
        //             $reportitem->end_time = Carbon::parse($this->date)->addDay(1)->toDateString().' '.$reportitem->end;
        //         }else{
        //             $reportitem->end_time = $this->date.' '.$reportitem->end;
        //         }
        //     }

        //     $items = $reportitems->sortBy(function ($obj , $key ) {
        //         return Carbon::parse($obj->end_time)->getTimestamp();
        //     });
         
        //     $reportitems = $items->values();
        // }
        
        return collect($reportitems->values());
    }
}
