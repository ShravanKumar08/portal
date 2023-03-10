<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PermissionRequest extends Mailable implements ShouldQueue
{
    use SerializesModels;
    public $permission;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($permission)
    {
        $this->permission = $permission;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject("Permission Request: {$this->permission->employee->name}")
            ->markdown('emails.permissions.permissionrequest')
            ->with('permission', $this->permission);
    }
}
