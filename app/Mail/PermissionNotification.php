<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PermissionNotification extends Mailable implements ShouldQueue
{
    use SerializesModels;
    public $permission;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($model)
    {
        $this->permission = $model;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject("Permission {$this->permission->statusname}: {$this->permission->employee->name}")
            ->markdown('emails.permissions.notification')
            ->with('permission',$this->permission);
    }
}
