<form id="{{ $id }}" action="{{ route('projects.tasks.mark', ['project' => $task->project, 'task' => $task]) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>