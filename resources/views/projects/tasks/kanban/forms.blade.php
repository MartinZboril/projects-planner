@switch($task->status)
    @case(App\Enums\TaskStatusEnum::new)
        @include('projects.tasks.forms.change', ['id' => 'start-working-on-task-' . $task->id . '-form', 'project' => $project, 'task' => $task, 'status' => App\Enums\TaskStatusEnum::in_progress])    
        @break

    @case(App\Enums\TaskStatusEnum::in_progress)
        @include('projects.tasks.forms.change', ['id' => 'complete-working-on-task-' . $task->id . '-form', 'project' => $project, 'task' => $task, 'status' => App\Enums\TaskStatusEnum::complete])    
        @include('projects.tasks.forms.pause', ['id' => (($task->paused) ? 'resume' : 'stop') . '-working-on-task-' . $task->id . '-form', 'project' => $project, 'task' => $task])    
        @break
    
    @case(App\Enums\TaskStatusEnum::complete)
        @include('projects.tasks.forms.change', ['id' => 'return-working-on-task-' . $task->id . '-form', 'project' => $project, 'task' => $task, 'status' => App\Enums\TaskStatusEnum::new])    
        @break
              
@endswitch