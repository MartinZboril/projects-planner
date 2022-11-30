<!-- Task status change forms -->
@include('tasks.forms.change', ['id' => 'start-working-on-task-' . $task->id . '-form', 'task' => $task, 'statusId' => 2, 'redirect' => 'tasks'])    
@include('tasks.forms.change', ['id' => 'complete-working-on-task-' . $task->id . '-form', 'task' => $task, 'statusId' => 3, 'redirect' => 'tasks'])    
@include('tasks.forms.change', ['id' => 'return-working-on-task-' . $task->id . '-form', 'task' => $task, 'statusId' => 1, 'redirect' => 'tasks'])    
<!-- Pause work on task form -->
@include('tasks.forms.pause', ['id' => 'stop-working-on-task-' . $task->id . '-form', 'task' => $task, 'action' => 1, 'redirect' => 'tasks'])    
@include('tasks.forms.pause', ['id' => 'resume-working-on-task-' . $task->id . '-form', 'task' => $task, 'action' => 0, 'redirect' => 'tasks'])    