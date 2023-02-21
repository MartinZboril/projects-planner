<form id="{{ $id }}" action="{{ route('projects.mark', $project) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>