<!-- Task status change forms -->
@include('tasks.forms.change', ['id' => 'start-working-on-task-' . $task->id . '-form', 'task' => $task, 'status' => App\Enums\TaskStatusEnum::in_progress, 'redirect' => 'tasks'])    
@include('tasks.forms.change', ['id' => 'complete-working-on-task-' . $task->id . '-form', 'task' => $task, 'status' => App\Enums\TaskStatusEnum::complete, 'redirect' => 'tasks'])    
@include('tasks.forms.change', ['id' => 'return-working-on-task-' . $task->id . '-form', 'task' => $task, 'status' => App\Enums\TaskStatusEnum::new, 'redirect' => 'tasks'])    
<!-- Pause work on task form -->
@include('tasks.forms.pause', ['id' => 'stop-working-on-task-' . $task->id . '-form', 'task' => $task, 'action' => 1, 'redirect' => 'tasks'])    
@include('tasks.forms.pause', ['id' => 'resume-working-on-task-' . $task->id . '-form', 'task' => $task, 'action' => 0, 'redirect' => 'tasks'])    