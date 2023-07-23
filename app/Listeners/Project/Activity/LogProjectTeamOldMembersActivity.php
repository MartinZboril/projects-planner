<?php

namespace App\Listeners\Project\Activity;

use App\Events\Project\ProjectTeamChanged;
use Illuminate\Support\Str;

class LogProjectTeamOldMembersActivity
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
    public function handle(ProjectTeamChanged $event): void
    {
        if ($event->old_team->count() === 0) {
            return;
        }

        activity()
            ->performedOn($event->project)
            ->log(Str::of('Member')->plural($event->old_team->count())." {$event->old_team->pluck('full_name')->implode(', ')} ".($event->old_team->count() === 1 ? 'was' : 'were').' removed from the team');
    }
}
