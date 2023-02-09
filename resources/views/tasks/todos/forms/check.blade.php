<form id="{{ $id }}" action="{{ route('tasks.todos.check', ['project' => $project, 'task' => $task, 'todo' => $todo]) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
    <input type="hidden" name="action" value="{{ $action }}">
</form>