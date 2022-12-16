<form action="{{ route('notes.update', $note->id) }}" method="post">
    @csrf
    @method('PATCH')
    @include('notes.forms.fields', ['note' => $note, 'parentId' => $parentId, 'parentType' => $parentType, 'type' => 'edit'])
</form>  