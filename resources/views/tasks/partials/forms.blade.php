<!-- Task status change forms -->
@if (!$task->paused)
    @if ($task->status == App\Enums\TaskStatusEnum::new)
        @include('tasks.forms.change', ['id' => 'start-working-on-task-' . $task->id . '-form', 'task' => $task, 'status' => App\Enums\TaskStatusEnum::in_progress])    
    @endif
    @if ($task->status == App\Enums\TaskStatusEnum::in_progress)
        @include('tasks.forms.change', ['id' => 'complete-working-on-task-' . $task->id . '-form', 'task' => $task, 'status' => App\Enums\TaskStatusEnum::complete])    
    @endif
    @if ($task->status == App\Enums\TaskStatusEnum::complete)
        @include('tasks.forms.change', ['id' => 'return-working-on-task-' . $task->id . '-form', 'task' => $task, 'status' => App\Enums\TaskStatusEnum::new])    
    @endif    
@endif
<!-- Pause work on task form -->
@if ($task->status == App\Enums\TaskStatusEnum::in_progress)
    @if ($task->paused)
        @include('tasks.forms.pause', ['id' => 'resume-working-on-task-' . $task->id . '-form', 'task' => $task, 'action' => 0])            
    @else
        @include('tasks.forms.pause', ['id' => 'stop-working-on-task-' . $task->id . '-form', 'task' => $task, 'action' => 1])    
    @endif
@endif