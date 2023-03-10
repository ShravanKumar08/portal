<?php

namespace App\Helpers;

use App\Models\Employee;
use App\Models\Entry;
use App\Models\Entryitem;
use App\Models\Holiday;
use App\Models\LateEntry;
use App\Models\Leave;
use App\Models\Report;
use App\Models\ReportItem;
use App\Models\Technology;
use App\Models\Userpermission;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeHelper
{
    protected $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function getDailyReportChartData($year, $month)
    {
        $now = Carbon::now();

        $year = $year ?? $now->year;
        $month = $month ?? $now->month;

        $worked = $this->getDailyReportWorkedQuery($year, $month)->count();
        $leaves = $this->getDailyReportLeaveQuery($year, $month)->sum('leaveitems.days');
        $permissions = $this->getDailyReportPermissionQuery($year, $month)->count();
        $official_holidays = Holiday::monthYear('date', $year, $month)->count();
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $no_records = $days_in_month - ($leaves + $permissions + $official_holidays + $worked);

        $data = [
            [
                'label' => "Worked Days ($worked)",
                'data' => $worked,
                'color' => '#B4FF80',
            ],
            [
                'label' => "Leaves ($leaves)",
                'data' => $leaves,
                'color' => '#F84848',
            ],
            [
                'label' => "Permissions ($permissions)",
                'data' => $permissions,
                'color' => '#E47296',
            ],
            [
                'label' => "Official Holidays ($official_holidays)",
                'data' => $official_holidays,
                'color' => '#EDEDED',
            ],
            [
                'label' => "No Records ($no_records)",
                'data' => $no_records,
                'color' => '#4f5467',
            ],
        ];

        return json_encode($data);
    }

    public function getDailyReportLeaveQuery($year, $month)
    {
        return $this->employee->leaveitems()->whereHas('leave', function ($q){
            $q->notDeclined();
        })->monthYear('leaveitems.date', $year, $month);
    }

    public function getDailyReportPermissionQuery($year, $month)
    {
        return $this->employee->userpermissions()->notDeclined()->monthYear('date', $year, $month);
    }

    public function getDailyReportWorkedQuery($year, $month)
    {
        return $this->employee->reports()->notDeclined()->monthYear('date', $year, $month);
    }
    
    public function getMonthlyReportitemsData($request)
    {
        $data = [];

        $data['year'] = $year = Carbon::parse($request->month_year)->format('Y');
        $data['month'] = $month = Carbon::parse($request->month_year)->format('m');
        $data['days_in_month'] = cal_days_in_month(CAL_GREGORIAN, $data['month'], $data['year']);

        $data['worked'] = $this->getDailyReportWorkedQuery($year, $month)->get();
        $data['permissions'] =  $this->getDailyReportPermissionQuery($year, $month)->get();
        $data['leaveitems'] =  $this->getDailyReportLeaveQuery($year, $month)->get();
        $data['holidays'] = Holiday::monthYear('date', $year, $month)->get();
        
        return $data;
    }

    public function getMonthlyLeaveReportitemsData($request)
    {
        $data = [];

        if($request->yearly){
            $year = @$request->month_year ?: Carbon::now()->year;
            $data['leaveitems'] = $this->employee->leaveitems()->whereYear('date', $year)->whereHas('leave', function($q){
                $q->approved();
            })->oldest('date')->get();
        }else{
            $now = Carbon::parse($request->month_year);

            $data['leaveitems'] = $this->employee->leaveitems()->monthYear('date', $now->year, $now->month)->whereHas('leave', function($q){
                $q->approved();
            })->get();
        }
        return $data;
    }
    
    public function getEmployeeProfileFormData()
    {
        $this->employee->appendCustomFields();
        $data['Employee'] = $this->employee;
        $data['gender'] = Employee::$gender;
        $data['custom_fields'] = CustomfieldHelper::getCustomfieldsByModule(Employee::class);
        
        return $data;
    }

    public function getEmployeeMonthlyAssessment($start, $end)
    {
        $employee = $this->employee;

        $reports = $employee->reports()->whereBetween('date', [$start, $end]);

        $data['workedseconds'] = $reports->sum(\DB::raw("TIME_TO_SEC(workedhours)"));
        $data['workedhours'] = AppHelper::secondsToHours($data['workedseconds']);
        $data['breakhours'] = AppHelper::secondsToHours($reports->sum(\DB::raw("TIME_TO_SEC(breakhours)")));
        $data['totalhours'] = AppHelper::secondsToHours($reports->sum(\DB::raw("TIME_TO_SEC(totalhours)")));
        $data['permissions'] = $employee->userpermissions()->approved()->whereBetween('date', [$start, $end])->count();
        $data['leaves'] = (float) $employee->leaves()->approved()->whereBetween('start', [$start, $end])->sum('days');
        $data['late_entries'] = $employee->late_entries()->approved()->whereBetween('date', [$start, $end])->count();

        return $data;
    }

    public function getEmployeeBreakTiming($start, $end)
    {
        $employee = $this->employee;

        $reports = $employee->reports()->whereBetween('date', [$start, $end]);
            
        $data['breakhours'] = AppHelper::secondsToHours($reports->sum(\DB::raw("TIME_TO_SEC(breakhours)")));
        $data['permissionhours'] = AppHelper::secondsToHours($reports->sum(\DB::raw("TIME_TO_SEC(permissionhours)")));
        $org_break = $employee->officetiming->value->break_hours.':00'; 
        $breakSeconds = AppHelper::getSecondsFromTime($org_break);
       
        $exceed_query = (clone $reports)->where('breakhours' , '>' , $org_break);
        $data['exceeded_break'] = $exceed_query->count();
        $exceedBreak =  $exceed_query->sum(\DB::raw("TIME_TO_SEC(breakhours)"));
        $break_exceedSeconds = $breakSeconds * $data['exceeded_break']; 
        $diff = $exceedBreak - $break_exceedSeconds; 
        $data['ExceedBreak'] = AppHelper::secondsToHours($diff);
        
        $unused_query = (clone $reports)->where('breakhours' , '<' , $org_break);
        $lessBreak = $unused_query->sum(\DB::raw("TIME_TO_SEC(breakhours)"));
        $data['less_break'] = $unused_query->count();
        $break_lessSeconds = $breakSeconds * $data['less_break']; 
        $diff_lessbreak =   $break_lessSeconds - $lessBreak ;
        $data['lessBreak'] = AppHelper::secondsToHours($diff_lessbreak); 
       
    
        return $data;
    }

    public function getTraineeBreakTiming($start , $end)
    {
        $entries = Entry::where('employee_id', $this->employee->id)->whereBetween('date' , [$start , $end])->get();
        
        $total_out_seconds = $unused_break_seconds =  $exceed_break_count  =  $exceed_break_seconds = $unused_break_count = 0;
      
        foreach($entries as  $entry)
        {
            $entry->getEntryItems();
            $org_break = $entry->employee->officetiming->value->break_hours;
                    
            $total_out_seconds += AppHelper::getSecondsFromTime($entry->total_out_hours.':00');

            if($entry->total_out_hours != '0:00' && $entry->total_out_hours > $org_break ){
                $exceedBreak = AppHelper::getTimeDiffFormat(Carbon::parse($entry->total_out_hours)->format('H:i:00'), Carbon::parse($org_break)->format('H:i:00'), 'H:i:00', 'H:i', false); 
                $exceed_break_seconds += AppHelper::getSecondsFromTime($exceedBreak .':00');
                $exceed_break_count += 1;
            }

            if($entry->total_out_hours < $org_break ){
                $unusedBreak = AppHelper::getTimeDiffFormat(Carbon::parse($org_break)->format('H:i:00'),Carbon::parse($entry->total_out_hours)->format('H:i:00') , 'H:i:00', 'H:i', false); 
                $unused_break_seconds += AppHelper::getSecondsFromTime($unusedBreak .':00');
                $unused_break_count += 1;
            }
        }

        if($exceed_break_count == 0){
            $exceed_break_count = '-';
        }elseif($unused_break_count == 0){
            $unused_break_count = '-';
        }

        return [
            'total_out' => AppHelper::secondsToHours($total_out_seconds),
            'exceedCount' => $exceed_break_count,
            'exceedBreak' => AppHelper::secondsToHours($exceed_break_seconds),
            'unusedCount' =>  $unused_break_count,
            'unusedBreak' => AppHelper::secondsToHours($unused_break_seconds),
        ];
    }
    
}
