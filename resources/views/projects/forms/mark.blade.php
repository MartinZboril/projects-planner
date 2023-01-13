<form id="{{ $id }}" action="{{ route('projects.mark', $project->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>