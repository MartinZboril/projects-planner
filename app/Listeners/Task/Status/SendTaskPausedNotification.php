<?php

namespace App\Listeners\Task\Status;

use App\Events\Task\Status\TaskPaused;
use App\Notifications\Task\Status\PausedTaskNotification;

class SendTaskPausedNotification
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
    public function handle(TaskPaused $event): void
    {
        $event->task->author->notify(new PausedTaskNotification($event->task));
    }
}
