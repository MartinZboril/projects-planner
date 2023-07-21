<?php

namespace App\Listeners\Project\Activity;

use App\Events\Project\ProjectTeamChanged;
use Illuminate\Support\Str;

class LogProjectTeamNewMembersActivity
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
        if ($event->new_team->count() === 0 || $event->created_mode) {
            return;
        }

        activity()
            ->performedOn($event->project)
            ->log(Str::of('Member')->plural($event->new_team->count())." {$event->new_team->pluck('full_name')->implode(', ')} ".($event->new_team->count() === 1 ? 'was' : 'were').' added to the team');
    }
}
