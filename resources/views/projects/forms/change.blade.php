<form id="{{ $id }}" action="{{ route('projects.change', $project->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="{{ $status }}">
</form>