<?php

namespace App\Listeners\Task\Status;

use App\Events\Task\Status\TaskCompleted;
use App\Notifications\Task\Status\CompletedTaskNotification;

class SendTaskCompletedNotification
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
    public function handle(TaskCompleted $event): void
    {
        $event->task->author->notify(new CompletedTaskNotification($event->task));
    }
}
