<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LectureJoinNotification extends Mailable
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
        $name = \Auth::user()->employee->name;
        return $this->subject("Lecture Joined: {$name}")->markdown('emails.lecture.join_notification')->cc(env('HR_EMAIL_ID', 'hr@arkinfotec.com'))->with(['lecture' => $this->lecture]);
    }
}
