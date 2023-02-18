<form id="{{ $id }}" action="{{ route('projects.tasks.todos.check', ['project' => $todo->task->project, 'task' => $todo->task, 'todo' => $todo]) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
    <input type="hidden" name="action" value="{{ $action }}">
</form>