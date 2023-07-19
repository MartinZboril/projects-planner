<?php

namespace App\Listeners\Task;

use App\Events\User\UserDeleted;
use App\Models\Task;
use App\Notifications\Task\UserDeletedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

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
