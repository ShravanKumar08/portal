<?php

namespace App\Rules;

use App\Helpers\AppHelper;
use App\Models\Employee;
use App\Models\LateEntry;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use App\Models\Report;

class PermissionValidator implements Rule
{
    protected $employee_id;
    protected $start;
    protected $end;
    protected $officeTimings;
    protected $attribute;
    protected $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($employee_id, $start, $end, $officeTimings)
    {
        $this->employee_id = $employee_id;
        $this->start = $start;
        $this->end = $end;
        $this->officeTimings = $officeTimings;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->attribute = $attribute;
        $date = Carbon::parse($value);
        $officeTimings = $this->officeTimings;
        // Set office End time for to send permission report if date not match with today
        $endTime = $officeTimings->lt_end;

        // Set actual End time for to send permission if only date match with current date
        $Report = Report::where(['employee_id' => $this->employee_id, 'date' => $date])->first();

        if($Report){
            $endTime = Carbon::parse($Report->actualEndTime)->format("H:i");
        }

        $start = Carbon::parse($this->start);
        $end = Carbon::parse($this->end);

        $this->start = $start->toDateTimeString();
        $this->end = $end->toDateTimeString();

        $official_start = Carbon::createFromFormat('H:i', $officeTimings->start)->toDateTimeString();
        $official_end = Carbon::createFromFormat('H:i', $endTime)->toDateTimeString();

        if($official_start > $official_end){
            $official_end = Carbon::createFromFormat('H:i', $endTime)->addDay(1)->toDateTimeString();
        }

        $valid = ($this->start >= $official_start && $this->end <= $official_end);
        $this->message = "You can apply permission between your official timing. Start time: {$officeTimings->start}, End time: {$endTime}";

        //Between office hours validation
        if($valid && \Auth::user()->hasRole('admin') == false){
            $morning_perm = AppHelper::getSecondsFromTime($officeTimings->morning_permission_hours.':00') ;
            $eve_perm = AppHelper::getSecondsFromTime($officeTimings->permission_hours.':00') ;
            $inbetween_mor_hours = Carbon::parse($officeTimings->start)->addSeconds($morning_perm)->format('H:i'); 
            $inbetween_eve_hours = Carbon::parse($endTime)->subSeconds($eve_perm)->format('H:i'); 
           
            $valid = $inbetween_mor_hours >= $end->format('H:i') || $inbetween_eve_hours <= $start->format('H:i');
            $this->message = "You can't able to apply permission between office Hours. Contact Admin for moreinfo. ";
        }

        if($valid){
            //when you have late entry you can't able to send report.
            $late_entry = LateEntry::query()->where('employee_id', $this->employee_id)->whereDate('date', $value)->approved()->first();

            if($late_entry && $late_entry->status == 'A'){
                $valid = false;
                $this->message = 'You can not apply permission when you have late entry. Contact admin.';
            }
        }

        return $valid;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
