<?php

/**
 * Created by PhpStorm.
 * User: technokryon
 * Date: 13/4/18
 * Time: 1:03 PM
 */

namespace App\Helpers;

use App\Models\Employee;
use App\Models\LateEntry;
use App\Models\Setting;
use App\Models\UserSettings;
use Carbon\Carbon;
use App\Models\Holiday;
use App\Models\Userpermission;
use App\Models\Leave;
use App\Models\InterviewRound;
use Illuminate\Support\Facades\Schema;
use Mail;

class AppHelper
{

    public static function getDomainFromEmail($email)
    {
        // Get the data after the @ sign
        $domain = substr(strrchr($email, "@"), 1);

        return $domain;
    }

    public static function allowToCreatePermission($employee_id, $exceptToday = false)
    {
        $employee = Employee::find($employee_id);

        $now = Carbon::now();

        $query = $employee->userpermissions();

        $allowed_permission = $employee->getAllowedPermissionCount();

        if($exceptToday){
            $query->where('date', '!=', $now->toDateString());
        }

        $used_permission = $query->notDeclined()->monthYear('date', $now->year, $now->month)->count();

        return $allowed_permission > $used_permission;
    }

    public static function getSettingEmails($setting)
    {
        return self::getSettingEmailsArray(Setting::fetch($setting));
    }

    public static function getSettingEmailsArray($array)
    {
        return array_map(function ($val) {
            return explode(',', $val);
        }, array_filter($array));
    }

    public static function getMailObject($emails)
    {
        if($to = @$emails['to']){
            $mail = Mail::to($to);

            if($cc = @$emails['cc']){
                $mail->cc($cc);
            }
            if($bcc = @$emails['bcc']){
                $mail->bcc($bcc);
            }

            return $mail;
        }

        return null;
    }

    public static function getMinutesFromTime($time)
    {
        $parse = Carbon::parse($time);
        return ($parse->hour * 60) + $parse->minute;
    }

    public static function getTimeDiff($start, $end, $diff = 'diffInSeconds', $format = 'H:i', $addDay = true)
    {
        $startTime = Carbon::createFromFormat($format, $start);
        $endTime = Carbon::createFromFormat($format, $end);

       if($addDay && $startTime > $endTime){
           $endTime->addDay(1);
       }

        return $endTime->$diff($startTime);
    }

    public static function getTimeDiffFormat($start, $end, $format = 'H:i', $ret_format = 'H:i', $addDay = true)
    {
        return gmdate($ret_format, self::getTimeDiff($start, $end, 'diffInSeconds', $format, $addDay));
    }
    
    public static function formatTimestring($value, $to_format, $from_format = 'parse')
    {
        $carbon = ($from_format == 'parse') ? Carbon::parse($value) : Carbon::createFromFormat($from_format, $value);
        return $carbon->format($to_format);
    }

    public static function getButtonColorByStatus($status)
    {
        if ($status == "A") {
            $color_class = 'success';
        } elseif ($status == "D" || $status == "U") {
            $color_class = 'inverse';
        } else{
            $color_class = 'danger';
        }

        return $color_class;
    }

    public static function getLabelColorByType($type)
    {
        if ($type == "casual") {
            $color_class = 'success';
        } elseif ($type == "paid") {
            $color_class = 'danger';
        } elseif ($type == 'compensation') {
            $color_class = 'primary';
        } else {
            $color_class = 'inverse';
        }

        return $color_class;
    }

    public static function getButtonColorByStatusReport($status)
    {
        if ($status == "S") {
            $color_class = 'success';
        } elseif ($status == "D") {
            $color_class = 'inverse';
        } else if ($status == "R") {
            $color_class = 'info';
        } else {
            $color_class = 'danger';
        }

        return $color_class;
    }

    public static function getDashboardWidgetsProgressbar($status)
    {
        $color_class = '';

        if ($status <= 50) {
            $color_class = 'bg-info';
        } elseif ($status <= 80) {
            $color_class = 'bg-warning';
        } elseif ($status > 80) {
            $color_class = 'bg-danger';
        }

        return $color_class;
    }

    public static function getTableColumnsByName($name)
    {
        return Schema::getColumnListing($name);
    }

    public static function insertStringAndImplode($names, $glue = '<br />')
    {

        
        $arr = array_chunk(collect(explode(', ', $names))->flatten()->toArray(), 5);

        $arr = array_map(function ($str) {
            return implode(', ', $str);
        }, $arr);

        return implode($glue, $arr);
    }

    public static function insertBreak($reason)
    {
        $word = wordwrap($reason, 15, "<br>\n");

        return $word;
    }

    public static function calendarDashboard($request, $prefix = 'admin')
    {
        $events = [];

        $start_obj = Carbon::createFromTimestamp($request->start)->startOfDay();
        $end_obj = Carbon::createFromTimestamp($request->end)->endOfDay();

        $start = $start_obj->toDateTimeString();
        $end = $end_obj->toDateTimeString();

        if ($request->filters != '') {
            //Holidays
            if (in_array('holidays', $request->filters)) {
//                $value = Setting::fetch('OFFICIAL_PERMISSION_LEAVE_DAYS');
//
//                $official_leaves = array_where($value['leave'], function ($val){
//                    return $val['value'] == 1;
//                });
//
//                for($date = $start_obj->copy(); $date->lte($end_obj); $date->addDay()) {
//                    if($date->month == Carbon::now()->month){
//                        if($dayWeek = @$official_leaves[$date->weekOfMonth]){
//                            if($date->dayOfWeek == $dayWeek['dayOfWeek']){
//                                $events[$date->toDateString()] = [
//                                    'title' => 'Official Holiday',
//                                    'start' => $date->toDateString(),
//                                    'id' => 1,
//                                    'data_url' => url($prefix . '/holiday', 1),
//                                    'className' => 'bg-success',
//                                ];
//                            }
//                        }
//                    }
//                }

                $holidays = Holiday::query()->whereBetween('date', [$start, $end])->get();
                foreach ($holidays as $holiday) {
                    $events[$holiday->date] = [
                        'title' => $holiday->name,
                        'start' => $holiday->date,
                        'id' => $holiday->id,
                        'data_url' => url($prefix . '/holiday', $holiday->id),
                        'className' => 'bg-success',
                        'icon' => 'mdi mdi-star',
                    ];
                }
            }

            //Permissions
            if (in_array('permissions', $request->filters)) {
                $permissions = Userpermission::query()->whereBetween('date', [$start, $end])->notDeclined()->get();
                foreach ($permissions as $permission) {
                    $events[] = [
                        'title' => $permission->employee->shortname,
                        'start' => $permission->starttime,
                        'end' => $permission->endtime,
                        'id' => $permission->id,
                        'data_url' => url($prefix . '/userpermission', $permission->id),
                        'className' => 'bg-info',
                        'icon' => 'mdi mdi-clipboard-check',
                    ];
                }
            }

            //Leave
            if (in_array('leaves', $request->filters)) {
                $leaves = Leave::query()->where('start', '>=', $start)->where('end', '<=', $end)->notDeclined()->get();

                foreach ($leaves as $leave) {
                    $employee = $leave->employee;
                    $officetiming = $employee->officetiming->value;

                    $events[] = [
                        'title' => $employee->shortname,
                        'start' => Carbon::createFromFormat('Y-m-d H:i', $leave->start . $officetiming->start)->toDateTimeString(),
                        'end' => Carbon::createFromFormat('Y-m-d H:i', $leave->end . $officetiming->end)->toDateTimeString(),
                        'id' => $leave->id,
                        'data_url' => url($prefix . '/leave', $leave->id),
                        'className' => 'bg-danger',
                        'icon' => 'mdi mdi-file-document',
                    ];
                }
            }

            //Late entries
            if (in_array('late_entries', $request->filters)) {
                $lates = LateEntry::query()->whereBetween('date', [$start, $end])->notDeclined()->get();

                foreach ($lates as $late) {
                    $employee = $late->employee;
                    if($employee->officetiming){
                        $officetiming = $employee->officetiming->value;
                    }

                    $events[] = [
                        'title' => $late->employee->shortname,
                        'start' => Carbon::parse( $late->date)->toDateString().' '.$officetiming->start,
                        'end' => $late->date,
                        'id' => $late->id,
                        'data_url' => url($prefix . '/late_entries', $late->id),
                        'className' => 'bg-warning',
                        'icon' => 'mdi mdi-alarm-check',
                    ];
                }
            }

            //birthdays
            if (in_array('birthdays', $request->filters)) {
                $birthdays = Employee::active()->get()->filter(function ($emp) use ($start_obj, $end_obj) {
                    $month = Carbon::parse($emp->dob)->month;
                    $start_m = $start_obj->format('m');
                    $end_m = $end_obj->format('m');

                    return ($start_m <= $month && $end_m >= $month) || in_array($month, [$start_m, $end_m]);
                });

                foreach ($birthdays as $birthday) {
                    $dob = Carbon::parse($birthday->dob);
                    $events[] = [
                        'title' => $birthday->name,
                        'start' => $start_obj->year.'-'.$dob->format('m-d'),
                        'icon' => 'fa fa-birthday-cake',
                        'className' => 'bg-primary',
                    ];
                }
            }

            if (in_array('interview_rounds', $request->filters)) {
                $schedules = InterviewRound::query()->has('interview_call')->whereBetween('datetime', [$start, $end])->get();

                foreach ($schedules as $schedule) {
                    $call = $schedule->interview_call;
                    $time =  Carbon::parse($schedule->datetime)->toTimeString();
                    $events[] = [
                        'title' => 'InterviewCall at '.$time,
                        'start' => Carbon::parse($schedule->datetime)->toDateString(),
                        'icon' => 'fa fa-phone',
                        'className' => 'bg-dark',
                        'data_url' => url($prefix . '/getinterviewcalls', @$call->id),
                    ];
                }
            }
        }

        $userSettings = UserSettings::firstOrNew(['user_id' => \Auth::user()->id, 'name' => 'CALENDAR_LAST_VALUE']);
        $userSettings->value = json_encode(['filters' => $request->filters]);
        $userSettings->save();

        return array_values($events);
    }

    public static function getWeekDays()
    {
        return [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];
    }

    public static function secondsToHours($seconds)
    {
        $H = floor($seconds / 3600);
        $i = ($seconds / 60) % 60;
//        $s = $seconds % 60;

        return str_pad($H, 2, '0', STR_PAD_LEFT).':'.str_pad($i, 2, '0', STR_PAD_LEFT);
    }

    public static function getSecondsFromTime($time)
    {
        $time_split = explode(':',$time);
        return ($time_split[0] * 60 * 60 ) + ($time_split[1] * 60) + ($time_split[2]);
    }
}
