<?php

namespace App\Console\Commands;

use App\Helpers\EntryHelper;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Report;
use App\Models\Setting;
use App\Models\Technology;
use Illuminate\Console\Command;
use \Carbon\Carbon;

class CheckDailyReport extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:report:check {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Check daily report";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $carbon = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::now();
        $progress_reports = Report::query()->progress()->get();
        
        foreach ($progress_reports as $progress_report) {
            try{
                $helper = new EntryHelper($progress_report->employee, $carbon);
                if($entry_date = $helper->getTodayEntryDate()){

                    $report_date = $progress_report->date;

                    $officetimings = $progress_report->employee->officetiming->value;

                    $timestart = $officetimings->start;
                    $timeend = $officetimings->end;

                    if ($timestart > $timeend){
                        $report_date = Carbon::parse($report_date)->addDay(1)->toDateString();
                    }

                    if(strtotime($report_date) < strtotime($entry_date)){
                        $progress_report->status = 'P';
                        $progress_report->save();
                        $progress_report->reportitems()->whereNull('end')->where('technology_id', Technology::BREAK_UUID)->forceDelete();
                        $progress_report->pendingNotificationMail();
                    }
                }
            }catch (\Exception $e){
                //
            }
        }

        $date_obj = $carbon->subDay(1);
        $date = $date_obj->toDateString();

        $official_leave_today = Setting::isOfficialLeaveToday($date);
        
        if (Holiday::where("date", $date)->exists() == false && $official_leave_today == false && $date_obj->dayOfWeek != Carbon::SUNDAY) {
            //Get employees who doesn't have reports
            $employees = Employee::query()->active()
                    ->whereDoesntHave('reports', function ($query) use ($date){
                        $query->where('date', $date);
                    })
                    ->whereDoesntHave('leaves', function ($query) use ($date){
                        $query->where('start', '<=', $date)
                                ->where('end', '>=', $date)
                                ->where('days', '!=', 0.5);
                    })
                    ->get();

            foreach ($employees as $emp) {
                if ($emp->exlcudeFromReports() == false) {
                    $start = $emp->officetiming->value->start;
                    $report = Report::firstOrNew(['date' => $date, 'employee_id' => $emp->id]);
                    $report->start = $start;
                    $report->status = 'P';
                    $report->save();
                }
            }
        }

        if($official_leave_today){
            Holiday::updateOrCreate(
                ['date' => $date],
                ['name' => 'Official leave']
            );
        }
    }
}
