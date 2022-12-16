<form action="{{ route('notes.store') }}" method="post">
    @csrf
    @method('POST')
    @include('notes.forms.fields', ['note' => $note, 'parentId' => $parentId, 'parentType' => $parentType, 'type' => 'create'])
</form>         