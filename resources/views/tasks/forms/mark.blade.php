<form id="{{ $id }}" action="{{ route('tasks.mark', $task) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>