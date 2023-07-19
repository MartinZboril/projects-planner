<?php

namespace App\Listeners\Project\Activity;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Project\ProjectTeamChanged;
use Illuminate\Contracts\Queue\ShouldQueue;

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
