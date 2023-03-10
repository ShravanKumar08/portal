<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReportsStatusNotification extends Mailable implements ShouldQueue
{
    use SerializesModels;

    public $report;

    public $to_employee;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($report, $to_employee = false)
    {
        $this->report = $report;
        $this->to_employee = $to_employee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Pending Status {$this->report->statusname}";

        if($this->to_employee == false){
            $subject .= ": {$this->report->employee->name}";
        }

        return $this
            ->subject($subject)
            ->markdown('emails.reports.reportsstatusnotification')
            ->with('report',$this->report);
    }
}
