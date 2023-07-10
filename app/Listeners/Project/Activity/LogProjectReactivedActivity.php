<?php

namespace App\Listeners\Project\Activity;

use App\Events\Project\Status\ProjectReactived;

class LogProjectReactivedActivity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProjectReactived $event): void
    {
        activity()
            ->performedOn($event->project)
            ->log('The project was reactived.');
    }
}
