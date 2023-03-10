<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\Report;
use App\Models\Entryitem;
use App\Models\ReportItem;
use App\Models\Entry;
use App\Models\Technology;
use Carbon\Carbon;

class FixReportBreaks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:fix:reportbreaks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Report Breaks';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = $this->ask('Enter Date');
        
        $reports = Report::where('date',$date)->get();
        
        foreach($reports as $report){
         if($report->employee->exlcudeFromBreaks() == false){
            $items = $report->reportitems()->where('technology_id', Technology::BREAK_UUID)->where('lock',1)->get();
       
            foreach($items as $item){
              $item->forcedelete();
            }
            
            $entryitems = Entryitem::whereHas('entry' , function($q) use ($report){
                $q->where('date' , $report->date)->where('employee_id' , $report->employee_id);
            })->oldest('datetime')->get();
                       
            foreach($entryitems as $key => $value){
                               
                if($value->inout != 'I'){
                    if($next_entry = @$entryitems[$key + 1]){
                       
                       $reportitem = ReportItem::firstOrCreate([
                            'report_id' => $report->id,
                            'start' =>  Carbon::parse($value->datetime)->format('H:i:00'),
                            'end' => Carbon::parse($next_entry->datetime)->format('H:i:00'),
                            'technology_id' => Technology::BREAK_UUID,
                            'lock' => 1,
                        ]);
                       
                        if (intval($reportitem->getElapsedTime('H')) == '00' && intval($reportitem->getElapsedTime('i')) <= Report::MINIMUM_BREAK_MINUTES) {
                            $reportitem->forceDelete();
                        }
                    }
                }
            }
        }
    }
        $this->info('Removed old Breaks and new breaks added Successfully');
        
    }  
       
}
