<?php

namespace App\Listeners\Project\Activity;

use App\Events\Project\Status\ProjectArchived;

class LogProjectArchivedActivity
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
    public function handle(ProjectArchived $event): void
    {
        activity()
            ->performedOn($event->project)
            ->log('The project was archived.');
    }
}
