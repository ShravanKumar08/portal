<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Models\Employee;
use App\Models\Report;
use Illuminate\Console\Command;
use \Carbon\Carbon;
use App\Mail\MonthlyAssessment;

class EmployeeMonthlyAssessment extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:employee:monthlyassessment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Employee Monthly Assessment";

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
        $mail = Setting::getMailObject('REPORT_NOTIFICATION_EMAIL');

        if ($mail) {
            $mail->queue(new MonthlyAssessment());
        }
    }
}
