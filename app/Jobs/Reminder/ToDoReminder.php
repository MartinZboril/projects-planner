<?php

namespace App\Jobs\Reminder;

use App\Models\ToDo;
use App\Notifications\ToDo\ToDoReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ToDoReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Collection $todos,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->todos->each(function (ToDo $todo) {
            $todo->user->notify(new ToDoReminderNotification($todo));
        });
    }
}
