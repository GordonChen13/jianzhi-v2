<?php

namespace App\Listeners\Employer;

use App\Events\User\ApplyWork;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WorkApplied
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
     * @param  ApplyWork  $event
     * @return void
     */
    public function handle(ApplyWork $event)
    {
        //
    }
}
