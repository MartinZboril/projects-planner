<form id="{{ $id }}" action="{{ route('projects.tasks.change_status', ['project' => $task->project, 'task' => $task]) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="{{ $status }}">
</form>