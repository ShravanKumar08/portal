<?php

namespace App\Console\Commands;

use App\Helpers\EntryHelper;
use App\Models\Entry;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Mail\ReportsNotification;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;
use App\Models\Technology;

class CheckTraineeDailyEntry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:entry:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Check trainee daily entry";

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
        $progress_entries = Entry::query()->traineeEndtime()->get();

        foreach ($progress_entries as $progress_entry) {
            $helper = new EntryHelper($progress_entry->employee, Carbon::now());

            if($entry_date = $helper->getTodayEntryDate()){
                if(strtotime($progress_entry->date) < strtotime($entry_date)){
                    $progress_entry->status = 'P';
                    $progress_entry->save();
                    $progress_entry->pendingTraineeNotificationMail();
                }
            }
        }
    }
}
