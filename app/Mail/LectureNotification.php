<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LectureNotification extends Mailable
{
    use Queueable, SerializesModels;
    
    public $lecture;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($lecture) {
        $this->lecture = $lecture;
    }

    /**
     * Build the message.
     *
     * @return $this
     */    
    public function build()
    {   
        return $this->subject('Lecture Notification!')->markdown('emails.lecture.notification')->cc(env('HR_EMAIL_ID', 'hr@arkinfotec.com'))->with(['lecture' => $this->lecture]);
    }
}
