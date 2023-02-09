<form id="{{ $id }}" action="{{ route('projects.timers.stop', ['project' => $project, 'timer' => $timer]) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>