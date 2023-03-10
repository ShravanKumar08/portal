<?php

namespace App\Console;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->call(function () {
//            Log::info(Carbon::now()->toDateTimeString().' cron runs at');
//        })->everyMinute();

        $schedule->command('custom:report:check')->dailyAt('01:00');
        $schedule->command('custom:reset:permissionleave')->monthlyOn(1, '02:00');	
        $schedule->command('custom:employee:monthlyassessment')->monthlyOn(31, '22:00');
        $schedule->command('custom:birthday:notification')->dailyAt('10:00');
        $schedule->command('custom:sync:casual')->yearly();
        $schedule->command('custom:fix:unusedproject')->dailyAt('03:00');
        $schedule->command('custom:schedule:execute')->dailyAt('00:05');
        $schedule->command('custom:sync:officetime')->monthlyOn(1, '03:00');

        //DB Backup commands
        $schedule->command('backup:run --only-db')->weekly();
        $schedule->command('backup:clean')->weekly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
