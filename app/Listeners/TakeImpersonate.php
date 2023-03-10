<?php

namespace App\Listeners;

use App\Models\Setting;
use Lab404\Impersonate\Events\TakeImpersonation;

class TakeImpersonate
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
    public function handle(TakeImpersonation $event)
    {
        flash($event->impersonator->name.' impersonated as '.$event->impersonated->name)->success();
        Setting::clearThemeSession();
    }
}