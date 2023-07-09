<?php

namespace App\Listeners\Task\Status;

use App\Events\Task\Status\TaskReturned;
use App\Notifications\Task\Status\ReturnedTaskNotification;

class SendTaskReturnedNotification
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
    public function handle(TaskReturned $event): void
    {
        $event->task->user->notify(new ReturnedTaskNotification($event->task));
    }
}
