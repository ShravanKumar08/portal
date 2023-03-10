<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use App\Models\Employee;
use Carbon\Carbon;

class FutureSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:schedule:execute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute saved schedules';

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
        $schedules = Schedule::whereDate('schedule_date', Carbon::now()->toDateString())->where('is_executed', 0)->get();

        foreach ($schedules as $schedule) {
            $model = $schedule->model_type::find($schedule->model_id);
            $values = $schedule->value;

            if ($schedule->key == 'OFFICE_TIMING_SLOT') {
                $model->slots = $values['slots'];
                Employee::whereIn('id', $values['employee_id'])->update(['officetiming_id' => $schedule->model_id]);
            } else {
                foreach ($values as $column => $value) { 
                    $model->$column = $value;
                }
            }
            $model->save();
            $schedule->update(['is_executed' => 1]);
        }
        $this->info('Schedule Allotted Successfully');
    }
}
