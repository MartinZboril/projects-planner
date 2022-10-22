<form id="{{ $id }}" action="{{ route('todos.check', $todo->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
    <input type="hidden" name="action" value="{{ $action }}">
    <input type="hidden" name="redirect" value="{{ $redirect }}">
</form>