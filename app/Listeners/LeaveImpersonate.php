<?php

namespace App\Listeners;

use App\Models\Setting;
use Lab404\Impersonate\Events\LeaveImpersonation;

class LeaveImpersonate
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderShipped  $event
     * @return void
     */
    public function handle(LeaveImpersonation $event)
    {
        flash($event->impersonator->name.' leave impersonation from '.$event->impersonated->name)->success();
        Setting::clearThemeSession();
    }
}