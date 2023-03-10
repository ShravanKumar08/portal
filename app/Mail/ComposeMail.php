<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ComposeMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $composemail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($composemail)
    {
        $this->composemail = $composemail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(!empty($this->composemail['composefile'])){
            $files = $this->composemail['composefile'];
        }
        $mail = $this->markdown('emails.contact.composemail')->with(['composemail' => $this->composemail]);
        if(!empty($files)){
            foreach ($files as $file) {
                $mail->attach(Storage::disk('public')->url($file)); // attach each file
            }
        }

        $mail->from(\Auth::user()->email, \Auth::user()->name)->replyTo(\Auth::user()->email);

        return $mail;
    }
}
