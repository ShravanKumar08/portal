<?php

namespace App\Rules;

use App\Helpers\AppHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use App\Models\Technology;
use App\Models\Setting;

class ReportValidator implements Rule
{
    protected $report;

    protected $reportitems;

    protected $employee;

    protected $error_message;

    protected $hasPermission = false;
    
    protected $now;
   
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($report)
    {        
        $this->report = $report;
        $this->reportitems = $this->report->reportitems()->oldest('start')->get();
//        $this->reportitems =  $this->report->reportitems()->orderByRaw('FIELD(start, "'.$report->start.'" ) desc')->get();

        $this->employee = $this->report->employee;
        $this->now = Carbon::now();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        
        if(!$this->_validate_report_approved()){
            return false;
        }

        if(!$this->_validate_rows_mismatch()){
            return false;
        }

        if(!$this->_validate_permission_added()){
            return false;
        }
        
        if(!$this->_validate_report_start_time()){
            return false;
        }

        if(!$this->_validate_report_endtime()){
            return false;
        }
        
        if(!(request()->has("novalidate"))) {  
            
          if(!$this->_validate_worked_hours()){
                return false;
            }
        }

       
        
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error_message;
    }

    /**
     * Report should not pending
     * @return bool
     */
    private function _validate_report_approved()
    {
        $return = true;

        if ($this->report->status == 'P') {
            $this->error_message = 'Your report is not approved yet';
            $return = false;
        }

        return $return;
    }

    /**
     * Report start and end time mismatch between rows
     * @return bool
     */
    private function _validate_rows_mismatch()
    {
        $return = true;

        $reportitems = $this->report->my_report_items();

        for ($i = 1; $i < $reportitems->count(); $i++) {
            if ($reportitems[$i]->start != $reportitems[$i - 1]->end) {
                $this->error_message = 'Start and End Time Mismatch in ' . $i . ' and ' . ($i + 1) . ' rows';
                $return = false;
            }
        }

        return $return;
    }

    /**
     * Validate Permission added in the report.
     * @return bool
     */
    private function _validate_permission_added()
    {
        $return = true;

        $permission = $this->employee->userpermissions()->where('date', $this->report->date)->notDeclined()->first();

        if ($permission) {
            $this->hasPermission = true;

            $permission_added = $this->report->reportitems()->where('start', $permission->start)
                ->where('end', Carbon::parse($permission->end)->format('H:i'))
                ->exclude(1)
                ->exists();

            if (!$permission_added) {
                $this->error_message = "Add your permission in report. Start: {$permission->start} End: {$permission->end}";
                $return = false;
            }
        }else{
            $permission_added = $this->report->reportitems()->where('technology_id', Technology::PERMISSION_UUID)
                ->exclude(1)
                ->exists();
            
            if($permission_added){
                $this->error_message = "Permission added in your report item. But you have not applied permission for this day!!";
                $return = false;   
            }
           
        }

        return $return;
    }
    
    private function _validate_worked_hours()
    {
        $return = true;

        $timings = $this->employee->officetiming;
        $official_worked_hours = $timings->totalWorkingHours;
        $greater_twelvehours ='12:00';
        if ($this->report->workedhours < $official_worked_hours) {
            $this->error_message = 'Your worked hours is less than '.$official_worked_hours.' hours !!';
            $return = false;
        }

        if($this->report->workedhours > $greater_twelvehours){ 
            $this->error_message = 'Your worked hours is greater than '.$greater_twelvehours.' hours !!';
            $return = false;
        }

        return $return;
    }

    /**
     * Validate report end time with current time for today report
     * @return bool
     */
    private function _validate_report_endtime()
    {
        $return = true;   

        if ($this->now->toDateString() == $this->report->date) {
            $last_reportitem = $this->reportitems->last(); 
            $permission = $this->employee->userpermissions()->where('date', $this->report->date)->notDeclined()->first();
            $leave = $this->employee->leaves()->where('start', '>=', $this->report->date)
                ->where('end', '<=', $this->report->date)
                ->notDeclined()
                ->first();
                //If report has actual end time
                if($actual_end_time = @$this->report->finalEndTime){
                    $timings = $this->employee->officetiming;
                    $officalPermission = Setting::isOfficialPermissionToday();
                    // $end_time_with_grace = Carbon::parse($actual_end_time)->toDateString(). ' '.AppHelper::getTimeDiffFormat(Carbon::parse($actual_end_time)->format('H:i'), $timings->value->rp_grace);                    

                    if(($permission == null || $last_reportitem->technology_id != Technology::PERMISSION_UUID) && $leave == null && !$officalPermission){                
                
                        if($this->now->timestamp < strtotime($actual_end_time)){
                            $this->error_message = 'Cannot send report before your actual end time ('.Carbon::parse($actual_end_time)->format('h:i A').')';
                            $return = false;
                        }else if ($this->report->endTime > $this->now->toDateTimeString()){
                            $return = false;
                            $this->error_message = 'Your End time should not exceeded the current time';
                        }else{
                            // $current_time = $this->now->toDateTimeString();
                            // if($current_time < $actual_end_time) {
                            //     if($this->report->endTime != $actual_end_time) {
                            //         $return = false;
                            //         $this->error_message = 'Your actual end time is ('.Carbon::parse($actual_end_time)->format('h:i A').')';
                            //     }
                            // } else {
                            //     if($this->report->endTime > $current_time) {
                            //         $return = false;
                            //         $this->error_message = 'Your End time should not exceeded the current time';
                            //     }
                            // }
                        }
                    } else if($permission || $officalPermission) {
                        //Send Report before 15mins(break)
                        $end_datetime = AppHelper::getTimeDiffFormat($this->now->toDateString().' '.$timings->value->rp_grace, $this->now->toDateString().' '.Carbon::parse($actual_end_time)->format('H:i'), 'Y-m-d H:i');
                        $end_time_with_grace = Carbon::parse($actual_end_time)->toDateString(). ' '.$end_datetime;
                        if($this->now->timestamp < strtotime($end_time_with_grace)) {
                            $return = false;
                            $this->error_message = 'Cannot send report before your actual end time with grace ('.Carbon::parse($end_time_with_grace)->format('h:i A').')';
                        }
                    }
                }
        }

        return $return;
    }

    /**
     * Validate report end time with current time for today report
     * @return bool
     */
    private function _validate_report_start_time()
    {
        $return = true;

        if ($this->now->toDateString() == $this->report->date) {
            //If report has actual start time
            if($this->hasPermission == false){
                if($first_report = @$this->report->firstreportitem()){
                    if(strtotime($this->report->start) < strtotime($first_report->start)){
                        $this->error_message = 'Wrong report start time. Your start time ('.Carbon::parse($this->report->start)->format('h:i A').')';
                        $return = false;
                    }
                }
            }
        }

        return $return;
    }

}
