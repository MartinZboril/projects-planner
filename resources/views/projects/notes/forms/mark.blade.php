<form id="{{ $id }}" action="{{ route('projects.notes.mark', ['project' => $project, 'note' => $note]) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>