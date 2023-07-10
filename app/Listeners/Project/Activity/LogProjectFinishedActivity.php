<?php

namespace App\Listeners\Project\Activity;

use App\Events\Project\Status\ProjectFinished;

class LogProjectFinishedActivity
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
    public function handle(ProjectFinished $event): void
    {
        activity()
            ->performedOn($event->project)
            ->log('Project was finished.');
    }
}
