<form id="{{ $id }}" action="{{ route('projects.tasks.pause', ['project' => $project, 'task' => $task]) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>