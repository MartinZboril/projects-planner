<form id="{{ $id }}" action="{{ route('tasks.change_status', $task) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="{{ $status }}">
</form>