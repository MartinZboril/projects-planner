<form id="{{ $id }}" action="{{ route('tasks.pause', $task->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
    <input type="hidden" name="action" value="{{ $action }}">
</form>