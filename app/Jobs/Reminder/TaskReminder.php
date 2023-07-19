<?php

namespace App\Jobs\Reminder;

use App\Models\Task;
use App\Notifications\Task\TaskReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class TaskReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Collection $tasks,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->tasks->each(function (Task $task) {
            $task->author->notify(new TaskReminderNotification($task));
            $task->user->notify(new TaskReminderNotification($task));
        });
    }
}
