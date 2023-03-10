<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReleaselockMail extends Mailable implements ShouldQueue
{
    use SerializesModels;

    public $report;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($report)
    {
        $this->report = $report;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $Reportitems = @$this->report->reportitems()->where('release_request' , 1)->get();
        
        return $this->markdown('emails.reports.releaselockmail')->with(['report' => $this->report, 'Reportitems' => $Reportitems]);
    }
}
