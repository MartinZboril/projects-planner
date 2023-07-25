<?php

namespace App\Jobs\Reminder;

use App\Models\Todo;
use App\Notifications\Todo\TodoReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class TodoReminder implements ShouldQueue
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
        $this->todos->each(function (Todo $todo) {
            $todo->user->notify(new TodoReminderNotification($todo));
        });
    }
}
