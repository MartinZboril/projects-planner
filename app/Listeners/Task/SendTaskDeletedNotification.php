<?php

namespace App\Listeners\Task;

use App\Events\Task\TaskDeleted;
use App\Notifications\Task\TaskDeletedNotification;

class SendTaskDeletedNotification
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
    public function handle(TaskDeleted $event): void
    {
        $event->task->user->notify(new TaskDeletedNotification($event->task));
    }
}
