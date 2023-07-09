<?php

namespace App\Listeners\Task\Status;

use App\Events\Task\Status\TaskResumed;
use App\Notifications\Task\Status\ResumedTaskNotification;

class SendTaskResumedNotification
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
    public function handle(TaskResumed $event): void
    {
        $event->task->author->notify(new ResumedTaskNotification($event->task));
    }
}
