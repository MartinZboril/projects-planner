<form id="{{ $id }}" action="{{ route('tasks.change', $task->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status_id" value="{{ $statusId }}">
    <input type="hidden" name="redirect" value="{{ $redirect }}">
</form>