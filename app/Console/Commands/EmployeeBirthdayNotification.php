<?php

namespace App\Console\Commands;

use App\Mail\BirthdayEmailNotification;
use App\Mail\BirthdayEmailWishes;
use App\Models\Employee;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EmployeeBirthdayNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:birthday:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Employee Birthday Notification Mail';

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
        // Mail notification about upcoming birthday
        // $now = Carbon::now();
        // // $subject = 'Upcoming Birthday Reminder!';
        // $employees = Employee::query()->active()->whereMonth('dob', $now->month)->whereDay('dob', $now->addDays(1)->day)->get();
        // $birthday_subject = Setting::select('emailparams')->where('name','BIRTHDAY_EMAIL_NOTIFICATION_CONTENT')->first();
        // $content = Setting::fetch("BIRTHDAY_EMAIL_NOTIFICATION_CONTENT");
        // $position = (strpos($content,'w'))+1;
        // $content = substr_replace( $content,'('.Carbon::tomorrow()->format('d-m-Y').')',$position , 0 );

        // foreach ($employees as $employee) {
        //     if($e_subject = @$birthday_subject->emailparams['subject'])
        //     {
        //        $subject = Setting::strReplaceEmployeeContent($e_subject, $employee);
        //     }
        //     $mail_content = Setting::strReplaceEmployeeContent($content, $employee);
        //     $other_employees = Employee::where('id', '!=', $employee->id)->with('user')->active()->get()->pluck("user.email")->toArray();
        //     foreach($other_employees as $other_employee) {
        //         Mail::to($other_employee)->queue(new BirthdayEmailNotification($mail_content, $subject));                
        //     }
        // }

        //Birthday wishes
        $now = Carbon::now();
        $subject = 'Hearty Wishes from Team technokryon!';
        $employees = Employee::query()->active()->whereMonth('dob', $now->month)->whereDay('dob', $now->day)->get();
        $birthday_subject = Setting::select('emailparams')->where('name','BIRTHDAY_EMAIL_CONTENT')->first();
        $content = Setting::fetch("BIRTHDAY_EMAIL_CONTENT");
        
        foreach ($employees as $employee) {
            $coWorksers = Employee::where('id', '!=', $employee->id)->with('user')->active()->get()->pluck("user.email")->toArray();

            if($e_subject = @$birthday_subject->emailparams['subject'])
            {
               $subject = Setting::strReplaceEmployeeContent($e_subject, $employee);
            }
            $mail_content = Setting::strReplaceEmployeeContent($content, $employee);
            Mail::to($employee->email)
                ->bcc(['pradep@technokryon.com', 'saravana@technokryon.com'])
                ->queue(new BirthdayEmailWishes($mail_content, $subject));
        }
    }
}
