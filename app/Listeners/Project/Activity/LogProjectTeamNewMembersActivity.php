<?php

namespace App\Listeners\Project\Activity;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Project\ProjectTeamChanged;
use Illuminate\Contracts\Queue\ShouldQueue;

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
