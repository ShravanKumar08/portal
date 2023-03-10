<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\Officetiming;
use App\Models\Officetimingslot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PhpParser\Node\Expr\New_;

class SyncOfficeTiming extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:sync:officetime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Officetiming Customizing';

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
        $year = date('Y');
        $currentMonth = date('F');
        $previousmonth = Date('F', strtotime($currentMonth . " last month"));
        $officetimings = Officetiming::all();

        //Every start of the month we sync the previous month sat and into current month
        if(date('d') == 1)
        {
            $first_sat =  date('j', strtotime('first sat of '.$currentMonth. $year));
            $second_sat =  date('j', strtotime('second sat of '.$currentMonth. $year));
            $third_sat = date('j', strtotime('third sat of '.$currentMonth. $year));
            $fourth_sat = date('j', strtotime('fourth sat of '.$currentMonth. $year));
            $fifth_sat = date('j',strtotime('fifth sat of '.$currentMonth. $year));
            $prevmonthfirst_saturday = date('j', strtotime('first sat of '.$previousmonth. $year));
            $prevmonthsecond_saturday = date('j', strtotime('second sat of '.$previousmonth. $year));
            $prevmonththird_saturday = date('j', strtotime('third sat of '.$previousmonth. $year));
            $prevmonthfourth_saturday = date('j', strtotime('fourth sat of '.$previousmonth. $year));
            $prevmonth_normalday = date('j', strtotime('first mon of '.$previousmonth. $year));

            foreach($officetimings as $officetiming)
            {
                $slots = $officetiming->slots;
                $secondsaturday = $officetiming->slots[$prevmonthsecond_saturday];
                $normalday = $officetiming->slots[$prevmonth_normalday];
                $firstsaturday = $officetiming->slots[$prevmonthfirst_saturday];
                $normalday = $officetiming->slots[$prevmonth_normalday];
                $slots[$first_sat] = $firstsaturday;
                $slots[$second_sat] = $secondsaturday;
                $slots[$third_sat] = $firstsaturday;
                $slots[$fourth_sat] = $secondsaturday;
                if($fifth_sat){
                    $slots[$fifth_sat] = $firstsaturday;
                }
                $slots[$prevmonthfirst_saturday] = $normalday;
                $slots[$prevmonthsecond_saturday] = $normalday;
                $slots[$prevmonththird_saturday] = $normalday;
                $slots[$prevmonthfourth_saturday] = $normalday;
                $officetiming->slots = $slots;
                $officetiming->save();
            }
        }
    }
}
