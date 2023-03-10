<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EntryNotification extends Mailable implements ShouldQueue
{
    use SerializesModels;

    public $entry;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($entry)
    {
        $this->entry = $entry;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
                ->subject("Entry Status {$this->entry->statusname}: {$this->entry->employee->name}")
                ->markdown('emails.entries.entrynotification')
                ->with('entry', $this->entry);
    }
}
