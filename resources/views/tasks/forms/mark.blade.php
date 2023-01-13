<form id="{{ $id }}" action="{{ route('tasks.mark', $task->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>