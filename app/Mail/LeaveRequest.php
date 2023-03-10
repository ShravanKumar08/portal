<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LeaveRequest extends Mailable implements ShouldQueue
{
    use SerializesModels;
    public $leave;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($model)
    {
        $this->leave = $model;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject("Leave Request: {$this->leave->employee->name}")
            ->markdown('emails.leaves.leaverequest')
            ->with('leave', $this->leave);
    }
}
