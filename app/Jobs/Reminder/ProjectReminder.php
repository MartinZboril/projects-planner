<?php

namespace App\Jobs\Reminder;

use App\Models\Project;
use App\Models\User;
use App\Notifications\Project\ProjectReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ProjectReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Collection $projects,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->projects->each(function (Project $project) {
            $project->team->each(function (User $user) use ($project) {
                $user->notify(new ProjectReminderNotification($project));
            });
        });
    }
}
