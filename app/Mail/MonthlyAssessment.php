<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Employee;

class MonthlyAssessment extends Mailable implements ShouldQueue
{
    use SerializesModels;

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.reports.monthlyassessment')->with(['employees' => Employee::oldest('name')->where('employeetype','P')->active()->get() ]);
    }
}
