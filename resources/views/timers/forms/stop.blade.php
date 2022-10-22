<form id="{{ $id }}" action="{{ route('timers.stop', $projectId) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>