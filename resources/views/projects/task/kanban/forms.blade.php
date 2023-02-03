@switch($task->status)
    @case(App\Enums\TaskStatusEnum::new)
        @include('tasks.forms.change', ['id' => 'start-working-on-task-' . $task->id . '-form', 'task' => $task, 'status' => App\Enums\TaskStatusEnum::in_progress])    
        @break

    @case(App\Enums\TaskStatusEnum::in_progress)
        @include('tasks.forms.change', ['id' => 'complete-working-on-task-' . $task->id . '-form', 'task' => $task, 'status' => App\Enums\TaskStatusEnum::complete])    
        @include('tasks.forms.pause', ['id' => (($task->paused) ? 'resume' : 'stop') . '-working-on-task-' . $task->id . '-form', 'task' => $task])    
        @break
    
    @case(App\Enums\TaskStatusEnum::complete)
        @include('tasks.forms.change', ['id' => 'return-working-on-task-' . $task->id . '-form', 'task' => $task, 'status' => App\Enums\TaskStatusEnum::new])    
        @break
              
@endswitch