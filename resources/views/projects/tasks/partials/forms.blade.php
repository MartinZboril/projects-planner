<!-- Task status change forms -->
@if (!$task->paused)
    @if ($task->status === App\Enums\TaskStatusEnum::new)
        @include('projects.tasks.forms.change', ['id' => 'start-working-on-task-' . $task->id . '-form', 'status' => App\Enums\TaskStatusEnum::in_progress])    
    @endif
    @if ($task->status === App\Enums\TaskStatusEnum::in_progress)
        @include('projects.tasks.forms.change', ['id' => 'complete-working-on-task-' . $task->id . '-form', 'status' => App\Enums\TaskStatusEnum::complete])    
    @endif
    @if ($task->status === App\Enums\TaskStatusEnum::complete)
        @include('projects.tasks.forms.change', ['id' => 'return-working-on-task-' . $task->id . '-form', 'status' => App\Enums\TaskStatusEnum::new])    
    @endif    
@endif
<!-- Pause work on task form -->
@if ($task->status === App\Enums\TaskStatusEnum::in_progress)
    @include('projects.tasks.forms.pause', ['id' => ($task->paused ? 'resume' : 'stop') . '-working-on-task-' . $task->id . '-form', 'action' => 0])            
@endif