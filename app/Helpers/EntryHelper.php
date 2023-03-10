<?php

namespace App\Helpers;

use App\Mail\LeaveNotification;
use App\Mail\PermissionNotification;
use App\Models\Employee;
use App\Models\Entry;
use App\Models\Entryitem;
use App\Models\LateEntry;
use App\Models\Leave;
use App\Models\Report;
use App\Models\ReportItem;
use App\Models\Setting;
use App\Models\Technology;
use App\Models\Userpermission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EntryHelper
{
    protected $employee;

    public $datetime;

    protected $report;

    protected $officetiming;

    public function __construct(Employee $employee, Carbon $datetime)
    {
        $this->employee = $employee;
        $this->datetime = $datetime;
        $this->officetiming = @$employee->officetiming->value;

        if (@$employee->user->active == false) {
            throw new \Exception('Trying to use entry helper for in-active employee');
        }
    }

    public function start($request, $date = null)
    {
        $date = !$date ? $this->getTodayEntryDate() : $date;

        if ($date) {
            $entry = Entry::firstOrCreate(['date' => $date, 'employee_id' => $this->employee->id]);
            $entry->outip = $entry->end = null;

            if ($entry->start == null) {
                $time = $this->datetime->toTimeString();

                $entry->start = $time;
                $entry->inip = $request->ip();

                if($this->employee->exlcudeFromReports() == false){
                    $this->report = Report::getTodayReport($entry->employee, $date);
                   
                    if(!$this->report){
                        $this->report = Report::firstOrNew(['date' => $date, 'employee_id' => $this->employee->id]);
                        $this->report->start = $time;
                        $this->report->save();
                    }

                    $this->validateEntry();
                } 
            }

            $entry->save();

            return ['status' => 'success', 'message' => 'Timer started', 'entry' => $entry];
        } else {
            return ['status' => 'error', 'message' => 'Office timing not found'];
        }
    }

    public function stop($request)
    {
        if ($date = $this->getTodayEntryDate()) {
            $entry = Entry::firstOrNew(['date' => $date, 'employee_id' => $this->employee->id]);
            $entry->end = $this->datetime;
            $entry->outip = $request->ip();
            $entry->save();

            return ['status' => 'success', 'message' => 'Timer stopped'];
        } else {
            return ['status' => 'error', 'message' => 'Office timing not found'];
        }
    }

    public function getTodayEntryDate()
    {
        $date = null;

        if ($timing = $this->employee->officetiming) {
            $start_time = $timing->value->start;
            $end_time = $timing->value->end;
            $date = $this->datetime->toDateString();
            if (strtotime($start_time) > strtotime($end_time)) {
                //Ex: Office timing 8:00 PM to 6:00 AM
                //officetiming start greater than end time
                //Check current time is next day -> Then take yesterday date
                $subdays = 0;

                $current = $this->datetime;
                $end = Carbon::parse($this->datetime->toDateString().' '.$end_time);

                if($current <= $end){
                    $subdays = 1;
                }

                if(!$subdays && $current->diffInHours($end) <= 4){
                    $subdays = 1;
                }

                if($subdays){
                    $date = Carbon::parse($this->datetime->toDateString())->subDays($subdays)->toDateString(); 
                }
            }
        }

        return $date;
    }

    public function getTodayEntry()
    {
        if ($date = $this->getTodayEntryDate()) {
            return Entry::where('employee_id', $this->employee->id)->where('date', $date)->first();
        }

        return null;
    }

    public function validateEntry()
    {
        $timing = $this->officetiming;

        if ($this->datetime->timestamp > strtotime($timing->lt_half_day_excuse)) {
            $this->addHalfDay();
        } else if ($this->datetime->timestamp > strtotime($timing->lt_perm)) {
            $this->addPermission();
        } else if ($this->datetime->timestamp > strtotime($timing->lt_start)) {
            $this->addLateEntry($timing);
        }
    }

    public function addLateEntry($timing)
    {
        //Adding late entry for that user
        $late_entry = LateEntry::withoutGlobalScopes()->whereDate('date', $this->datetime->toDateString())->where('employee_id', $this->employee->id)->first();

        if (!$late_entry) {
            $late_entry = LateEntry::firstOrNew(['date' => $this->datetime->toDateTimeString(), 'employee_id' => $this->employee->id]);
        }

        $late_entry->date = $this->datetime->toDateTimeString();
        $late_entry->elapsed = AppHelper::getTimeDiffFormat($timing->start . ':00', Carbon::parse($late_entry->date)->format('H:i:s'), 'H:i:s');
        $late_entry->at_training_period = ($this->employee->employeetype == 'T') ? 1 : 0 ;
        $late_entry->save();

        //get late entry count
        $count = $this->employee->late_entries()->approved()
            ->monthYear('date', $this->datetime->year, $this->datetime->month)
            ->where('employee_id', $this->employee->id)
            ->count();

        $late_setting = Setting::fetch('LATE_ENTRY_COUNT');
        $perm_setting = Setting::fetch('MAX_ALLOWED_PERMISSION');
        //get permission count
        $perm_count =  $this->employee->userpermissions()->pendingApproved()
        ->where('date', '!=', $this->datetime->toDateString())
        ->monthYear('date', $this->datetime->year, $this->datetime->month)
        ->count();

        if (in_array($count, explode(',', $late_setting['perm_interval']))) {
            //Add permission if 3rd / 6th
            $this->addPermission();
        } else if ($count > $late_setting['count'] || $perm_count >= $perm_setting) {
            //More than 6 late entries   and more than 2 perm , 4 th late entry  -> half day leave
            $this->addHalfDay();
        }
    }

    /**
     * Add permission
     */
    public function addPermission($start = null, $end = null , $msg = null)
    {
        //Check employee has permission in current month
        $allow_permission = AppHelper::allowToCreatePermission($this->employee->id, true);

        if ($allow_permission) {
            $timing = $this->employee->officetiming;
            $permission_query = $this->employee->userpermissions()->notDeclined()->whereDate('date', $this->datetime->toDateString());

            $start = $start ?: $timing->value->start;
            $end = $end ?: $this->datetime->toTimeString();

            $reportExists = Report::where('employee_id', $this->employee->id)->where('date', $this->datetime->toDateString())->whereNotNull('manual_request_time')->exists();

            if (!$permission_query->exists() && !$reportExists) {
                $request = new Request();
                $request->replace([
                    'employee_id' => $this->employee->id,
                    'date' => $this->datetime->toDateString(),
                    'start' => $start,
                    'end' => $end,
                    'reason' => $msg ?: 'Late Entry Permission',
                    'status' => 'A'
                ]);

                $permission = new Userpermission(); 
                $permission->at_training_period = ($this->employee->employeetype == 'T') ? 1 : 0 ;
                $permission->saveForm($request, $permission); 
            } else {
                $permission = $permission_query->first();
                //$permission->status = 'A'; //if it was pending
                $permission->save();
            }

            //if IN time exceeds the morning permission - split permission and break time automatically
            if($permission->start == $start){
                $office_morning_perm = $timing->value->morning_permission_hours;  
                $time_diff = Carbon::parse($start)->diff(Carbon::parse($end))->format('%H:%I');
               
                if($time_diff > $office_morning_perm){
                    $perm_diff = Carbon::parse($time_diff)->diff(Carbon::parse($office_morning_perm))->format('%H:%I');
                    $end_time = Carbon::parse($end)->diff(Carbon::parse($perm_diff))->format('%H:%I');

                    //update the endtime
                    $permission->end = $end_time;
                    $permission->save();

                    if($this->report){
                        ReportItem::firstOrCreate([
                            'report_id' => $this->report->id,
                            'technology_id' => Technology::BREAK_UUID,
                            'start' => Carbon::parse($end_time)->format('H:i:00'),
                            'end' => Carbon::parse( $end)->format('H:i:00'),
                            'lock' => 1,
                        ]);
                    }
                }
            }

            //Mail to admin and employee
            $permission->mailTo();
            Mail::to($this->employee->email)->queue(new PermissionNotification($permission));

            //Create permission row automatically in the reportitem
            if ($permission && $this->report) {
                ReportItem::firstOrCreate([
                    'report_id' => $this->report->id,
                    'technology_id' => Technology::PERMISSION_UUID,
                    'start' => Carbon::parse($timing->value->start)->format('H:i:00'),
                    'end' => Carbon::parse($permission->end)->format('H:i:00'),
                    'lock' => 1,
                ]);
            }
        } else {
            //If permission exceeds add half day leave
            $this->addHalfDay();
        }
    }

    /*
     * Add half day leave
     */
    public function addHalfDay()
    {
        $date = $this->datetime->toDateString();

        $leave_exists = $this->employee->leaves()->notDeclined()->whereHas('leaveitems', function ($q) use ($date) {
            $q->whereDate('date', $date);
        })->exists();

        // when give manual entry, it checks report exists on that day.
        $reportExists = Report::where('employee_id', $this->employee->id)->where('date', $date)->whereNotNull('manual_request_time')->exists();

        if (!$leave_exists && !$reportExists) {
            $request = new Request();
            $request->replace([
                'employee_id' => $this->employee->id,
                'start' => $date,
                'end' => $date,
                'days' => 0.5,
                'halfday' => $date,
                'leavedates' => $date,
                'reason' => 'Late Entry Half Day',
                'status' => 'A'
            ]);

            $leave = new Leave();
            $leave->at_training_period = ($this->employee->employeetype == 'T') ? 1 : 0 ;
            $leave->saveForm($request, $leave);
            $leave->mailTo();
            Mail::to($this->employee->email)->queue(new LeaveNotification($leave));
        }
    }

    public function addAttendance($request)
    {
        $entry = $this->getTodayEntry();

        if (!$entry) {
            //Start timer if not
            $entryResponse = $this->start($request, $this->datetime->toDateString());
            if ($entryResponse['status'] == 'success') {
                $entry = $entryResponse['entry'];
            }
        }

        if ($entry) {
            $attr_time = $this->datetime;

            //Avoid duplicate IN/OUT entries
            $process_entry = true;
            $entryitem = $entry->entryitems()->latest('datetime')->first();

            if ($entryitem) {
                if ($request->att_type == 'out' && $entryitem->inout == 'O') {
                    $process_entry = false;
                }

                if ($request->att_type == 'in' && $entryitem->inout == 'I') {
                    $process_entry = false;
                }
            }

            if ($process_entry) {
                $item = new Entryitem();
                $item->entry_id = $entry->id;
                $item->datetime = $attr_time->toDateTimeString();
                $item->inout = ($request->att_type == 'in' ? 'I' : 'O');
                $item->save();

                //This is for daily status report items
                if($this->employee->exlcudeFromBreaks() == false){
                    $report = Report::getTodayReport($entry->employee, $attr_time->toDateString());
                    // $report = $this->employee->reports()->where('date', $entry->date)->notDeclined()->first();
                   
                    if($report && $report->status != 'S'){
                        //When Out -> Create a new Report item with that start time
                        if ($item->inout == 'O') {
                            ReportItem::firstOrCreate([
                                'report_id' => $report->id,
                                'start' => $attr_time->format('H:i:00'),
                                'technology_id' => Technology::BREAK_UUID,
                                'lock' => 1,
                            ]);
                        } elseif ($item->inout == 'I') {
                            //When In -> Update the end time for the last reportitem
                            $reportitem = $report->reportitems()->where('lock', 1)->latest('start')->first();

                            if ($reportitem) {
                                $reportitem->end = $attr_time->format('H:i:00');
                                $reportitem->save();

                                //Delete reportitems lesser than min. break minutes (For ex: 2 minutes breaks)
                                //                            $report->deleteMinimumBreaks();
                                if (intval($reportitem->getElapsedTime('H')) == '00' && intval($reportitem->getElapsedTime('i')) <= Report::MINIMUM_BREAK_MINUTES) {
                                    $reportitem->forceDelete();
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
