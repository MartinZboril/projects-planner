<?php

namespace App\Listeners\Task\Status;

use App\Events\Task\Status\TaskInProgressed;
use App\Notifications\Task\Status\InProgressedTaskNotification;

class SendTaskInProgressedNotification
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
    public function handle(TaskInProgressed $event): void
    {
        $event->task->author->notify(new InProgressedTaskNotification($event->task));
    }
}
