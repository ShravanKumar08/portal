<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncCasual extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:sync:casual';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Sync employee's casual leave";

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
        $employees = Employee::all();

        $year = Carbon::now()->year;
        $count = Setting::fetch('CASUAL_LEAVE_COUNT');

        foreach ($employees as $employee) {
            $casuals = $employee->casual_count_per_year;

            if(!isset($casuals[$year])){
                $casuals[$year] = $count;
                $employee->casual_count_per_year = $casuals;
                $employee->save();
            }
        }
    }
}
