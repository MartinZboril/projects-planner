<form id="{{ $id }}" action="{{ route('tasks.pause', $task) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>