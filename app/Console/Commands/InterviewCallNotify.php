<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\InterviewRound;
use App\Models\User;
use App\Notifications\InterviewCallNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class InterviewCallNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:notify-interviewcall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'InterviewCall notification with slack';

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
        $now = Carbon::now()->addMinutes(30)->toDateTimeString();
        $schedules = InterviewRound::where('datetime', $now)->get();

        foreach($schedules as $schedule){
            $schedule->notify(new InterviewCallNotification($schedule));
        }

        $this->info('notification sent');
    }
}
