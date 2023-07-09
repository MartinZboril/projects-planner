<?php

namespace App\Listeners\Task;

use App\Models\Task;
use App\Events\User\UserDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Task\UserDeletedNotification;

class SendTasksWithoutUserNotification implements ShouldQueue
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
    public function handle(UserDeleted $event): void
    {
        $event->user->tasks->each(function (Task $task) {
            $task->author->notify(new UserDeletedNotification($task));
        });
    }
}
