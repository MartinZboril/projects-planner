<?php

namespace App\Observers;

use App\Models\File;
use App\Models\Task;

class TaskObserver
{
    public function deleted(Task $task): void
    {
        $task->files()->delete();
        File::where('fileable_type', 'App\Models\Comment')->whereIn('fileable_id', array_column($task->comments->toArray(), 'id'))->delete();
        $task->comments()->delete();
    }
}
