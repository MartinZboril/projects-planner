<form id="{{ $id }}" action="{{ route('notes.mark', $note->id) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
    <input type="hidden" name="parent_id" value="{{ $parentId }}">
    <input type="hidden" name="type" value="{{ $parentType }}">
</form>