<?php

namespace App\Listeners;

use IlluminateAuthEventsLogout;

class LogSuccessfulLogout
{

    public function handle(Logout $event)
    {
        activity('auth')
            ->causedBy($event->user)
            ->log('User logged out');
    }
}
